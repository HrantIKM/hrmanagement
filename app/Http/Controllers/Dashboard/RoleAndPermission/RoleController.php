<?php

namespace App\Http\Controllers\Dashboard\RoleAndPermission;

use App\Contracts\Role\IRoleRepository;
use App\Http\Controllers\Dashboard\BaseController;
use App\Http\Requests\Role\RoleSearchRequest;
use App\Models\RoleAndPermission\RoleSearch;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

// NOT USED FOR NOW
class RoleController extends BaseController
{
    public function __construct(IRoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('roles.index');
    }

    public function getListData(RoleSearchRequest $request): array
    {
        $searcher = new RoleSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): void
    {
    }

    public function store(Request $request): void
    {
    }

    public function show(int $id): void
    {
    }

    public function edit(int $id): void
    {
    }

    public function update(Request $request, int $id): void
    {
    }

    public function destroy(int $id): void
    {
    }
}
