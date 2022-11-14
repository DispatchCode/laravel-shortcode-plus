<?php

namespace Murdercode\LaravelShortcodePlus\Parsers;

use Murdercode\LaravelShortcodePlus\Enums\SupportedParser;

class Parser
{
    private $dynamic_shortcode_conf;

    private $shortcodes;

    public function __construct()
    {
        $this->dynamic_shortcode_conf = config('shortcode-plus.dynamic_shortcode');
        $this->shortcodes = array_keys($this->dynamic_shortcode_conf);
    }

    private function searchMatchedConfig(array|null $params, array $config)
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

    private function parseArguments(string $args)
    {
        $pattern = '/(\w+)=(["\'])(.*?)\2/';
        preg_match_all($pattern, $args, $matches);

        $params_map = [];
        foreach ($matches[1] as $key => $param_name) {
            $params_map[$param_name] = $matches[3][$key];
        }

        return $params_map;
    }

    private function castArguments(array &$params, array $config)
    {
        foreach ($config as $key => $type) {
            if (str_contains($type, 'integer')) {
                $params[$key] = (int) $params[$key];
            } elseif (str_contains($type, 'boolean')) {
                $params[$key] = (bool) $params[$key];
            }
        }
    }

    public function replaceWithContent(string $shortcode, array $params, string $content = null)
    {
        switch ($shortcode) {
            case 'image':
                return Image::parse($params); // TODO image parser must be updated
            case 'spoiler':
                return Spoiler::parse($params, $content);
            case 'faq':
                return Faq::parse($params, $content);
            case 'facebook':
                return Facebook::parse($params);
            case 'twitter':
                return Twitter::parse($params);
            case 'youtube':
                return Youtube::parse($params);
            case 'spotify':
                return Spotify::parse($params);
            case 'gallery':
                return Gallery::parse($params, $content);

            default:
                // TODO  throw exception, invalid shortcode
        }
    }

    private function countShortcode(string $text)
    {
        $pattern = '/\[('.implode('|', $this->shortcodes).')(.*?)\]/s';
        preg_match_all($pattern, $text, $matches);

        return count($matches[0] ?? []);
    }

    private function parseText(string $text, string $searched_shortcode = '')
    {
        $n_shortcode = $this->countShortcode($text);

        while ($n_shortcode--) {
            $shortcode = $searched_shortcode;
            if ($searched_shortcode == '') {
                preg_match('/\[([^\s\]]+)/', $text, $matches);
                $shortcode = $matches[1] ?? null;
            }

            // If the word is a shortcode
            if (in_array($shortcode, $this->shortcodes)) {
                $config = $this->dynamic_shortcode_conf[$shortcode];

                preg_match('/\[('.$shortcode.')\s?([^\]]*)\]/', $text, $matches);

                $params = $this->parseArguments($matches[2] ?? '');

                $matched_config = $this->searchMatchedConfig($params, $config);
                if (empty($matched_config)) {
                    continue;
                }

                $this->castArguments($params, $matched_config['options']);

                if ($matched_config['content']) {
                    preg_match('/\[('.$shortcode.')\s?([^\]]*)\](.*?)\[\/\1\]/s', $text, $matches);

                    $content = $matches[3] ?? '';

                    $text = str_replace(
                        $matches[0] ?? '',
                        $this->replaceWithContent($shortcode, $params, $content),
                        $text
                    );
                } else {
                    $text = str_replace($matches[0], $this->replaceWithContent($shortcode, $params), $text);
                }
            }
        }

        return $text;
    }

    public static function parse(string $text, SupportedParser $supportedParser = SupportedParser::ALL)
    {
        $parser = new self();
        $type = $supportedParser->value;

        return $parser->parseText($text, $type);
    }
}
