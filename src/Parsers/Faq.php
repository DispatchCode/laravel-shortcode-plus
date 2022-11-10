<?php

namespace Murdercode\LaravelShortcodePlus\Parsers;

use Murdercode\LaravelShortcodePlus\Helpers\Sanitizer;

class Faq
{
    public static function parse(array $params, string $content): string
    {

        $title = $params["title"] ? Sanitizer::escapeQuotes($params["title"]) : __(
            'Show hidden content'
        );
        $content = $content ?? '';

        return view('shortcode-plus::faq', compact('title', 'content'))->render();
    }
}
