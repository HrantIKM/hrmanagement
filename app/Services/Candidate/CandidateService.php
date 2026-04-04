<?php

namespace App\Services\Candidate;

use App\Contracts\Candidate\ICandidateRepository;
use App\Contracts\Skill\ISkillRepository;
use App\Contracts\Vacancy\IVacancyRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CandidateService extends BaseService
{
    public function __construct(
        ICandidateRepository $repository,
        protected IVacancyRepository $vacancyRepository,
        protected ISkillRepository $skillRepository
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

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        $skillIds = array_key_exists('skill_ids', $data) ? $data['skill_ids'] : null;
        unset($data['skill_ids']);

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

        return DB::transaction(function () use ($data, $id, $skillIds) {
            $candidate = $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);

            if ($skillIds !== null) {
                $candidate->skills()->sync($skillIds);
            }

            $candidate->applications()->delete();
            if ($candidate->vacancy_id) {
                $candidate->applications()->create(['vacancy_id' => $candidate->vacancy_id]);
            }

            return $candidate->refresh();
        });
    }
}
