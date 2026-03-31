<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Contracts\User\IUserRepository;
use App\Http\Controllers\Dashboard\BaseController;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UserSearchRequest;
use App\Models\User\User;
use App\Models\User\UserSearch;
use App\Services\User\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function __construct(
        IUserRepository $repository,
        UserService $service
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('user.index');
    }

    public function getListData(UserSearchRequest $request): array
    {
        $searcher = new UserSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'user.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(UserRequest $request): JsonResponse
    {
        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.users.index'),
        ]);
    }

    public function show(User $user): View
    {
        return $this->dashboardView(
            view: 'user.form',
            vars: $this->service->getViewData($user->id),
            viewMode: 'show'
        );
    }

    public function edit(User $user): View
    {
        return $this->dashboardView(
            view: 'user.form',
            vars: $this->service->getViewData($user->id),
            viewMode: 'edit'
        );
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $this->service->createOrUpdate($request->validated(), $user->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.users.index'),
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user->id);

        return $this->sendOkDeleted();
    }
}
