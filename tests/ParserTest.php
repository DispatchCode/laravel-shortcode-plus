<?php

use Murdercode\LaravelShortcodePlus\LaravelShortcodePlus;
use Murdercode\LaravelShortcodePlus\Models\ShortcodeImage;

it('can parse all shortcodes', function () {
    $images = ShortcodeImage::factory(2)->create();

    $paths = $images->pluck('path')->toArray();
    $alternative_texts = $images->pluck('alternative_text')->toArray();

    $text = 'Shortcodes tests.'.PHP_EOL.
        "[spoiler title='SPOILER1 TITLE']Spoiler content[/spoiler]".PHP_EOL.
        "[spoiler][image url='https://upload.wikimedia.org/wikipedia/commons/9/9f/BengalCat_Stella.jpg'][/spoiler]".PHP_EOL.
        "[faq title='Faq1 title!']FAQ Content[/faq]".PHP_EOL.
        "[spotify url='https://open.spotify.com/album/1DFixLWuPkv3KT3TnV35m3']".PHP_EOL.
        '[gallery title="This is a custom title" images="'.$images->pluck('id')->implode(',').'"]'.PHP_EOL.
        '[twitter url="https://twitter.com/elonmusk/status/1585841080431321088"]'.PHP_EOL.
        '[youtube url="https://www.youtube.com/watch?v=9bZkp7q19f0"]'.PHP_EOL.
        '[facebook url="https://www.facebook.com/elonmusk/posts/10157710103910177"]';

    $parsedContent = LaravelShortcodePlus::source($text)->parseAll();
    expect($parsedContent)->toContain(
        'data-href="https://www.facebook.com/elonmusk/posts/10157710103910177"'
    )->and($parsedContent)->toContain(
        '<div class="shortcode_spoiler">'
    )->and($parsedContent)->toContain(
        '<img class="mx-auto" src="https://upload.wikimedia.org/wikipedia/commons/9/9f/BengalCat_Stella.jpg"'
    )->and($parsedContent)->toContain(
        'FAQ Content'
    )->and($parsedContent)->toContain(
        '>the bird is freed'
    )->and($parsedContent)->toContain(
        'src="https://www.youtube-nocookie.com/embed/9bZkp7q19f0&autoplay=1"'
    )->and($parsedContent)->toContain(
        'src="'.asset('storage/'.$paths[0]).'"'
    )->and($parsedContent)->toContain(
        'src="'.asset('storage/'.$paths[1]).'"'
    )->and($parsedContent)->toContain(
        'alt="'.$alternative_texts[0].'"'
    )->and($parsedContent)->toContain(
        'alt="'.$alternative_texts[1].'"'
    )->and($parsedContent)->toContain(
        'src="https://open.spotify.com/embed/album/1DFixLWuPkv3KT3TnV35m3"'
    );
});

it(
    'left a "fake shortcode" inside the text',
    function () {
        $text = '[fake shortcode]Text[/fake]'.PHP_EOL.
            '[fake]shortcode content[/fake]'.PHP_EOL.
            "[fake title='I\'m fake']";

        $parsedContent = LaravelShortcodePlus::source($text)->parseAll();
        expect($parsedContent)->toContain($text);
    }
);

it('parse a shortcode with an invalid parameter', function () {
    $text = "[spoiler caption='invalid attribute']CONTENT[/spoiler]";

    $parsedContent = LaravelShortcodePlus::source($text)->parseAll();
    expect($parsedContent)->toContain('<div class="shortcode_spoiler">');
});

it('does not parse a shortcode without an unmatched config', function () {
    $text = "[image caption='TEST']";
    $parsedContent = LaravelShortcodePlus::source($text)->parseAll();

    expect($parsedContent)->toContain($text);
});
