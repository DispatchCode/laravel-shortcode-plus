<?php

use Murdercode\LaravelShortcodePlus\Parsers\Parser;

it('can load the config', function ()
{
    $parser = new Parser();

    $text = "Questo è un testo di prova contenente uno shortcode [faq title='TITLE']CONTENT[/faq] per vedere se funziona qualcosa, proviamo un'immagine [image id='2']. Proviamo anche con [spoiler title='TITLE'][image id='4' caption='image title'][/spoiler] e vediamo i risultati";

    $text = "Esempio di tag [image id='2' caption='test titolo'] immagine [spoiler title='TITLE TEST'][image id='2' caption='test titolo'][/spoiler] per vedere se parsa correttamente.";

    dd($parser->parseText($text));

    expect(true)->toBeTrue();
});
