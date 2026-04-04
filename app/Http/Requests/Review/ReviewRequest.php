<?php

namespace App\Http\Requests\Review;

use App\Models\Review\Enums\ReviewPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'rating' => 'required|numeric|min:1|max:5',
            'feedback_text' => 'nullable|text_with_max',
            'review_period' => ['required', Rule::in(ReviewPeriod::ALL)],
            'user_id' => 'required|integer_with_max|exists:users,id',
            'reviewer_id' => 'required|integer_with_max|exists:users,id|different:user_id',
        ];
    }
}
