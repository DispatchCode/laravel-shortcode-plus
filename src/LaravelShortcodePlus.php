<?php

namespace Murdercode\LaravelShortcodePlus;

use Murdercode\LaravelShortcodePlus\Enums\SupportedParser;
use Murdercode\LaravelShortcodePlus\Parsers\Facebook;
use Murdercode\LaravelShortcodePlus\Parsers\Faq;
use Murdercode\LaravelShortcodePlus\Parsers\Gallery;
use Murdercode\LaravelShortcodePlus\Parsers\Image;
use Murdercode\LaravelShortcodePlus\Parsers\Parser;
use Murdercode\LaravelShortcodePlus\Parsers\Spoiler;
use Murdercode\LaravelShortcodePlus\Parsers\Spotify;
use Murdercode\LaravelShortcodePlus\Parsers\Twitter;
use Murdercode\LaravelShortcodePlus\Parsers\Youtube;

final class LaravelShortcodePlus
{
    public static function css(): string
    {
        return '<link rel="stylesheet" href="' . route('shortcode-plus.css') . '">';
    }

    public static function source(string $source): static
    {
        return new static($source);
    }

    public function __construct(protected string $content = '')
    {
    }

    public function parseAll(): string
    {
        $this->content = Parser::parse($this->content, SupportedParser::ALL);
        dd($this->content);
        $this->content = $this->parseGalleryTag();

        return $this->content;
    }

    public function parseTwitterTag(): string
    {
        return Parser::parse($this->content, SupportedParser::TWITTER);
    }

    public function parseYoutubeTag(): string
    {
        return Parser::parse($this->content, SupportedParser::YOUTUBE);
    }

    public function parseSpotifyTag(): string
    {
        return Parser::parse($this->content, SupportedParser::SPOTIFY);
    }

    public function parseFaqTag(): string
    {
        return Parser::parse($this->content, SupportedParser::FAQ);
    }

    public function parseSpoilerTag(): string
    {
        return Parser::parse($this->content, SupportedParser::SPOILER);
    }

    public function parseFacebookTag(): string
    {
        return Parser::parse($this->content, SupportedParser::FACEBOOK);
    }

    public function parseImageTag(): string
    {
        return Parser::parse($this->content, SupportedParser::IMAGE);
    }

    public function parseGalleryTag(): string
    {
        return Parser::parse($this->content, SupportedParser::GALLERY);
    }
}
