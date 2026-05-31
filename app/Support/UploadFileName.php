<?php

namespace App\Support;

class UploadFileName
{
    public static function sanitize(string $fileName, int $maxLength = 120): string
    {
        // Strip filesystem-reserved characters, URL-breaking characters (#, '), and control characters.
        // # fragments the URL so the server never sees the full path. ' breaks shell/CSV contexts.
        $clean = preg_replace('/[\\\\\\/:*?"<>|#\'\\x00-\\x1F\\x7F]+/u', '', $fileName) ?? $fileName;
        $clean = preg_replace('/\\s+/u', ' ', $clean) ?? $clean;
        $clean = trim($clean);

        if ($clean === '') {
            return '';
        }

        return mb_substr($clean, 0, $maxLength);
    }
}
