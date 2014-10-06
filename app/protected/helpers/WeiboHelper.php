<?php

include_once 'saetv2.ex.class.php';

class WeiboHelper
{
    public $config,
            $instance = null,
            $client = null;

    public function __construct(WeixinAccount $account)
    {
        $this->config = $account;

        $config = array(
                'app_key' => $this->config->sina_weibo_app_key,
                'app_secret' => $this->config->sina_weibo_app_secret,
                'callback' => $this->config->sina_weibo_callback,
        );

        foreach ($config as $key => $value) {
            if (!$value) {
                throw new ExceptionClass("未设置{$key}的值。");
            }
        }

        /*$config = array(
                'app_key' => $account->sina_weibo_app_key,
                'app_secret' => $account->sina_weibo_app_secret,
                'callback' => $account->sina_weibo_callback,
        );*/

        if (!$this->instance) {
            $this->instance = new SaeTOAuthV2($this->config->sina_weibo_app_key, $this->config->sina_weibo_app_secret);
        }


    }

    public function getAuthorizeURL()
    {
        return $this->instance->getAuthorizeURL($this->config->sina_weibo_callback);
    }


    public function getAccessToken()
    {
        if (isset($_REQUEST['code'])) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = $this->config->sina_weibo_callback;
            try {
                $token = $this->instance->getAccessToken('code', $keys);
            } catch (OAuthException $e) {
            }
        } else {
            $token = null;
        }
        return $token;
    }

    private function getClient()
    {
        if (!$this->client) {
            $this->client = new SaeTClientV2(
                    $this->config->sina_weibo_app_key,
                    $this->config->sina_weibo_app_secret,
                    $this->config->sina_weibo_token
            );
        }

        return $this->client;
    }

    /**
     * 发送微博
     * @param $title
     * @param $description
     * @param $url
     * @param string $keyword
     * @param null $image
     * @return array
     */
    public function sendWeibo($title, $description, $url, $keyword = '', $image = null)
    {
        $client = $this->getClient();
        $titleLen = mb_strlen($title, 'UTF-8');

        //140字除去链接的20个字和省略符；剩115字左右，需要说明的是链接：无论文章的链接多长，在微博里都会被替换成短链接，按短链接的长度来计算字数；

        $summaryLen = 100 - $titleLen;
        $pubPaper = $this->cutstr_html($description);
        if (mb_strlen($pubPaper, 'UTF-8') >= $summaryLen) {
            $pubPaper = mb_substr($pubPaper, 0, $summaryLen, 'UTF-8');
        }


        #多个话题用,分割
        if ($tem_keyword = array_filter(explode(',', $keyword))) {
            $keyword = '';
            foreach ($tem_keyword as $key => $value) {
                $keyword .= '#' . $value . '#';
            }
        } else {
            $keyword = $keyword ? '#' . $keyword . '#' : '';
        }

        $content = sprintf('%s【%s】%s...%s', $keyword, $title, $pubPaper, urlencode($url));

//print_r($content);exit;

        if ($image) {
            $result = $client->upload($content, $image);
        } else {
            $result = $client->update($content);
        }

        return $result;
    }


    function cutstr_html($string)
    {
        $string = strip_tags($string);
        $string = preg_replace('/n/is', '', $string);
        $string = preg_replace('/ |　/is', '', $string);
        $string = preg_replace('/&nbsp;/is', '', $string);
        return $string;
    }

    /**
     * 结果解析
     */
    public function parseResult($result)
    {
        if ($result && $result['error_code']) {
            return false;
        } else {
            return $result;
        }
    }

}