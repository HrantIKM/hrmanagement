<?php

namespace App\Services\Vacancy;

use App\Contracts\Position\IPositionRepository;
use App\Contracts\Skill\ISkillRepository;
use App\Contracts\Vacancy\IVacancyRepository;
use App\Models\Vacancy\Enums\VacancyStatus;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VacancyService extends BaseService
{
    public function __construct(
        IVacancyRepository $repository,
        protected IPositionRepository $positionRepository,
        protected ISkillRepository $skillRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $vacancySkillIds = null;

        if ($id) {
            $vacancy = $this->repository->find($id, ['skills', 'position']);
            $vacancySkillIds = $vacancy->skills->pluck('id')->all();
        } else {
            $vacancy = $this->repository->getInstance();
        }

        return [
            'vacancy' => $vacancy,
            'positions' => $this->positionRepository->getForSelect(),
            'skills' => $this->skillRepository->getForSelect(),
            'vacancySkillIds' => $vacancySkillIds,
            'vacancyStatusOptions' => collect(VacancyStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('vacancy.status.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        $skillIds = array_key_exists('skill_ids', $data) ? $data['skill_ids'] : null;
        unset($data['skill_ids']);

        if (array_key_exists('position_id', $data) && $data['position_id'] === '') {
            $data['position_id'] = null;
        }

        if (array_key_exists('closing_date', $data) && $data['closing_date'] === '') {
            $data['closing_date'] = null;
        }

        return DB::transaction(function () use ($data, $id, $skillIds) {
            $vacancy = $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);

            if ($skillIds !== null) {
                $vacancy->skills()->sync($skillIds);
            }

            return $vacancy->refresh();
        });
    }
}
