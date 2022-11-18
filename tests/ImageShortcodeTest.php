<?php

use Murdercode\LaravelShortcodePlus\LaravelShortcodePlus;
use Murdercode\LaravelShortcodePlus\Models\ShortcodeImage;

it('can parse an image shortcode', function () {
    $image = ShortcodeImage::factory()->create();
    $id = $image->id;
    $path = $image->path;

    $this->assertModelExists($image);

    $html = '[image id="'.$id.'"]';
    $imageOembed = LaravelShortcodePlus::source($html)->parseImageTag();

    expect($imageOembed)->toContain('src="'.asset('storage/'.$path).'"');
});

it('parse an image shortcode 2', function () {
    $image = ShortcodeImage::factory()->create();
    $id = $image->id;
    $path = $image->path;

    $text = "[image caption='TEST CAPTION' id='".$id."']";
    $parsedContent = LaravelShortcodePlus::source($text)->parseAll();

    expect($parsedContent)->toContain('<img class="mx-auto" src="'.asset('storage/'.$path).'"');
});

it('can parse an image shortcode with custom caption', function () {
    $image = ShortcodeImage::factory()->create();
    $id = $image->id;
    $path = $image->path;

    $this->assertModelExists($image);

    $html = '[image id="'.$id.'" caption="This is a custom caption"]';
    $imageOembed = LaravelShortcodePlus::source($html)->parseImageTag();
    expect($imageOembed)->toContain('src="'.asset('storage/'.$path).'"')
        ->and($imageOembed)->toContain('This is a custom caption');
});

it('can parse an image shortcode when an image id is not found', function () {
    $id = 99999;

    $html = '[image id="'.$id.'"]';
    $imageOembed = LaravelShortcodePlus::source($html)->parseImageTag();
    expect($imageOembed)->toContain('');
});

it('can parse an image shortcode inside a spoiler shortcode', function () {
    $image = ShortcodeImage::factory()->create();
    $id = $image->id;
    $path = $image->path;

    $this->assertModelExists($image);

    $html = '[spoiler][image id="'.$id.'" caption="This is a custom caption"][/spoiler]';
    $parsedContent = LaravelShortcodePlus::source($html)->parseAll();

    expect($parsedContent)->toContain('<div class="relative block">')
        ->and($parsedContent)->toContain('<img class="mx-auto" src="'.asset('storage/'.$path).'"');
});

it('can parse an image shortcode with an external url', function () {
    $html = "[image url='https://upload.wikimedia.org/wikipedia/commons/9/9f/BengalCat_Stella.jpg']";

    $parsedContent = LaravelShortcodePlus::source($html)->parseImageTag();

    expect($parsedContent)->toContain('<img class="mx-auto" src="https://upload.wikimedia.org/wikipedia/commons/9/9f/BengalCat_Stella.jpg"');
});
