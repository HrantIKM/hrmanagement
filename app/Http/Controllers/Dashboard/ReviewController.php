<?php

namespace App\Http\Controllers\Dashboard;

use App\Contracts\Review\IReviewRepository;
use App\Http\Controllers\Dashboard\Concerns\AuthorizesDashboardEmployeeAccess;
use App\Http\Requests\Review\ReviewRequest;
use App\Http\Requests\Review\ReviewSearchRequest;
use App\Models\Review\Review;
use App\Models\Review\ReviewSearch;
use App\Services\Review\ReviewService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ReviewController extends BaseController
{
    use AuthorizesDashboardEmployeeAccess;

    public function __construct(
        ReviewService $service,
        IReviewRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(): View
    {
        return $this->dashboardView('review.index', array_merge($this->service->getIndexViewData(), [
            'createRoute' => $this->dashboardUserIsAdmin() ? route('dashboard.reviews.create') : null,
        ]));
    }

    public function getListData(ReviewSearchRequest $request): array
    {
        $searcher = new ReviewSearch($request->validated());

        return [
            'recordsTotal' => $searcher->totalCount(),
            'recordsFiltered' => $searcher->filteredCount(),
            'data' => $searcher->search(),
        ];
    }

    public function create(): View
    {
        return $this->dashboardView(
            view: 'review.form',
            vars: $this->service->getViewData()
        );
    }

    public function store(ReviewRequest $request): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated());

        return $this->sendOkCreated([
            'redirectUrl' => route('dashboard.reviews.index'),
        ]);
    }

    public function show(Review $review): View
    {
        return $this->dashboardView(
            view: 'review.form',
            vars: $this->service->getViewData($review->id),
            viewMode: 'show'
        );
    }

    public function edit(Review $review): View
    {
        $this->abortUnlessAdminCanManageHrRecords();

        return $this->dashboardView(
            view: 'review.form',
            vars: $this->service->getViewData($review->id),
            viewMode: 'edit'
        );
    }

    public function update(ReviewRequest $request, Review $review): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->createOrUpdate($request->validated(), $review->id);

        return $this->sendOkUpdated([
            'redirectUrl' => route('dashboard.reviews.index'),
        ]);
    }

    public function destroy(Review $review): JsonResponse
    {
        $this->abortUnlessAdminCanManageHrRecords();

        $this->service->delete($review->id);

        return $this->sendOkDeleted();
    }
}
