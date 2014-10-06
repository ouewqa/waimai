<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-4-29
 * Time: 下午4:22
 * To change this template use File | Settings | File Templates.
 */
class WordHelper
{

    /**
     * @param $word
     * @param bool $htmlMode 是否需要html结构
     * @return string
     */
    public static function getHiragara($word, $htmlMode = false)
    {
        $content = mb_convert_encoding($word, 'euc-jp', 'utf-8');

        $url = 'http://www.hiragana.jp/cgi-bin/trans2.cgi';
        $data = 'html=0&body=' . urlencode($content);

//        $result = Yii::app()->curl->setHeaders(array(
//                'host' => 'www.hiragana.jp',
//                'origin' => 'http://www.hiragana.jp',
//                'referer' => 'http://www.hiragana.jp/reading/query.php',
//                'User-agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.72 Safari/537.36',
//        ))->post($url, $data, 'euc-jp');

        $curl = new Curl();
        $result = $curl->post($url, $data);

        if ($result) {
            preg_match('/<body>(.+?)<\/body>/is', $result, $hiragara);

            if ($hiragara && $hiragara[1]) {
                $result = trim($hiragara[1]);

                if (!$htmlMode) {
                    $result = strip_tags($result);
                }
            }
        } else {
            $result = $word;
        }
        return $result;
    }


    /**
     * 从沪江上获取信息
     * @param $word
     * @return array
     * @throws CHttpException
     */
    public static function getFromHJ($word)
    {

//        $curl = Yii::app()->curl->setHeaders(array(
//                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
//                'Accept-Language' => 'zh-CN,zh;q=0.8,en;q=0.6',
//                'Connection' => 'keep-alive',
//                'Host' => 'dict.hjenglish.com',
//                'Referer' => 'http://dict.hjenglish.com/',
//                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.72 Safari/537.36',
//        ));

        $url = 'http://dict.hujiang.com/services/GetWordInfo.ashx?callback=aaa&lang=jp&word=' . urlencode($word);
        $curl = new Curl();
        $html = $curl->get($url);

        preg_match('/aaa\((.+?)\)/is', $html, $match);
        if ($match && $match[1]) {
            $data = json_decode($match[1], false);
            $data = $data[0];
        } else {
            throw new CHttpException(404, 'word doesn\'t exsit.');
        }

        /*print_r($data);
        exit;*/
        $result = array(
            //'word' => $data->Word,
                'hiragara' => $data->Pronounce,
                'tone' => $data->Tone,
                'tts' => $data->Sound,
        );

        if (preg_match('/【(.+?)】/is', $data->Comment, $word_type)) {
            if ($word_type && $word_type[1]) {
                $result['word_type'] = $word_type[1];
            }
        }

        if (preg_match_all('/\s+(.+?。\/.+?。)/is', $data->Comment, $sentences)) {
            if ($sentences && $sentences[1]) {
                $i = 0;
                foreach ($sentences[1] as $key => $value) {


                    if (strpos($value, "\n") !== false) {
                        unset($sentences[1][$key]);
                    } else {
                        $result['sentences'][$key] = str_replace('/', '／', $sentences[1][$key]);;
                        $i++;
                    }

                    if ($i >= 3) {
                        break;
                    }

                }
            }

            sort($result['sentences']);

        }

        /* print_r($result);
         exit;*/

        return $result;
    }

} 