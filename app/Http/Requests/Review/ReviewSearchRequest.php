<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\Core\DatatableSearchRequest;
use App\Models\Review\Enums\ReviewPeriod;
use Illuminate\Validation\Rule;

class ReviewSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'f.id' => 'nullable|integer_with_max',
            'f.user_id' => 'nullable|integer_with_max|exists:users,id',
            'f.reviewer_id' => 'nullable|integer_with_max|exists:users,id',
            'f.review_period' => ['nullable', Rule::in(ReviewPeriod::ALL)],
        ];
    }
}
