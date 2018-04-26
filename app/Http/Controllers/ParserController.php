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
        $params = \App\Params::where('cron', 1)->get();
        $this->model($params);
    }

    public function test($id)
    {
        $param = \App\Params::find($id);
        if(!$param)abort(404);
        $url = $param->value;
        return $this->getHtmlAvito($url);
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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

    public function getHtmlAvito($url){
        $client = new \GuzzleHttp\Client;
        $response = $client->request('GET', $url, [
            'headers' => [
                'cookie:' => 'u=2ar1fkid.1e9ix5.fo010iuou0; _ga=GA1.2.1993347833.1521729938; _vwo_uuid_v2=D6A55C9631AD70DD717A9F58BFFBEAD67|9c3820440c08af10e70c2f465a545023; _vis_opt_exp_46_exclude=1; _ym_uid=1521729939963390101; _vwo_uuid=D6A55C9631AD70DD717A9F58BFFBEAD67; __gads=ID=5d7d892988e6f51a:T=1521729954:S=ALNI_Mbe67418RtuR838r5DCTQKuIzNGhA; dfp_group=52; _gid=GA1.2.1758872146.1524579322; _vwo_ds=3%3Aa_0%2Ct_0%3A0%241524579322%3A65.65636449%3A%3A%3A18_0%2C12_0; f=5.367a37203faa7618a7d90a8d0f8c6e0b47e1eada7172e06c47e1eada7172e06c47e1eada7172e06c47e1eada7172e06c8a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b890df103df0c26013a0df103df0c26013a2ebf3cb6fd35a0ac0df103df0c26013a8b1472fe2f9ba6b91772440e04006def90d83bac5e6e82bd59c9621b2c0fa58f897baa7410138ead3de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe2cd39050aceac4b90c13758190af548eb21bd549811de366d84ad1f92f24d4cac0a2c422d6138c2cd7741bdd019813739af42f4d72e9a5499d65518de44bb221823d9f5f1350376437e8f7ececaee20e2a5b790f0ada77498343076c04c14cfd09d9b2ff8011cc827cbf1a5019b899285ad09145d3e31a569eb0eb8b0435e644d8db57d0f7c7638d42da10fb74cac1eabd1d953d27484fd81cab2156c031c728148712f79e50bb44d; v=1524730383; _vis_opt_s=4%7C; _vis_opt_test_cookie=1; _ym_isad=2; buyer_location_id=653240; buyer_selected_search_radius0=0; _ym_uid_cmd=%%CACHEBUSTER%%; weborama-viewed=1; _vis_opt_exp_40_combi=2; isHeartsEnabled=0; sx=H4sIAAAAAAACA12U0XayQAyE38Xr%2FyLggqFvI6kEibLQBaPt6bv%2Fsz2ntPYGL1g%2FJjOT%2FdjxLXVvfJGoROqWXEWNosXdy8futnvZjQVNd%2B8nKVMIpNFdTMWZ8QdJsvu3O%2B1eiqoMh0DM9PlvV3ajpLG9PkY2Cu7qQkGCyjfylMJwvxfDcAhgquIZyfDDzCHFHySXdUElkPXe6%2BGtiHRj80DiAoVqat%2FIedR%2BPKxW3EKCLHyPQ2SzJJ4Ix36QBTe8B7KQyknbWttGKJkwQSZLpA1Z6HEq5l56t%2BgYILCxQ2QUDyx%2FkAHIagns6VaergwBeZ6UZwr%2BjTzKuI6HqVCCzZExNMHT4EnYo6XfXjbVgbPKqXvMzeOhY7SAeMyUJJhsKsVO%2B%2BoQ3zWoORKBNMydjbeUPD2pLEJGttodBz%2FzIIoDGBngSE6bl%2BX7Ze%2FO564o0YWAVwFOwXYPUZI%2FI%2BvsZe3D1KTasn%2BekrkgJlbjTWU5rvy4DnXv5ByMjANQiTE%2Bif1B1kCGVHWvHXfdgxzHDcciUggb8lgO7blZexcUkRSWwlSOiB78pM%2FIQ46nLZY63c%2F%2BWmueJLKIK6oXNiRN9UA61gsGhpuKt6ac0CJT5z%2BJVxkZrreemkuU%2FH2NmmNAnpvK6xpP7XlsoCviHbpJFpEijBRFis%2FIHM%2Fp3s71NN%2BmNWIf1cTgmKAg38h%2Bniul8jiVMaW8t1gwbCU2MsKp8IxsCiCP9aVLr%2FfmIYzMo3GMhKL%2BeNnO55lKrjwjAuGNqmKDkKCF8BRPQ5QHxwrP79Oc7hPGQYqee%2BdJdRv8wPeqn9%2F2kB8lJGVD19wRPAv%2Fjqck%2Ftrxtqm7y3Ly9Wa5winAMcOSbNdGocsd7kzV%2BSIiRDFfU%2BAa6JF%2BV73E82vHcZkt62nSFQVXS3jm68s3ZDhUiy79kqY1RexgjAE2wh%2F98v4Zuc%2B9rNa5XheNQ4u4sd0U4RfS3O5Lu8Vj%2F7i%2FVY1LCnA0ISaFSRBA%2Bjuesqz3DZBdGdbH29mmziN4aFtMqJtsvZxxCc17e7%2BikHkPDC5iIUk5XxvhWWVdfn7%2BB7xIcGbXBQAA; nps_sleep=1; _gat_UA-2546784-1=1; anid=1119273177%3B0af8c54a85fa6f91bebb98601a50315f%3B1; sessid=a5a3b186c278dc7fc322d17e11604274.1524739707; auth=1; abp=0; _nfh=baa7aa016fcf30b9f00e609bf99adf3c',
            ]
        ]);
        if($response->getStatusCode() != 200){
            syslog('LOG_ERR', 'avito error not 200');
            return false;
        }
        return $response->getBody()->getContents();
    }

    /**
     * @param $url
     *
     * @return array
     */
    public function getPaginationLink($url)
    {
        $html = $this->getHtmlAvito($url);
        $dom = HtmlDomParser::str_get_html($html);
        $_url = $dom->find('a.pagination-page');
        if(!count($_url))return [$url];
        $_url = end($_url);
        preg_match('/p\=([0-9]*)/is', $_url->href, $c);
        $arr = [];
        if(isset($c[1])){
            for($i = 1; $i<=$c[1]; $i++){
                $arr[] = preg_replace('/p\=([0-9]*)/is', 'p='.$i, $_url->href);
            }
        }
        else{
            $arr[] = $_url->href;
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
            if($item->el)$item->sendMail($item);
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
                $html = $this->getHtmlAvito($url);
                $dom = HtmlDomParser::str_get_html($html);
                if(count($dom->find('div.item'))){
                    foreach ($dom->find('div.item') as $item)
                        $arr[$item->id] = $item->find('a')[0]->href;
                }
            }
        }
        else{
            if(!stristr($url, 'http'))$url = $this->domain.trim($url, '/');
            $html = $this->getHtmlAvito($url);
            $dom = HtmlDomParser::str_get_html($html);
            $arr = [];
            if(count($dom->find('div.item'))){
                foreach ($dom->find('div.item') as $item)
                    $arr[$item->id] = $item->find('a')[0]->href;
            }
        }
        return $arr;
    }
}
