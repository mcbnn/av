<?php

namespace App\Http\Controllers;

use App\Http\Traits\MailTrait;
use App\Contents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Sunra\PhpSimple\HtmlDomParser;


class ParserController extends Controller
{
    use MailTrait;

    public $limit_see = 40;

    /**
     * init
     */
    public function init()
    {
        /**
         * Парсер авито
         */
        $this->model(\App\Params::where('cron', 1)->where('type', 1)->get());
    }

    public function initTest()
    {

        /**
         * Парсер авито
         */
        $this->modelTest(\App\Params::where('id', 4)->get());
    }


    public function getContentType(\Illuminate\Database\Eloquent\Collection $params = null)
    {
        if(!$params)return null;
        /** @var \App\Params $item */
        foreach ($params as $item){

            if($parsers = $this->parserPol($item->value)){
                $item->saveContentsPol($parsers);
                if($item->mail == 1){
                    if(count(MailTrait::$el))$item->sendMailPol($item);
                }
            }
        }
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

    public function getHtmlPol($url){

        $client = new \GuzzleHttp\Client;
        sleep(5);
        $response = $client->request('GET', $url, [
            'headers' => [
                'Cookie:' => 'JSESSIONID=a242e2f9f5f32f400dda7f809fa4',
                'Upgrade-Insecure-Requests'=> '1'
            ]
        ]);
        if($response->getStatusCode() != 200){
            syslog(LOG_ERR, 'avito error not 200');
            return false;
        }
        return $response->getBody()->getContents();
    }

    public function getHtmlAvito($url){
        if (!defined('MAX_FILE_SIZE'))
        {
            define('MAX_FILE_SIZE', 60000000);
        }
        if(!stristr($url, 'http'))$url = config('app.url_avito').trim($url, '/');
        $client = new \GuzzleHttp\Client;
        sleep(5);
        try{
	        $response = $client->request('GET', $url, [
		        'headers' => [
			        'cookie:' => 'u=2ar1fkid.1e9ix5.fo010iuou0; _ga=GA1.2.1993347833.1521729938; _vwo_uuid_v2=D6A55C9631AD70DD717A9F58BFFBEAD67|9c3820440c08af10e70c2f465a545023; _vis_opt_exp_46_exclude=1; _ym_uid=1521729939963390101; _vwo_uuid=D6A55C9631AD70DD717A9F58BFFBEAD67; __gads=ID=5d7d892988e6f51a:T=1521729954:S=ALNI_Mbe67418RtuR838r5DCTQKuIzNGhA; dfp_group=52; _gid=GA1.2.1758872146.1524579322; _vwo_ds=3%3Aa_0%2Ct_0%3A0%241524579322%3A65.65636449%3A%3A%3A18_0%2C12_0; f=5.367a37203faa7618a7d90a8d0f8c6e0b47e1eada7172e06c47e1eada7172e06c47e1eada7172e06c47e1eada7172e06c8a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b898a38e2c5b3e08b890df103df0c26013a0df103df0c26013a2ebf3cb6fd35a0ac0df103df0c26013a8b1472fe2f9ba6b91772440e04006def90d83bac5e6e82bd59c9621b2c0fa58f897baa7410138ead3de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe23de19da9ed218fe2cd39050aceac4b90c13758190af548eb21bd549811de366d84ad1f92f24d4cac0a2c422d6138c2cd7741bdd019813739af42f4d72e9a5499d65518de44bb221823d9f5f1350376437e8f7ececaee20e2a5b790f0ada77498343076c04c14cfd09d9b2ff8011cc827cbf1a5019b899285ad09145d3e31a569eb0eb8b0435e644d8db57d0f7c7638d42da10fb74cac1eabd1d953d27484fd81cab2156c031c728148712f79e50bb44d; v=1524730383; _vis_opt_s=4%7C; _vis_opt_test_cookie=1; _ym_isad=2; buyer_location_id=653240; buyer_selected_search_radius0=0; _ym_uid_cmd=%%CACHEBUSTER%%; weborama-viewed=1; _vis_opt_exp_40_combi=2; nps_sleep=1; anid=1119273177%3B0af8c54a85fa6f91bebb98601a50315f%3B1; sessid=a5a3b186c278dc7fc322d17e11604274.1524739707; auth=1; _gat_UA-2546784-1=1; sx=H4sIAAAAAAACA12UQXKrQAxE7%2BL1XwxYYJHbGMUII5uBDFh2Urn770lVSJwNWUCeW90tfez4lro3vkjUENQtuYpaiBZ3Lx%2B72%2B5lNxZhuns%2FSZmIgkZ3MRVnxj9Ikt2%2F3Wn3UlQlHSgwh89%2Fu7IbJY3t9TGyBXJXl0BCKt%2FIU6Lhfi%2BG4UBgquIZg%2BEPM1OKP0gu6yKUQNZ7r4e3IoYbm1MQFyhUU%2FtGzqP242G14kYJsvB7TJHNkngK%2BOwHWXDDeyALqTxoW2vbSEgmHCCTJYYNWehxKuZeerfoGIDY2CEyihPLHyQBWS3Enm7l6coQkOdJeSbyb%2BRRxnU8TIUG2BwZQwd4Sp6EPVr67WVTHTirnLrH3DweOkYjxGOmQchkUyl22leH%2BK6k5kgE0jB3Nt5S8vSksqCMbLU7Dn7mQRQfYGSAY%2FCweVm%2BX%2FbufO6KEl0gvCI4BdudoiR%2FRtbZy9qHqUm1Zf88JXNBTKzGm8pyXPlxHeregzNZMCagEmP8IPYHWQNJqepeO%2B66R3B8bvgsIgXakMdyaM%2FN2rugiEFhKUzliOjBT%2FqMPOR42mKp0%2F3sr7XmSSKLuKJ6tCHDVA9Bx3rBwHBT8daUE1pk6vwn8Soj6XrrQ3OJkn9fo%2BYYkOem8rrGU3seG%2BiKeIduBotIEUaKIsVnZI7ndG%2Fneppv0xqxj2picExQkG9kP8%2BVhvI4lTGlvLdYMGwlNjLCKXpGNgWQx%2FrSpdd78xBG5tE4xoCi%2FnjZzuc5lFx5RlDAG1XFBiFBI3qKpwkhD44Vnt%2BnOd0njIMUPffOk%2Bo2%2BIHvVT%2B%2F7SE%2FCiVlQ9fcETwL%2F46nDPy1421Td5fl5OvNcoUTwTHDkmxno9DlDnem6nwRkRBiPlPgGugx%2FK56iefXjuOYLetp0hUFV0t45vPlG5IO1aJLv6RpTRE7GCPBRvijX94%2FI%2Fe5l9U61%2BuicWgRN7Y7RPiFNLd7abd47B%2F3t6pxSQRHE2JSmAQBQX%2FHU5b1vgGyK2l9vJ1t6jyCh7bFhLrJ1ssZR2je2%2FsVhcx7YHARCxmU89mgZ5V19vJwpGK42VJjZvSXoAABOW9ng6oi%2BbpW%2FbvCu2yJYRDPp4X%2BIOuiaT4%2F%2FwN%2BtNMSKgYAAA%3D%3D; isHeartsEnabled=0; abp=0; _nfh=6bd6b1aa3e2b3617a199089b2cffc962',
		        ]
	        ]);
        }
        catch (\Exception $e)
        {
	        syslog(LOG_ERR, 'avito error request '.$e->getMessage());
	        return null;
        }
        if($response->getStatusCode() != 200){
            syslog(LOG_ERR, 'avito error not 200');
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
        if (!$html) {
            return;
        }
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
            MailTrait::$el = [];
            $parsers = $this->parser($item->value);
            $item->saveContents($parsers);
            if($item->mail == 1){
                $this->checkUrlLimitCount($item->words);
                if(count(MailTrait::$el))$item->sendMail($item);
            }
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $params
     */
    public function modelTest(\Illuminate\Database\Eloquent\Collection $params)
    {
        /** @var \App\Params $item */
        foreach( $params as $item )
        {
            $parsers = $this->parser($item->value);
            $item->saveContents($parsers, true);

            $this->checkUrlLimitCount($item->words);
            if( count(MailTrait::$el) ) $item->sendMail($item);

        }
    }

    /**
     * Проверка на количество просмотров
     */
    public function checkUrlLimitCount($words = null)
    {
        foreach(MailTrait::$el as $key => $item) {
            $html = $this->getHtmlAvito($item);
	        if(!$html)continue;
            $dom = HtmlDomParser::str_get_html($html);
            $text_count = (count($dom->find('.title-info-metadata-views')))?'.title-info-metadata-views':'.title-info-views';
            if (count($dom->find($text_count))) {
                $text_count = $dom->find($text_count)[0]->text();
                $count_see = trim(preg_replace('/\(.*\)/isu', '', $text_count));
                $count_see = (int)preg_replace('/\s/isu', '', $count_see);
                $text = $dom->find('div.item-view')[0]->text();
                if($words)
                {
                    $exp = explode(',', $words);
                    $check = false;
                    foreach($exp as $word)
                    {
                        if(mb_stristr($text, $word, 'UTF-8') !== false)$check = true;
                    }
                    if(!$check)$this->deleteElement($key);
                }
                if ($count_see >= $this->limit_see)$this->deleteElement($key);
            }
            else{
                syslog(LOG_ERR, 'not get see count '. $item);
            }
        }
    }

    public function parserPol($url = null)
    {
        $arr = [];
        $html = $this->getHtmlPol($url);
        $dom = HtmlDomParser::str_get_html($html);
        if(count($dom->find('li'))){
            foreach ($dom->find('li button') as $item){
                if(stristr($item->class, 'attention'))continue;
                $arr[$item->id]['class'] = $item->class;
                $arr[$item->id]['text'] = $item->text();
            }
        }
        return $arr;
    }

    /**
     * @param null $url
     *
     * @return array
     */
    public function parser($url = null)
    {
        $arr = [];
        if(is_array($url)){
            foreach($url as $url){
                $html = $this->getHtmlAvito($url);
	            if(!$html)return;
                $dom = HtmlDomParser::str_get_html($html);
                if(count($dom->find('div.item'))){
                    foreach ($dom->find('div.item') as $item)
                        $arr[$item->id] = $item->find('a')[0]->href;
                }
            }
        }
        else{
            $html = $this->getHtmlAvito($url);
            if(!$html)return;
            $dom = HtmlDomParser::str_get_html($html);
            $arr = [];
            if(count($dom->find('div.item'))){
                foreach ($dom->find('div.item') as $item)
                    $arr[$item->id] = $item->find('a')[0]->href;
            }
        }
        return $arr;
    }

    /**
     * Тест
     */
    public function testCount()
    {
    }
}
