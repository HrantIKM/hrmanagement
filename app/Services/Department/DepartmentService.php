<?php

namespace App\Services\Department;

use App\Contracts\Department\IDepartmentRepository;
use App\Models\Department\Department;
use App\Models\Position\Position;
use App\Models\Skill\Skill;
use App\Models\User\User;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class DepartmentService extends BaseService
{
    public function __construct(
        IDepartmentRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        if ($id === null) {
            return [
                'department' => $this->repository->getInstance(),
                'parentDepartments' => $this->parentDepartmentOptions(excludeId: null),
            ];
        }

        $department = $this->repository->find($id, ['icon']);

        return [
            'department' => $department,
            'parentDepartments' => $this->parentDepartmentOptions(excludeId: $department->id),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function parentDepartmentOptions(?int $excludeId): array
    {
        $q = Department::query()->orderBy('name');
        if ($excludeId !== null) {
            $excludeIds = array_merge([$excludeId], $this->descendantIds($excludeId));
            $q->whereKeyNot($excludeIds);
        }

        return $q->get(['id', 'name'])->map(fn (Department $d) => [
            'id' => $d->id,
            'name' => $d->name,
        ])->all();
    }

    /**
     * @return list<int>
     */
    public function descendantIds(int $departmentId): array
    {
        $ids = [];
        $queue = [$departmentId];
        while ($queue !== []) {
            $current = array_shift($queue);
            $children = Department::query()->where('parent_id', $current)->pluck('id')->all();
            foreach ($children as $cid) {
                $ids[] = (int) $cid;
                $queue[] = (int) $cid;
            }
        }

        return $ids;
    }

    /**
     * Nested tree + rich payloads for the interactive department hub.
     *
     * @return array{tree: array<int, array<string, mixed>>, flat: array<int, array<string, mixed>>}
     */
    public function buildHubPayload(): array
    {
        $departments = Department::query()
            ->with('icon')
            ->withCount(['users', 'positions', 'skills'])
            ->orderBy('name')
            ->get();

        if ($departments->isEmpty()) {
            return ['tree' => [], 'flat' => []];
        }

        $ids = $departments->pluck('id')->all();

        $usersByDept = User::query()
            ->whereIn('department_id', $ids)
            ->with(['avatar', 'position:id,title'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->groupBy('department_id');

        $positionsByDept = Position::query()
            ->whereIn('department_id', $ids)
            ->orderBy('title')
            ->get()
            ->groupBy('department_id');

        $skillsByDept = Skill::query()
            ->whereIn('department_id', $ids)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('department_id');

        $memberSkillNamesByDept = [];
        $skillAccum = [];
        foreach ($ids as $did) {
            $skillAccum[$did] = [];
        }
        $usersWithSkills = User::query()
            ->whereIn('department_id', $ids)
            ->with(['skills:id,name'])
            ->get(['id', 'department_id']);
        foreach ($usersWithSkills as $u) {
            $did = $u->department_id;
            if ($did === null) {
                continue;
            }
            foreach ($u->skills as $s) {
                $skillAccum[$did][$s->id] = ['id' => $s->id, 'name' => $s->name];
            }
        }
        foreach ($ids as $did) {
            $list = array_values($skillAccum[$did] ?? []);
            usort($list, fn (array $a, array $b) => strcmp($a['name'], $b['name']));
            $memberSkillNamesByDept[$did] = array_slice($list, 0, 40);
        }

        $flat = [];
        foreach ($departments as $d) {
            $flat[$d->id] = $this->serializeHubDepartment(
                $d,
                $departments,
                $usersByDept,
                $positionsByDept,
                $skillsByDept,
                $memberSkillNamesByDept
            );
        }

        $roots = $departments->filter(fn (Department $d) => $d->parent_id === null)->values();
        $tree = $roots->map(function (Department $d) use ($departments, $flat) {
            return $this->buildHubTreeNode($d, $departments, $flat);
        })->values()->all();

        return ['tree' => $tree, 'flat' => $flat];
    }

    /**
     * @param  array<int, array<string, mixed>>  $flat
     * @return array<string, mixed>
     */
    private function buildHubTreeNode(Department $d, Collection $all, array $flat): array
    {
        $node = $flat[$d->id];
        $children = $all->where('parent_id', $d->id)->sortBy('name')->values();
        $node['children'] = $children->map(fn (Department $c) => $this->buildHubTreeNode($c, $all, $flat))->values()->all();

        return $node;
    }

    /**
     * @param  Collection<int, Department>  $allDepts
     * @param  \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, User>>  $usersByDept
     * @param  \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, Position>>  $positionsByDept
     * @param  \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, Skill>>  $skillsByDept
     * @param  array<int, list<array{id: int, name: string}>>  $memberSkillNamesByDept
     * @return array<string, mixed>
     */
    private function serializeHubDepartment(
        Department $d,
        Collection $allDepts,
        Collection $usersByDept,
        Collection $positionsByDept,
        Collection $skillsByDept,
        array $memberSkillNamesByDept
    ): array {
        $uid = $d->id;
        $users = $usersByDept->get($uid, collect());
        $preview = $users->take(24)->map(function (User $u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'avatar_url' => $u->avatar_url,
                'position' => $u->position?->title,
            ];
        })->values()->all();

        $skills = $skillsByDept->get($uid, collect());
        $skillsByCategory = $skills->groupBy('category')->map(
            fn (Collection $group) => $group->map(fn (Skill $s) => [
                'id' => $s->id,
                'name' => $s->name,
            ])->values()->all()
        )->all();

        $children = $allDepts->where('parent_id', $d->id)->sortBy('name')->values()->map(
            fn (Department $c) => [
                'id' => $c->id,
                'name' => $c->name,
                'counts' => [
                    'members' => (int) $c->users_count,
                    'positions' => (int) $c->positions_count,
                    'skills' => (int) $c->skills_count,
                ],
            ]
        )->all();

        return [
            'id' => $d->id,
            'name' => $d->name,
            'description' => $d->description,
            'parent_id' => $d->parent_id,
            'icon_url' => $d->icon?->file_url,
            'counts' => [
                'members' => (int) $d->users_count,
                'positions' => (int) $d->positions_count,
                'skills' => (int) $d->skills_count,
            ],
            'members_preview' => $preview,
            'members_more' => max(0, $users->count() - count($preview)),
            'positions' => $positionsByDept->get($uid, collect())->map(fn (Position $p) => [
                'id' => $p->id,
                'title' => $p->title,
            ])->values()->all(),
            'skills_by_category' => $skillsByCategory,
            'member_skills' => $memberSkillNamesByDept[$uid] ?? [],
            'children' => $children,
        ];
    }
}
