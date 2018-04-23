<?php

namespace App\Http\Controllers;

use App\Contents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Sunra\PhpSimple\HtmlDomParser;

class ParserController extends Controller
{

    public function parser($url = null)
    {
        $url = 'https://www.avito.ru/sankt-peterburg/uslugi';
        $dom = HtmlDomParser::str_get_html( file_get_contents($url));
        $arr = [];
        $content = new \App\Contents();

        if(count($dom->find('div.item'))){
            foreach ($dom->find('div.item') as $item)
            $content->create([
                    'url' => $item->find('a')[0]->href,
                    'key' => $item->id,
                    'param_id' => 1
                ]);
            }
    }






}
