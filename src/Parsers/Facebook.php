<?php

namespace Murdercode\LaravelShortcodePlus\Parsers;

class Facebook
{
    public static function parse(array $params): string
    {
        $url = str_contains($params['url'], 'facebook.com') ? $params['url'] : null;
        if ($url) {
            return view('shortcode-plus::facebook', compact('url'))->render();
        }

        return 'No Facebook URL found';
    }
}
