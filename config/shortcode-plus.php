<?php

return [
    'model' => [
        'image' => [
            // Your model class
            'class' => Murdercode\LaravelShortcodePlus\Models\ShortcodeImage::class,
            'attributes' => [
                'caption' => 'caption',
                'credits' => 'credits',
                'alternative_text' => 'alternative_text',
                'title' => 'title',
                'width' => 'width',
                'height' => 'height',
                'path' => 'path',
            ],
        ],
    ],

    /**
     * DYNAMIC SHORTCODE
     */
    'dynamic_shortcode' => [
        'image' => [
            'types' => [
                [
                    'content' => false,
                    'options' => [
                        'caption' => 'string|optional',
                        'id' => 'integer|required',
                    ],
                ],
                [
                    'content' => false,
                    'options' => [
                        'url' => 'string|required',
                    ],
                ],
            ],
        ],
        'facebook' => [
            'types' => [
                [
                    'content' => false,
                    'options' => ['url' => 'string|required'],
                ],
            ],
        ],
        'faq' => [
            'types' => [
                [
                    'content' => true,
                    'options' => ['title' => 'string|required'],
                ],
            ],
        ],
        'gallery' => [
            'types' => [
                [
                    'content' => false,
                    'options' => [
                        'title' => 'string|required',
                        'images' => 'string|required',
                    ],
                ],
            ],
        ],
        'spoiler' => [
            'types' => [
                [
                    'content' => true,
                    'options' => ['title' => 'string|optional'],
                ],
            ],
        ],
        'spotify' => [
            'types' => [
                [
                    'content' => false,
                    'options' => ['url' => 'string|required'],
                ],
                [
                    'content' => false,
                    'options' => ['uri' => 'string|required'],
                ],
            ],
        ],
        'twitter' => [
            'types' => [
                [
                    'content' => false,
                    'options' => ['url' => 'string|required'],
                ],
            ],
        ],
        'youtube' => [
            'types' => [
                [
                    'content' => false,
                    'options' => ['url' => 'string|required'],
                ],
            ],
        ],
    ],
];
