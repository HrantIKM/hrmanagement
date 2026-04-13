<?php

namespace App\Services\Candidate;

use App\Models\Vacancy\Vacancy;

class CandidateMatchingService
{
    public function calculateForVacancy(Vacancy $vacancy, string $resumeText): array
    {
        $normalized = $this->normalizeText($resumeText);
        $tokens = $this->tokenize($normalized);
        $skills = $vacancy->skills()->get(['skills.id', 'skills.name']);
        $requiredSkillCount = $skills->count();

        if ($requiredSkillCount === 0 || $normalized === '') {
            return [
                'match_score' => $requiredSkillCount === 0 ? null : 0,
                'matched_skill_ids' => [],
                'matched_skill_names' => [],
                'required_skill_count' => $requiredSkillCount,
            ];
        }

        $matchedSkills = $skills->filter(function ($skill) use ($normalized, $tokens) {
            $name = $this->normalizeText((string) $skill->name);
            if ($name === '') {
                return false;
            }

            if (str_contains($normalized, $name)) {
                return true;
            }

            $skillTokens = $this->tokenize($name);
            if (count($skillTokens) === 0) {
                return false;
            }

            $intersections = array_intersect($skillTokens, $tokens);
            $ratio = count($intersections) / count($skillTokens);

            return $ratio >= 0.6;
        })->values();

        $matchScore = (int) round(($matchedSkills->count() / $requiredSkillCount) * 100);

        return [
            'match_score' => $matchScore,
            'matched_skill_ids' => $matchedSkills->pluck('id')->all(),
            'matched_skill_names' => $matchedSkills->pluck('name')->all(),
            'required_skill_count' => $requiredSkillCount,
        ];
    }

    private function normalizeText(string $value): string
    {
        $value = mb_strtolower($value);
        $value = preg_replace('/[^a-z0-9\+\#\.\-\s]/u', ' ', $value) ?? '';
        $value = preg_replace('/\s+/', ' ', $value) ?? '';

        return trim($value);
    }

    private function tokenize(string $value): array
    {
        if ($value === '') {
            return [];
        }

        return array_values(array_filter(explode(' ', $value)));
    }
}
