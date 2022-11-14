<?php

namespace Murdercode\LaravelShortcodePlus\Parsers;

class Spoiler
{
    public static function parse(array $params, string $content): string
    {
        $title = $params['title'] ?? __('Spoiler alert').'! '.__('Click to reveal');

        return view('shortcode-plus::spoiler', compact('content', 'title'))->render();
    }
}
