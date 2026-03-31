<?php

if (!function_exists('modifyDataForSelect')) {
    function modifyDataForSelect(array $data, bool $customKey = false, string $addTransKey = ''): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$customKey ? $key : $value] = $addTransKey ? trans($addTransKey . '.' . $value) : $value;
        }

        return $result;
    }
}
