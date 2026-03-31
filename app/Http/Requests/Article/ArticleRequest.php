<?php

namespace App\Http\Requests\Article;

use App\Http\Requests\AdditionalRules\MetaData\MetaDataValidation;
use App\Models\Article\Article;
use App\Models\User\User;
use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug' => 'required|string|max:50|unique:' . Article::getTableName() . ',slug,' . $this->article?->id,
            'publish_date' => 'required|after_or_equal_today',
            'release_date_time' => 'required|datetime',
            'photo' => 'required|string_with_max',

            'multiple_group_data' => 'nullable|array',
            'multiple_group_data.*.title' => 'nullable|string_with_max',
            'multiple_group_data.*.link' => 'nullable|url|string_with_max',
            'multiple_group_data.*.desc' => 'nullable|string_with_max',
            'multiple_group_data.*.user' => 'nullable|exist_validator:' . User::getTableName(),

            'multiple_author' => 'nullable|array',
            'multiple_author.*' => 'nullable|string_with_max',

            'show_status' => 'required|show_status_validator',

            'ml' => 'required|array',
            'ml.*.title' => 'required|string|max:100',
            'ml.*.short_description' => 'required|string_with_max',
            'ml.*.description' => 'required|text_with_max',
        ] + MetaDataValidation::rules();
    }
}
