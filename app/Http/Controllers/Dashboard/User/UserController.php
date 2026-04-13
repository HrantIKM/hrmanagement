<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Contracts\User\IUserRepository;
use App\Http\Controllers\Dashboard\BaseController;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UserSearchRequest;
use App\Exports\UsersExport;
use App\Models\User\Enums\EmploymentStatus;
use App\Models\User\User;
use App\Models\User\UserSearch;
use App\Services\User\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $totalUsers = User::count();
        $activeUsers = User::where('employment_status', EmploymentStatus::ACTIVE)->count();
        $onLeaveUsers = User::where('employment_status', EmploymentStatus::ON_LEAVE)->count();
        $withAvatar = User::whereHas('avatar')->count();

        $recentUsers = User::with(['avatar', 'department', 'position'])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return $this->dashboardView('user.index', compact(
            'totalUsers',
            'activeUsers',
            'onLeaveUsers',
            'withAvatar',
            'recentUsers'
        ));
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

    public function exportExcel(): BinaryFileResponse
    {
        return Excel::download(new UsersExport(), 'employees-report.xlsx');
    }

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'employees-report.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        return response()->stream(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Email', 'Department', 'Position', 'Employment Status']);

            User::query()->with(['department:id,name', 'position:id,title'])->chunk(300, function ($rows) use ($out) {
                foreach ($rows as $user) {
                    fputcsv($out, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->department?->name,
                        $user->position?->title,
                        $user->employment_status,
                    ]);
                }
            });
            fclose($out);
        }, 200, $headers);
    }
}
