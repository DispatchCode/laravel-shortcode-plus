<?php

namespace Murdercode\LaravelShortcodePlus\Parsers;

use Murdercode\LaravelShortcodePlus\Helpers\ModelHelper;
use Murdercode\LaravelShortcodePlus\Helpers\Sanitizer;

class Image
{
    public static function parse(array $params): string
    {

        $caption = null;
        $credits = null;
        $width = "1920";
        $height = "1080";
        $path = null;
        $alternative_text = null;
        $title = null;

        if (isset($params["url"]))
        {
            $path = $params["url"];
        }
        else
        {
            $id_image = $params['id'];
            $caption = $params['caption'] ? Sanitizer::escapeQuotes($params['caption']) : null;

            $model = new ModelHelper('image');
            $image = $model->getModelClass()::find($id_image);

            if (!$image)
            {
                return 'Image not found';
            }

            $model->setModelInstance($image);
            $caption = $caption ?: $model->getValueFromInstance('caption') ?: null;
            $credits = $model->getValueFromInstance('credits') ?: $credits;
            $width = $model->getValueFromInstance('width') ?: $width;
            $height = $model->getValueFromInstance('height') ?: $height;
            $path = asset('storage/' . $model->getValueFromInstance('path')) ?: $path;
            $alternative_text = $model->getValueFromInstance('alternative_text') ?: $alternative_text;
            $title = $model->getValueFromInstance('title') ?: $title;
        }

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
