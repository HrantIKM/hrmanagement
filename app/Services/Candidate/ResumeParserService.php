<?php

namespace App\Services\Candidate;

use Smalot\PdfParser\Parser;

class ResumeParserService
{
    public function extractText(string $absolutePath): string
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($absolutePath);
            $text = $pdf->getText();
        } catch (\Throwable) {
            return '';
        }

        return trim(preg_replace('/\s+/', ' ', $text) ?? '');
    }
}
