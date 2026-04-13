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
use Illuminate\Http\RedirectResponse;

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

    public function index(): View|RedirectResponse
    {
        if (!$this->dashboardUserIsAdmin()) {
            return redirect()->route('dashboard.reviews.mine');
        }

        return $this->dashboardView('review.index', array_merge($this->service->getIndexViewData(), [
            'createRoute' => route('dashboard.reviews.create'),
        ]));
    }

    public function myIndex(): View
    {
        view()->share('subHeaderData', ['pageName' => 'review.my-index']);

        $userId = (int) auth()->id();
        $reviews = Review::query()
            ->with(['reviewer:id,first_name,last_name,email'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $avg = $reviews->avg('rating');
        $stats = [
            'count' => $reviews->count(),
            'avg_rating' => $avg !== null ? round((float) $avg, 2) : null,
            'latest_at' => $reviews->first()?->created_at,
        ];

        return $this->dashboardView('review.my-index', array_merge($this->service->getIndexViewData(), [
            'reviews' => $reviews,
            'stats' => $stats,
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
        $this->abortUnlessAdminCanManageHrRecords();

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
        $this->abortUnlessAdminOrOwnsUserId($review->user_id);

        return $this->dashboardView(
            view: 'review.form',
            vars: array_merge($this->service->getViewData($review->id), [
                'indexUrl' => $this->dashboardUserIsAdmin()
                    ? route('dashboard.reviews.index')
                    : route('dashboard.reviews.mine'),
            ]),
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
