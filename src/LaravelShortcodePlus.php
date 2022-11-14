<?php

namespace Murdercode\LaravelShortcodePlus;

use Murdercode\LaravelShortcodePlus\Enums\SupportedParser;
use Murdercode\LaravelShortcodePlus\Parsers\Parser;

final class LaravelShortcodePlus
{
    public static function css(): string
    {
        return '<link rel="stylesheet" href="'.route('shortcode-plus.css').'">';
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
