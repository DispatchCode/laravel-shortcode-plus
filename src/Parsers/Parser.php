<?php

namespace Murdercode\LaravelShortcodePlus\Parsers;

use Murdercode\LaravelShortcodePlus\Enums\SupportedParser;

class Parser
{
    private $dynamic_shortcode_conf;

    private $shortcodes;

    private $invalid_shortcode_message;

    public function __construct()
    {
        $this->dynamic_shortcode_conf = config('shortcode-plus.dynamic_shortcode');
        $this->shortcodes = array_keys($this->dynamic_shortcode_conf);
        $this->invalid_shortcode_message = config('shortcode-plus.invalid_shortcode_error_message');
    }

    public static function parse(string $text, SupportedParser $supportedParser = SupportedParser::ALL): string
    {
        $parser = new self();
        $type = $supportedParser->value;

        return $parser->parseText($text, $type);
    }

    private function parseText(string $text, string $searched_shortcode = ''): string
    {
        if ($searched_shortcode !== '') {
            $this->shortcodes = [$searched_shortcode];
        }

        foreach ($this->shortcodes as $shortcode) {
            $n_shortcode = $this->countShortcode($text, $shortcode);

            for ($i = 0; $i < $n_shortcode; $i++) {
                $config = $this->dynamic_shortcode_conf[$shortcode];
                $params = $this->getShortcodeParameters($text, $shortcode);

                $matched_config = $this->searchMatchedConfig($params, $config);
                if (empty($matched_config)) {
                    $text = $this->replaceShortcodeWithError($text, $shortcode);

                    continue;
                }

                $this->castArguments($params, $matched_config['options']);
                $text = $this->parseTag($text, $shortcode, $params, $matched_config);
            }
        }

        return $text;
    }

    private function countShortcode(string $text, string $shortcode): int
    {
        return substr_count($text, '['.$shortcode);
    }

    private function getShortcodeParameters(string $text, string $shortcode): array
    {
        preg_match('/\[('.$shortcode.')\s?([^\]]*)\]/', $text, $matches);

        return $this->parseArguments($matches[2] ?? '');
    }

    private function searchMatchedConfig(array|null $params, array $config): array
    {
        $keys = $params ? array_keys($params) : [];

        $matched_config = [];
        foreach ($config['types'] as $key => $type) {
            $matched = true;
            foreach ($type['options'] as $option => $type) {
                if (! in_array($option, $keys) && str_contains($type, 'required')) {
                    $matched = false;
                    break;
                }
            }

            if ($matched) {
                $matched_config = $config['types'][$key];
                break;
            }
        }

        return $matched_config;
    }

    private function castArguments(array &$params, array $config): void
    {
        foreach ($config as $key => $type) {
            if (str_contains($type, 'integer')) {
                $params[$key] = (int) $params[$key];
            } elseif (str_contains($type, 'boolean')) {
                $params[$key] = (bool) $params[$key];
            }
        }
    }

    private function parseTag(string $text, string $shortcode, array $params, array $matched_config): string
    {
        $search_pattern = $this->getPatternForShortcodeDetection($shortcode, $matched_config);

        return $this->parseTagContent($text, $shortcode, $params, $search_pattern);
    }

    private function parseArguments(string $args): array
    {
        $pattern = '/(\w+)=(["\'])(.*?)\2/';
        preg_match_all($pattern, $args, $matches);

        $params_map = [];
        foreach ($matches[1] as $key => $param_name) {
            $params_map[$param_name] = $matches[3][$key];
        }

        return $params_map;
    }

    public function replaceWithContent(string $shortcode, array $params, string $content = ''): string
    {
        return match ($shortcode) {
            'image' => Image::parse($params),
            'spoiler' => Spoiler::parse($params, $content),
            'faq' => Faq::parse($params, $content),
            'facebook' => Facebook::parse($params),
            'twitter' => Twitter::parse($params),
            'youtube' => Youtube::parse($params),
            'spotify' => Spotify::parse($params),
            'gallery' => Gallery::parse($params, $content),
        };
    }

    private function parseTagContent(string $text, string $shortcode, array $params, string $search_pattern): string
    {
        preg_match($search_pattern, $text, $matches);

        $content = $matches[3] ?? '';

        return str_replace(
            $matches[0] ?? '',
            $this->replaceWithContent($shortcode, $params, $content),
            $text
        );
    }

    private function replaceShortcodeWithError(string $text, string $shortcode): string
    {
        $search_pattern = $this->getPatternForShortcodeDetection($shortcode);

        $this->invalid_shortcode_message = str_replace('{shortcode}', $shortcode, $this->invalid_shortcode_message);

        return preg_replace($search_pattern, $this->invalid_shortcode_message, $text, 1);
    }

    private function getPatternForShortcodeDetection(string $shortcode, array $config = []): string
    {
        if (empty($config)) {
            $content = $this->dynamic_shortcode_conf[$shortcode]['type']['content'] ?? false;
        } else {
            $content = $config['content'];
        }

        return $content ? '/\[('.$shortcode.')\s?([^\]]*)\](.*?)\[\/\1\]/s' : '/\[('.$shortcode.')\s?([^\]]*)\]/';
    }
}
