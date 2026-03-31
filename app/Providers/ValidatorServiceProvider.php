<?php

namespace App\Providers;

use App\Models\Base\Enums\ShowStatus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->globalValidators();

        $this->existsValidators();
    }

    private function globalValidators(): void
    {
        $maxStringLength = 250;

        $minIntegerLength = 0;
        $maxIntegerLength = 2000000000;

        $maxTextLength = 5000;

        $minDoubleLength = 0;
        $maxDoubleLength = 999999.99;

        $minPhoneNumberLength = 8;
        $maxPhoneNumberLength = 12;

        // Max String
        Validator::extend('string_with_max', function ($attribute, $value) use ($maxStringLength) {
            $rules = [$attribute => 'string|max:' . $maxStringLength];

            return $this->validator($this->getAttributeValue($attribute, $value), $rules);
        }, trans('validation.max.string', ['max' => $maxStringLength]));

        // Max Text
        Validator::extend('text_with_max', function ($attribute, $value) use ($maxTextLength) {
            $rules = [$attribute => 'string|max:' . $maxTextLength];

            return $this->validator($this->getAttributeValue($attribute, $value), $rules);
        }, trans('validation.max.string', ['max' => $maxTextLength]));

        // Max Integer
        Validator::extend('integer_with_max', function ($attribute, $value) use ($minIntegerLength, $maxIntegerLength) {
                $rules = [$attribute => "integer|between:$minIntegerLength,$maxIntegerLength"];

                return $this->validator($this->getAttributeValue($attribute, $value), $rules);
            },
            trans('validation.between.numeric', ['min' => $minIntegerLength, 'max' => $maxIntegerLength])
        );

        // Max Double
        Validator::extend('double_with_max', function ($attribute, $value) use ($minDoubleLength, $maxDoubleLength) {
                $rules = [$attribute => "numeric|gt:0|between:$minDoubleLength,$maxDoubleLength"];

                return $this->validator($this->getAttributeValue($attribute, $value), $rules);
            },
            trans('validation.between.numeric', ['min' => $minDoubleLength, 'max' => $maxDoubleLength])
        );

        // Phone number
        Validator::extend('phone_number_validator', function ($attribute, $value) use ($minPhoneNumberLength, $maxPhoneNumberLength) {
                $rules = [
                    $attribute => "gt:0|between:$minPhoneNumberLength,$maxPhoneNumberLength|regex:/^([0-9\\s\\-\\+\\(\\)]*)$/",
                ];

                return $this->validator($this->getAttributeValue($attribute, $value), $rules);
            },
            trans('validation.invalid', ['min' => $minPhoneNumberLength, 'max' => $maxPhoneNumberLength])
        );

        // Show status
        Validator::extend('show_status_validator', function ($attribute, $value) {
            $rules = [$attribute => 'in:' . implode(',', ShowStatus::FOR_SELECT)];

            return $this->validator($this->getAttributeValue($attribute, $value), $rules);
        }, trans('validation.invalid'));

        // Datetime
        Validator::extend('datetime', function ($attribute, $value) {
            $rules = [$attribute => 'date_format:' . getDateTimeFormat()];

            return $this->validator($this->getAttributeValue($attribute, $value), $rules);
        }, trans('validation.invalid'));

        // Date
        Validator::extend('date_validator', function ($attribute, $value) {
            $rules = [$attribute => 'date_format:' . getDateFormat()];

            return $this->validator($this->getAttributeValue($attribute, $value), $rules);
        }, trans('validation.invalid'));

        // After or equal today
        Validator::extend('after_or_equal_today', function ($attribute, $value) {
            $rules = [$attribute => 'date_validator|after_or_equal:' . today()];

            return $this->validator($this->getAttributeValue($attribute, $value), $rules);
        }, trans('validation.custom.after_or_equal', ['date' => now()->format(getDateFormatFront())]));

        // Email Validator
        Validator::extend('email_validator', function ($attribute, $value) {
            $rules = [$attribute => 'string|email|min:5|max:60'];

            return $this->validator($this->getAttributeValue($attribute, $value), $rules);
        }, trans('validation.max.string', ['max' => 50]));

        // Name Validator
        Validator::extend('name_validator', function ($attribute, $value, $parameters) {
            $rules = [$attribute => 'string_with_max|regex:/^[^@\d]+$/u'];

            return $this->validator($this->getAttributeValue($attribute, $value), $rules);
        }, trans('validation.invalid'));
    }

    private function existsValidators(): void
    {
        Validator::extend('exist_validator', function ($attribute, $value, $parameters) {
            $tableName = $parameters[0];
            $checkField = $parameters[1] ?? 'id';

            $rules = ['integer_with_max|exists:' . $tableName . ',' . $checkField];

            return $this->validator($this->getAttributeValue($attribute, $value), $rules);
        }, trans('validation.invalid'));
    }

    private function getAttributeValue(mixed $attribute, mixed $value): array
    {
        $data = [];

        if (str_contains($attribute, '.')) {
            $data = $this->dataArray(explode('.', $attribute), $value);
        } else {
            $data[$attribute] = $value;
        }

        return $data;
    }

    private function validator(array $data, array $rules): bool
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return false;
        }

        return true;
    }

    private function dataArray(array $array, array|string $value): array
    {
        $resultArray = $value;
        for ($i = count($array) - 1; $i >= 0; $i--) {
            $resultArray = [$array[$i] => $resultArray];
        }

        return $resultArray;
    }
}
