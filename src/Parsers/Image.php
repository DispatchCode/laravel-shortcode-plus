<?php

namespace Murdercode\LaravelShortcodePlus\Parsers;

use Murdercode\LaravelShortcodePlus\Helpers\ModelHelper;
use Murdercode\LaravelShortcodePlus\Helpers\Sanitizer;

class Image
{
    public static function parse(array $params): string
    {

        $id_image = $params['id'];
        $caption = $params['caption'] ? Sanitizer::escapeQuotes($params['caption']) : null;

        $image = ModelHelper::getInstance($id_image);

        if (!$image)
        {
            return 'Image not found';
        }

        $caption = $caption ?: ModelHelper::getValue($image, 'caption') ?: null;
        $credits = ModelHelper::getValue($image, 'credits') ?: null;
        $width = ModelHelper::getValue($image, 'width') ?: '1920';
        $height = ModelHelper::getValue($image, 'height') ?: '1080';
        $path = ModelHelper::getValue($image, 'path') ?: null;
        $alternative_text = ModelHelper::getValue($image, 'alternative_text') ?: null;
        $title = ModelHelper::getValue($image, 'title') ?: null;

        return view(
            'shortcode-plus::image',
            compact(
                'caption',
                'path',
                'credits',
                'width',
                'height',
                'alternative_text',
                'title'
            )
        )->render();
    }
}
