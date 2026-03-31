<?php

if (!function_exists('isProduction')) {
    function isProduction(): bool
    {
        return config('app.env') === 'production';
    }
}

if (!function_exists('isAwsFilesystem')) {
    function isAwsFilesystem(): bool
    {
        return config('filesystems.default') === 's3';
    }
}

if (!function_exists('formattedPrice')) {
    function formattedPrice(?string $price = '', bool $addIcon = false): string
    {
        $result = 0;
        if ($price) {
            switch (currentLanguageCode()) {
                case 'de':
                    $decimalOperator = ',';
                    $thousandOperator = '.';
                    break;

                default:
                    $decimalOperator = '.';
                    $thousandOperator = ',';
            }

            $result = number_format($price, 2, $decimalOperator, $thousandOperator);

            if ($addIcon) {
                $result = $result . ' ' . getCurrencyIcon();
            }
        }

        return $result;
    }
}

if (!function_exists('getCurrencyIcon')) {
    function getCurrencyIcon(): string
    {
        return match (currentLanguageCode()) {
            'de' => config('enums.currencies.EUR.icon'),
            'en' => config('enums.currencies.USD.icon'),
            default => ''
        };
    }
}
