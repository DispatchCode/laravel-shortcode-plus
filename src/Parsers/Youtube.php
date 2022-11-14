<?php

namespace Murdercode\LaravelShortcodePlus\Parsers;

class Youtube
{
    public static function parse(array $params): string
    {

        $url = $params["url"] ?? null;
        $url = str_contains($url, 'youtube.com') ? $url : null;
        preg_match('/youtube.com\/watch\?v=(.*)/', $url, $matches);

        $youtubeId = $matches[1] ?? null;

        if ($youtubeId)
        {
            return view(
                'shortcode-plus::youtube',
                ['youtubeId' => $youtubeId]
            )->render();
        }

        return 'No youtube URL defined';
    }
}
