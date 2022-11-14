<?php

namespace Murdercode\LaravelShortcodePlus\Enums;

enum SupportedParser: string
{
    case ALL = '';
    case IMAGE = 'image';
    case FAQ = 'faq';
    case SPOILER = 'spoiler';
    case FACEBOOK = 'facebook';
    case TWITTER = 'twitter';
    case YOUTUBE = 'youtube';
    case SPOTIFY = 'spotify';
    case GALLERY = 'gallery';


    public function getSupportedParser(): array
    {
        return self::cases();
    }
}
