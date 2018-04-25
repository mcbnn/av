<?php

namespace App\Http\Controllers;

use App\Contents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Sunra\PhpSimple\HtmlDomParser;

class ParserController extends Controller
{
    public $domain = "https://www.avito.ru/";

    /**
     * init
     */
    public function init()
    {
        $params = \App\Params::all();
        $this->model($params);
    }

    /**
     * @param $id
     */
    public function parserUrl($id)
    {
        $param = \App\Params::find($id);
        if(!$param)abort(404);
        $urls = $this->getPaginationLink($param->value);
        $parsers = $this->parser($urls);
        $param->saveContents($parsers);
        return redirect('params');
    }

    /**
     * @param $url
     *
     * @return array
     */
    public function getPaginationLink($url)
    {
        $dom = HtmlDomParser::str_get_html(file_get_contents($url));
        $url = $dom->find('a.pagination-page');
        $url = end($url);
        preg_match('/p\=([0-9]*)/is', $url->href, $c);
        $arr = [];
        if(isset($c[1])){
            for($i = 1; $i<=$c[1]; $i++){
                $arr[] = preg_replace('/p\=([0-9]*)/is', 'p='.$i, $url->href);
            }
        }
        else{
            $arr[] = $url->href;
        }
        return $arr;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $params
     */
    public function model(\Illuminate\Database\Eloquent\Collection $params)
    {
        /** @var \App\Params $item */
        foreach ($params as $item){
            $parsers = $this->parser($item->value);
            $item->saveContents($parsers);
        }
    }

    /**
     * @param null $url
     *
     * @return array
     */
    public function parser($url = null)
    {
        if(is_array($url)){
            $arr = [];
            foreach($url as $url){
                if(!stristr($url, 'http'))$url = $this->domain.trim($url, '/');
                $dom = HtmlDomParser::str_get_html(file_get_contents($url));
                if(count($dom->find('div.item'))){
                    foreach ($dom->find('div.item') as $item)
                        $arr[$item->id] = $item->find('a')[0]->href;
                }
            }
        }
        else{
            if(!stristr($url, 'http'))$url = $this->domain.trim($url, '/');
            $dom = HtmlDomParser::str_get_html(file_get_contents($url));
            $arr = [];
            if(count($dom->find('div.item'))){
                foreach ($dom->find('div.item') as $item)
                    $arr[$item->id] = $item->find('a')[0]->href;
            }
        }
        return $arr;
    }
}
