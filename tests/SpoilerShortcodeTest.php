<?php

use Murdercode\LaravelShortcodePlus\LaravelShortcodePlus;

it('can parse spoiler shortcode', function ()
{
    $html = '[spoiler]This is a spoiler![/spoiler]';
    $spoilerOembed = LaravelShortcodePlus::source($html)->parseSpoilerTag();
    expect($spoilerOembed)->toContain('This is a spoiler!');
});


it('can parse a spoiler with an image', function ()
{
    $html = "Text before spoiler." . PHP_EOL . "[spoiler]Test content, with an image [image url='https://upload.wikimedia.org/wikipedia/commons/9/9f/BengalCat_Stella.jpg'][/spoiler]";

    $parsedContent = LaravelShortcodePlus::source($html)->parseSpoilerTag();
    expect($parsedContent)->toContain('<div class="shortcode_spoiler">');
});

it('can parse two nested spoilers', function ()
{
    $html = "[spoiler]First spoiler [spoiler title='CUSTOM TITLE']Second spoiler[/spoiler][/spoiler]";
    $parsedContent = LaravelShortcodePlus::source($html)->parseSpoilerTag();

    $parsedContent = preg_replace('/\s+/', '', $parsedContent);

    expect($parsedContent)->toContain('<summary>Spoileralert!Clicktoreveal</summary>')
        ->and($parsedContent)->toContain('<summary>CUSTOMTITLE</summary>');
});
