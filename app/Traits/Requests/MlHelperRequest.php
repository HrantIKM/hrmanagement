<?php

namespace App\Traits\Requests;

trait MlHelperRequest
{
    /**
     * Function to making rules set default lng required, other languages set nullable(for default).
     */
    public function makeMlRules(array $rules = []): array
    {
        $defaultLangCode = 'en';
        $modifiedRules['ml'] = 'required|array';

        foreach ($rules as $field => $rule) {
            $modifiedRules["ml.*.$field"] = str_replace('required', 'nullable', $rule);
            $modifiedRules["ml.$defaultLangCode.$field"] = $rule;
        }

        return $modifiedRules;
    }
}
