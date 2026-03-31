<?php

if (!function_exists('replaceNameWithDots')) {
    function replaceNameWithDots(mixed $name): string
    {
        if (str_contains($name, '[')) {
            $name = str_replace(['[', ''], '.', $name);
            $name = str_replace([']', ''], '', $name);

            if ($name[-1] == '.') {
                $name = rtrim($name, '.');
            }
        }

        return $name;
    }
}
