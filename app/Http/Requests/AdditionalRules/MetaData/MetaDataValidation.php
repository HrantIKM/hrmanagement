<?php

namespace App\Http\Requests\AdditionalRules\MetaData;

class MetaDataValidation
{
    public static function rules(bool $isRequired = false): array
    {
        $requiredRule = $isRequired ? 'required' : 'nullable';

        return [
            'ml' => "$requiredRule|array",
            'ml.*.meta_title' => "$requiredRule|string_with_max",
            'ml.*.meta_description' => "$requiredRule|text_with_max",
            'ml.*.meta_keywords' => "$requiredRule|string_with_max",
        ];
    }
}
