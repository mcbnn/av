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
        $url = $param->url;
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
                'cookie:' => 'u=2ar1fkid.1e9ix5.fo010iuou0; _ga=GA1.2.1993347833.1521729938; _vwo_uuid_v2=D6A55C9631AD70DD717A9F58BFFBEAD67|9c3820440c08af10e70c2f465a545023; _vis_opt_exp_46_exclude=1; _ym_uid=1521729939963390101; _vwo_uuid=D6A55C9631AD70DD717A9F58BFFBEAD67; __gads=ID=5d7d892988e6f51a:T=1521729954:S=ALNI_Mbe67418RtuR838r5DCTQKuIzNGhA; dfp_group=52; _gid=GA1.2.1758872146.1524579322; _vwo_ds=3%3Aa_0%2Ct_0%3A0%241524579322%3A65.65636449%3A%3A%3A18_0%2C12_0; f=5.367a37203faa7618a7d90a8d0f8c6e0b47e1eada7172e06c47e1eada7172e06c47e1eada7172e06c47e1eada7172e06c8a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b890df103df0c26013a0df103df0c26013a2ebf3cb6fd35a0ac0df103df0c26013a8b1472fe2f9ba6b91772440e04006def90d83bac5e6e82bd59c9621b2c0fa58f897baa7410138ead3de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe2cd39050aceac4b90c13758190af548eb21bd549811de366d84ad1f92f24d4cac0a2c422d6138c2cd7741bdd019813739af42f4d72e9a5499d65518de44bb221823d9f5f1350376437e8f7ececaee20e2a5b790f0ada77498343076c04c14cfd09d9b2ff8011cc827cbf1a5019b899285ad09145d3e31a569eb0eb8b0435e644d8db57d0f7c7638d42da10fb74cac1eabd1d953d27484fd81cab2156c031c728148712f79e50bb44d; v=1524730383; _vis_opt_s=4%7C; _vis_opt_test_cookie=1; _ym_isad=2; buyer_location_id=653240; buyer_selected_search_radius0=0; _ym_uid_cmd=%%CACHEBUSTER%%; weborama-viewed=1; _vis_opt_exp_40_combi=2; nps_sleep=1; sx=H4sIAAAAAAACA12UQXbiQAxE78J6FrKRjTy3AQULELjttI0g83L3qZ73xgnZeNPt31JVSX82cs%2F9u1w1GZGF5zA1p%2BRp8%2FvP5r75vRkqGh9xGrXOzGQpQt00RPCDZt382hw3v6um5h2TCH3%2B2tT9oHk43J6DOHGEhRIrm%2F5H7m%2Fzvn%2FspzFSktDMeDWJsjoZO38hpeatVEC222gv71Wiu3gwaSgqNDf%2Fj5wGOw27xas7Z5SF94STuGeNTLj2haykky2QlTZBdmjt0CllVyGUKZpoRVa2H6vppKfwFGiAxSU4S9Jg0R9IBrKZWSLf6%2BNNUIDhKloT4Vgb12EZdmNlBJmTJII%2BDImySiTP37Xsmp2UKsf%2BOXXPpw3JGfa4Gym7rlWqH7fNLn0YmwccQWnouwjvOUd%2BqbLigjxYv7%2FEWS5quICWAU4UtGpZf1y3EXLuqxpZYBwxlILswUlzvCLbomUbl7HLrRf9ImcPhU1iLmuV9bDI83ZpT0Eh7OTCQGVB%2B6T%2BA9kCybnp33rp%2BycFrjuuJbjAK3JfXw7nbjmFIohkkBSiSoL14Gd7Re6KPYdqbvPjHG%2BtlU6QOA3LnHhF0theyIZ2RsNQ03DqJhkpcgv54XhTkHy7n6i7Ji3vW7JiA%2Fxcq7wt6Xg4Dx3qSjhDNskTXISQanDxFVnsOT4OUztO93FJmEdzDEWgUllDdJqmxqjej3XKucwtBgxTiYlMUIpfkV2Znn177fPbo3uqwPPkkhIhqF9aHqbzRLU0URBMODEzTBAcdOYXezqi0vgx8%2FQxTvkxoh24GCV3kc3WxnfyaE7T%2BxblJ%2BVs4shaBIwXle%2F21CRUFy27tr%2FOx1juXiKcGYo5hmRdG5XND6gzNuerqhKlsqbAddATfY96je%2B%2FGccym5fjaAsCbp7xLesrViTvmtnm05zHJWMVpZQYMkIf%2B6f9K3JbctksU7vMli4H2I3ppgS94Oa6L%2F2e9qfn473pymaDohk2GURCAVhv35F1u%2B2A7Gtenu9nH%2FtI4CFtKSNuuuZywhKatv5xQyDLHDhUxECSSVkb%2FFplW39%2B%2FgV3lsqA1wUAAA%3D%3D; anid=1119261037%3B268b36369f6d554a29c8a9ee6c4c5f98%3B1; auth=1; sessid=d743250c5f320bf76f2fd31b2208d6ce.1524738100; isHeartsEnabled=0; abp=0; _gat_UA-2546784-1=1; _nfh=13018ded075376402c899b404c748e66',
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
