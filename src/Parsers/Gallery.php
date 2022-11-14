<?php

namespace Murdercode\LaravelShortcodePlus\Parsers;

use Murdercode\LaravelShortcodePlus\Helpers\ModelHelper;
use Murdercode\LaravelShortcodePlus\Helpers\Sanitizer;

class Gallery
{
    public static function parse(array $params): string
    {
        $title = Sanitizer::escapeQuotes($params['title']);

        $imagesArray = explode(',', $params['images']);

        $model = new ModelHelper('image');
        $images = $model->getModelClass()::whereIn('id', $imagesArray)->get()->toArray();

        return view('shortcode-plus::gallery', compact('title', 'images'))
            ->render();
    }
}
