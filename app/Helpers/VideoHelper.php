<?php

if (!function_exists('youtube_embed_url')) {
    function youtube_embed_url(?string $url): ?string
    {
        if (!$url) return null;

        $videoId = null;
        $startSeconds = null;

        // ===============================
        // 1. EXTRACT VIDEO ID
        // ===============================

        // youtube.com/watch?v=ID
        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $m)) {
            $videoId = $m[1];
        }

        // youtu.be/ID
        elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $m)) {
            $videoId = $m[1];
        }

        // youtube.com/embed/ID
        elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $m)) {
            $videoId = $m[1];
        }

        if (!$videoId) {
            // fallback (vimeo / lainnya)
            return $url;
        }

        // ===============================
        // 2. HANDLE TIMESTAMP
        // ===============================

        // ?t=90 / &t=90s / 1m30s
        if (preg_match('/[?&]t=([0-9hms]+)/', $url, $m)) {
            $startSeconds = convertToSeconds($m[1]);
        }

        // ?start=90
        if (preg_match('/[?&]start=([0-9]+)/', $url, $m)) {
            $startSeconds = (int) $m[1];
        }

        // ===============================
        // 3. BUILD EMBED URL (NO PLAYLIST)
        // ===============================

        $embedUrl = "https://www.youtube.com/embed/{$videoId}";

        if ($startSeconds && $startSeconds > 0) {
            $embedUrl .= "?start={$startSeconds}";
        }

        return $embedUrl;
    }
}

/**
 * Convert 1h2m30s / 90s / 120 → seconds
 */
if (!function_exists('convertToSeconds')) {
    function convertToSeconds(string $time): int
    {
        if (is_numeric($time)) {
            return (int) $time;
        }

        $seconds = 0;

        if (preg_match('/(\d+)h/', $time, $m)) {
            $seconds += $m[1] * 3600;
        }

        if (preg_match('/(\d+)m/', $time, $m)) {
            $seconds += $m[1] * 60;
        }

        if (preg_match('/(\d+)s/', $time, $m)) {
            $seconds += $m[1];
        }

        return $seconds;
    }
}
