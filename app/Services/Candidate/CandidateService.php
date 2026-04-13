<?php

namespace App\Services\Candidate;

use App\Contracts\Candidate\ICandidateRepository;
use App\Contracts\Skill\ISkillRepository;
use App\Contracts\Vacancy\IVacancyRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CandidateService extends BaseService
{
    public function __construct(
        ICandidateRepository $repository,
        protected IVacancyRepository $vacancyRepository,
        protected ISkillRepository $skillRepository,
        protected ResumeParserService $resumeParserService,
        protected CandidateMatchingService $candidateMatchingService
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $candidateSkillIds = null;

        if ($id) {
            $candidate = $this->repository->find($id, ['skills', 'vacancy']);
            $candidateSkillIds = $candidate->skills->pluck('id')->all();
        } else {
            $candidate = $this->repository->getInstance();
        }

        return [
            'candidate' => $candidate,
            'vacancies' => $this->vacancyRepository->getForSelect(),
            'skills' => $this->skillRepository->getForSelect(),
            'candidateSkillIds' => $candidateSkillIds,
        ];
    }

    public function getDetailViewData(int $id): array
    {
        $candidate = $this->repository->find($id, ['vacancy.skills', 'skills']);
        $aiData = is_array($candidate->raw_ai_data) ? $candidate->raw_ai_data : [];
        $matchedSkillNames = $aiData['matched_skill_names'] ?? [];
        $requiredSkillCount = (int) ($aiData['required_skill_count'] ?? 0);
        $resumeExcerpt = (string) ($aiData['resume_excerpt'] ?? '');

        return [
            'candidate' => $candidate,
            'matchedSkillNames' => is_array($matchedSkillNames) ? $matchedSkillNames : [],
            'requiredSkillCount' => $requiredSkillCount,
            'resumeExcerpt' => $resumeExcerpt,
            'rawAiDataJson' => $aiData ? json_encode($aiData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : null,
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null, ?UploadedFile $resumeFile = null): Model
    {
        $resume = $resumeFile ?? request()->file('resume');
        $skillIds = array_key_exists('skill_ids', $data) ? $data['skill_ids'] : null;
        unset($data['skill_ids'], $data['resume']);

        if (array_key_exists('vacancy_id', $data) && $data['vacancy_id'] === '') {
            $data['vacancy_id'] = null;
        }

        if (array_key_exists('match_score', $data) && $data['match_score'] === '') {
            $data['match_score'] = null;
        }

        if (array_key_exists('raw_ai_data', $data) && ($data['raw_ai_data'] === '' || $data['raw_ai_data'] === null)) {
            $data['raw_ai_data'] = null;
        } elseif (isset($data['raw_ai_data']) && is_string($data['raw_ai_data'])) {
            $decoded = json_decode($data['raw_ai_data'], true);
            $data['raw_ai_data'] = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }

        return DB::transaction(function () use ($data, $id, $skillIds, $resume) {
            $candidate = $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);

            if ($resume instanceof UploadedFile && $resume->isValid()) {
                if ($candidate->resume_path) {
                    Storage::disk('public')->delete($candidate->resume_path);
                }

                $candidate->resume_path = $resume->store('candidates/resumes', 'public');
                $candidate->save();
            }

            if ($skillIds !== null) {
                $candidate->skills()->sync($skillIds);
            }

            if ($candidate->vacancy_id && $candidate->resume_path) {
                $vacancy = $this->vacancyRepository->find($candidate->vacancy_id, ['skills']);
                $absolutePath = Storage::disk('public')->path($candidate->resume_path);
                $resumeText = $this->resumeParserService->extractText($absolutePath);
                $matching = $this->candidateMatchingService->calculateForVacancy($vacancy, $resumeText);

                $candidate->match_score = $matching['match_score'];
                $candidate->raw_ai_data = [
                    'parser' => 'pdf_text_skill_matcher_v1',
                    'resume_excerpt' => mb_substr($resumeText, 0, 1500),
                    'required_skill_count' => $matching['required_skill_count'],
                    'matched_skill_names' => $matching['matched_skill_names'],
                ];
                $candidate->skills()->sync(array_values(array_unique(array_merge(
                    $candidate->skills()->pluck('skills.id')->all(),
                    $matching['matched_skill_ids']
                ))));
            }

            $candidate->applications()->delete();
            if ($candidate->vacancy_id) {
                $candidate->applications()->create(['vacancy_id' => $candidate->vacancy_id]);
            }

            $candidate->save();

            return $candidate->refresh();
        });
    }

    public function delete(int $id): void
    {
        $candidate = $this->repository->find($id);
        if ($candidate->resume_path) {
            Storage::disk('public')->delete($candidate->resume_path);
        }

        parent::delete($id);
    }
}
