<?php

class ToolsController extends AdminToolsController
{

    public function actionIndex()
    {


        $this->render('index', array());
    }

    /**
     * 修复二维码地址
     * @param int $page
     * @param int $pageSize
     */
    public function actionRepairQrCode($page = 1, $pageSize = 50)
    {
        $limit = $pageSize;
        $offset = max($page - 1, 0) * $pageSize;

        $weixininvitationcode = WeixinInvitationCode::model()->findAll(array(
                'condition' => '',
                'order' => 'weixin_id ASC',
                'with' => 'weixin',
                'limit' => $limit,
                'offset' => $offset,
        ));


        if ($weixininvitationcode) {
            $weixin = new WeixinHelper($this->account);

            foreach ($weixininvitationcode as $key => $value) {

                $result = $weixin->getQRTicket($value->id);
                if ($result && $result['errcode'] == 0) {
                    $value->setAttributes(array(
                            'ticket' => $result['data']['ticket'],
                    ));
                }


                /*print_r($value->getAttributes());
                exit;*/


                if (!$value->save()) {
                    print_r($value->getErrors());
                    exit;
                } else {
                    $user = Weixin::model()->findByPk($value->weixin_id);
                    $user->qrcode_ticket = $value->ticket;
                    $user->save();
                }
            }
            $this->success('修复成功，继续修复', array('/console/tools/repairQrCode', 'page' => $page + 1, 'pageSize' => $pageSize), 0);
        } else {
            $this->success('修复完毕', array('/console/tools/index'), 0);
        }
    }

    /**
     * 豆豆修复，只能运行一次
     * @param int $page
     * @param int $pageSize
     */
    public function actionRepairDoudou($page = 1, $pageSize = 50)
    {
        exit('无法再次运行');
        $limit = $pageSize;
        $offset = max($page - 1, 0) * $pageSize;

        $weixin = Weixin::model()->findAll(array(
                'order' => 'id ASC',
                'limit' => $limit,
                'offset' => $offset,
        ));
        if ($weixin) {
            foreach ($weixin as $key => $value) {
                $value->doudou = $value->invitation_count;
                $value->save();
            }
            $this->success('修复成功，继续修复', array('/console/tools/repairDoudou', 'page' => $page + 1, 'pageSize' => $pageSize), 0);
        } else {
            $this->success('修复完毕', array('/console/tools/index'), 0);
        }
    }

    /**
     * 考试成绩修复
     * @param int $page
     * @param int $pageSize
     */
    public function actionRepairExamScore($page = 1, $pageSize = 20)
    {
        $limit = $pageSize;
        $offset = max($page - 1, 0) * $pageSize;

        $exam_log = ExamLog::model()->findAll(array(
                'condition' => 't.status=:status',
                'params' => array(
                        ':status' => 'Y',
                ),
                'order' => 'weixin_id ASC',
                'limit' => $limit,
                'offset' => $offset,
        ));


        if ($exam_log) {
            foreach ($exam_log as $key => $value) {

                #分数详情
                $scores = Exam::model()->getExamScore($value->exam_id, $value->weixin_id);

                //print_r($scores);exit;
                #总分
                $totalScore = 0;
                foreach ($scores as $k => $v) {
                    $totalScore += $v['convertScore'];
                }

                $value->setAttributes(array(
                        'score' => $totalScore,
                        'score_detial' => serialize($scores),
                ));


                if (!$value->save()) {
                    print_r($value->getErrors());
                    exit;
                }
            }
            $this->success('修复成功，继续修复', array('/console/tools/repairExamScore', 'page' => $page + 1, 'pageSize' => $pageSize), 0);
        } else {
            $this->success('修复完毕', array('/console/tools/index'), 0);
        }
    }


    /**
     * 修复好友邀请数
     * @param int $page
     * @param int $pageSize
     */
    public function actionRepairInvitationCount($page = 1, $pageSize = 50)
    {
        $limit = $pageSize;
        $offset = max($page - 1, 0) * $pageSize;
        $model = WeixinInvitationCode::model()->findAll(array(
                'limit' => $limit,
                'offset' => $offset,
        ));

        if ($model) {

            foreach ($model as $key => $value) {
                $count = WeixinInvitationLog::model()->count(
                        'weixin_invitation_code_id=:weixin_invitation_code_id', array(
                                ':weixin_invitation_code_id' => $value->id
                        )
                );

                if ($count) {
                    Weixin::model()->updateByPk($value->weixin_id, array(
                            'invitation_count' => $count,
                    ));
                }

            }

            $this->success('修复成功，继续修复', array('/console/tools/repairInvitationCount', 'page' => $page + 1, 'pageSize' => $pageSize), 0);
        } else {
            $this->success('修复完毕', array('/console/tools/index'), 0);
        }
    }


    public function actionRepairTtsAttachment($page = 1, $pageSize = 30)
    {
        $tts = Tts::model()->findAll(
                array(
                        'condition' => 'filesize<:filesize',
                        'params' => array(
                                ':filesize' => 1000,
                        ),
                        'limit' => $pageSize,
                        'offset' => max($page - 1, 0) * $pageSize,
                        'order' => 'filesize ASC',
                )

        );

        if ($tts) {
            $realPath = dirname(Yii::app()->basePath);

            //exit($realPath);

            foreach ($tts as $key => $value) {
                echo $value->id, '<br />';

                if (file_exists($realPath . $value->path)) {
                    if (unlink($realPath . $value->path)) {
                        $value->delete();
                    } else {
                        exit('删除失败' . $realPath . $value->path);
                    }
                } else {
                    $value->delete();
                }


            }

            $this->success('修复成功，继续修复', array('/console/tools/repairTtsAttachment', 'page' => $page + 1, 'pageSize' => $pageSize), 0);
        } else {
            $this->success('修复完毕', array('/console/tools/index'), 0);
        }
    }

    /**
     * 单词分类下数量修复
     * @param int $page
     * @param int $pageSize
     */
    public function actionRepairWordCount($page = 1, $pageSize = 5)
    {
        $categories = WordCategory::model()->findAll();
        foreach ($categories as $key => $value) {


            #统计单词数
            $count = WordCategories::model()->count('word_category_id=:word_category_id', array(
                    ':word_category_id' => $value->id,
            ));

            $value->updateByPk($value->id, array(
                    'word_count' => $count,
            ));
        }

        $this->redirect('/console/tools/index');

    }

    public function actionWord()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('status="N"');
        $criteria->order = 'id ASC';
        $model = Word::model()->find($criteria);

//        $curl = Yii::app()->curl->setHeaders(array(
//                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
//                'Accept-Language' => 'zh-CN,zh;q=0.8,en;q=0.6',
//                'Connection' => 'keep-alive',
//                'Host' => 'dict.hjenglish.com',
//                'Referer' => 'http://dict.hjenglish.com/',
//                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.72 Safari/537.36',
//        ));

        $url = 'http://dict.hujiang.com/services/GetWordInfo.ashx?callback=aaa&lang=jp&word=' . urlencode($model->word);
        $curl = new Curl();
        $html = $curl->get($url);

        $html = preg_match('/aaa\((.+?)\)/is', $html, $match);
        if ($match && $match[1]) {
            $data = json_decode($match[1], false);
        } else {
            throw new CHttpException(404, 'word doesn\'t exsit.');
        }


        print_r($data);
        exit;

        $word = new WordForm();
        $word->setAttributes(array(
                'hiragara' => $data->Pronounce,
                'Tone' => $data->Tone,
                'hiragara' => $data->Pronounce,
        ));

        $word->save();

        $transaction = Yii::app()->db->beginTransaction();
        try {


            //【名】\r\n收据收据，发票。（おかねなどを受け取ること。）\r\n領収書を書いてください。/ 请给我开张发票。",
            if ($data->Comment) {
                preg_match('/【(.+?)】\\r\\n(.+?)\\r\\n/is', $data->Comment, $meaning);

                if ($meaning && $meaning[1] && $meaning[2]) {


                    WordMeaning::model()->deleteAll('word_id=:word_id', array(
                            ':word_id' => $word->id,
                    ));

                    $wordMeaning = new WordMeaning();
                    $wordMeaning->setAttributes(array(
                            'word_id' => $word->id,
                            'word_type' => $meaning[1],
                            'meaning' => $meaning[2],
                    ));
                    $wordMeaning->save();

                    $html = str_replace($meaning[0], '', $data->Comment);

                    preg_match_all('/(.+?)\/(.+?)。/is', $html, $sentences);


                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
        }


    }

    /**
     * 单词采集
     * @param int $retryCount
     * @internal param int $retry
     */
    public function actionWordCurl($retryCount = 0)
    {
        $word = Word::model()->find('meaning IS NULL AND status="N"');

        if (!$word) {
            $this->success('单词内容采集完毕', array('/console/tools/index'));
        }

        $api = 'http://dict.hjenglish.com/jp/jc/%s';

        $url = sprintf($api, urlencode($word->word));

        /*$url = 'http://dict.hjenglish.com/jp/jc/%E3%82%B3%E3%82%B9%E3%83%A1';
        $url = 'http://dict.hjenglish.com/jp/jc/%E5%86%99%E3%81%99';
        $url = 'http://dict.hjenglish.com/jp/jc/%EF%BC%93%E5%88%86%E5%89%B2';
        $url = 'http://dict.hjenglish.com/jp/jc/%E3%81%BE%E3%81%A3%E3%81%95%E3%82%89';
        $url = 'http://dict.hjenglish.com/jp/jc/%E3%81%BE%E3%81%A3%E3%81%97%E3%81%90%E3%82%89%E3%81%AB%E9%80%B2%E3%82%80';
        $url = 'http://dict.hjenglish.com/jp/jc/%E9%80%B2%E3%82%80';
        $url = 'http://dict.hjenglish.com/jp/jc/%E3%82%82%E3%81%A4';*/
        echo $url . '<br />';
        echo $word->word . '<br />';

//        $curl = Yii::app()->curl->setHeaders(array(
//                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
//                'Accept-Language' => 'zh-CN,zh;q=0.8,en;q=0.6',
//                'Connection' => 'keep-alive',
//                'Host' => 'dict.hjenglish.com',
//                'Referer' => 'http://dict.hjenglish.com/',
//                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.72 Safari/537.36',
//        ));

        $curl = new Curl();

        $html = $curl->get($url);

        if ((strpos($html, '没有找到你想要搜索的词条') != false) || (strpos($html, '日中查询无匹配结果')) || $retryCount > 5) {
            $word->status = 'F';
            $word->save();
            $this->success('单词不存在，继续采集', array('/console/tools/wordCurl'), 0);
        }


        preg_match('/<span id=\"kana_0\" class=\"trs_jp bold\" title=\"假名\">【(.+?)】<\/span>/is', $html, $hiragara);
        if ($hiragara && $hiragara[1] && $hiragara[1] != $word->word) {
            $hiragara = $hiragara[1];
        } else {
            $hiragara = null;
        }

        preg_match('/<span class=\"trs_jp  bold\" title=\"罗马音\">【(.+?)】<\/span>/is', $html, $roman);
        if ($roman && $roman[1]) {
            $roman = $roman[1];
        } else {
            $roman = null;
        }

        preg_match('/<span class=\'tone_jp\' title=\'音调\'>(.+?)<\/span>/is', $html, $tone);
        if ($tone && $tone[1]) {
            $tone = $tone[1];
        } else {
            $tone = null;
        }

        preg_match('/class=\"link_recite fl\" title=\"N(\d)基础词汇\"/is', $html, $level);
        if ($level && $level[1]) {
            $level = 'N' . $level[1];
        } else {
            $level = null;
        }

        preg_match('/GetTTSVoice\(\"(.+?)\"\)/is', $html, $tts);
        if ($tts && $tts[1]) {
            $tts = $tts[1];
        } else {
            $tts = null;
        }

        $wordtype = '';


        $transaction = Yii::app()->db->beginTransaction();
        try {


            preg_match_all('/<span id=\"jpword_(\d+)\" class=\"jpword\">(.+?)<\/span>/is', $html, $w);

            print_r($w);
            $ob = null;
            foreach ($w[2] as $a => $b) {
                //$word->word = 'もつ';
                if ($b == $word->word) {
                    $ob = $a;
                    break;
                }
            }

            var_dump($ob);
            if (is_null($ob)) {
                //exit('循环块出错');
                $this->success('循环块出错，重试一次', array('/console/tools/WordCurl', 'retryCount' => $retryCount + 1), 0);
            }

            preg_match_all('/<span id=\"comment_' . $ob . '\" class=\"commentItem\">(.+?)<\/span>\s+<br \/>/is', $html, $meanings);
            if ($meanings) {

                unset($meanings[0]);


                //print_r($meanings);

                //echo $html;
                //exit;
                if (!$meanings[1]) {
                    echo '内容为空';
                    $this->success('内容为空，重试一次', array('/console/tools/WordCurl'), 0);
                    exit;
                }

                foreach ($meanings[1] as $key => $value) {

                    if (isset($value) && $value) {
                        $content = $value;
                    } else {
                        exit('采集内容为空');
                    }

                    //echo '捕获到的内容块：' . $value;


                    #有清晰结构
                    if (strpos($content, '<p class="wordtype">') !== false) {
                        $structure = true;
                    } else {
                        $structure = false;
                    }


                    if ($structure) {
                        echo '带wordtype结构<br />';


                        #取出所有类型
                        preg_match_all('/<p class=\"wordtype\">(.+?)<\/p>/is', $content, $types);

                        #添加切割边界
                        $content = str_replace('<p class="wordtype">', '##wordtype##<p class="wordtype">', $content);

                        $contents = array_filter(explode('##wordtype##', $content));

                        /*print_r($contents);
                        exit;*/

                        foreach ($contents as $content) {

                            #类型
                            preg_match('/<p class=\"wordtype\">(.+?)<\/p>/is', $content, $type);
                            if ($type && $type[1]) {
                                $content = str_replace($type[0], '', $content);
                                $wordtype = $type[1];
                            } else {
                                $wordtype = '';
                            }

                            //$wordtype = str_replace(array('【', '】'), '', $wordtype);


                            if (strlen($wordtype) >= 60) {
                                continue;
                            }

                            //echo '单词内容：' . $content . '<br />';
                            echo '单词类型：' . $wordtype . '<br />';


                            if (strpos($content, '（1）') !== false) {
                                echo '有多个意思<br />';

                                $content = preg_replace('/（\d+）/', '|meaning|', $content);


                                $m = array_filter(explode('|meaning|', trim($content)));

                                //print_r($m);

                                if (!count($m)) {
                                    exit('没有多个意思');
                                }

                                //print_r($m);exit;


                                #循环多个意思
                                foreach ($m as $keys => $values) {

                                    echo '第' . $keys . '个意思：' . $values . '<br />';


                                    var_dump($values);

                                    preg_match('/(.+?)<br\/>/is', $values, $mean);

                                    if ($mean && $mean[1]) {
                                        $sentence = str_replace($mean[0], '', $values);
                                        $meaning = $mean[1];

                                        if (!trim($meaning)) {
                                            continue;
                                        }

                                        #单词意思
                                        $wordMeaning = WordMeaning::model()->find('word_id=:word_id AND meaning=:meaning', array(
                                                ':word_id' => $word->id,
                                                ':meaning' => trim($meaning),
                                        ));


                                        if (!$wordMeaning) {
                                            echo '单词意思' . $keys . '：' . $meaning . '<br />';
                                            $wordMeaning = new WordMeaning();
                                            $wordMeaning->attributes = array(
                                                    'word_id' => $word->id,
                                                    'word_type' => $wordtype,
                                                    'meaning' => trim($meaning),
                                            );

                                            echo '单词意思数组<br />';
                                            print_r($wordMeaning->getAttributes());

                                            if (!$wordMeaning->save()) {
                                                print_r($wordMeaning->getErrors());
                                                exit('$meaning->save()');
                                            }
                                        }


                                        #例句
                                        preg_match_all('/<img src=\'http:\/\/dict\.hjenglish\.com\/images\/icon_star\.gif\' align=\'absmiddle\' \/>(.+?)</is', $sentence, $s);

                                        if ($s && $s[1]) {
                                            unset($s[0]);

                                            //print_r($s[1]);exit;

                                            foreach ($s[1] as $k => $v) {
                                                $v = str_replace('/', '／', $v);

                                                if (strpos($v, '／') === false) {
                                                    $v = str_replace('。', '。／', $v);
                                                }

                                                $sen = array_filter(explode('／', $v));

                                                $sentence = Sentence::model()->find('sentence=:sentence', array(
                                                        ':sentence' => trim($sen[0]),
                                                ));

                                                if (!$sentence) {
                                                    echo '例句:' . $k . '：' . $v . '<br />';
                                                    $sentence = new Sentence;
                                                    $sentence->setAttributes(array(
                                                            'weixin_id' => 1,
                                                            'sentence' => trim($sen[0]),
                                                            'meaning' => trim($sen[1]),
                                                    ));

                                                    if (!$sentence->save()) {
                                                        print_r($sentence->getErrors());
                                                        exit('$sentence->save()');
                                                    }
                                                }


                                                $relatioin = WordSentences::model()->find('word_meaning_id=:word_meaning_id AND sentence_id=:sentence_id', array(
                                                        ':word_meaning_id' => $wordMeaning->id,
                                                        ':sentence_id' => $sentence->id,
                                                ));

                                                if (!$relatioin) {
                                                    $relatioin = new WordSentences();
                                                    $relatioin->setAttributes(array(
                                                            'word_meaning_id' => $wordMeaning->id,
                                                            'sentence_id' => $sentence->id,
                                                    ));
                                                    if (!$relatioin->save()) {
                                                        print_r($relatioin->getErrors());
                                                        exit('$relatioin->save()');
                                                    }
                                                }


                                            }


                                        }
                                    } else {
                                        echo $values;
                                        echo '<br /><br />&&&&&&&&&&&&&&&&&&&&&&&&<br /><br />';
                                        print_r($mean);
                                        exit('mean is empty');
                                    }

                                    echo '<br /><br />·······多个意思循环体·········<br /><br />';
                                }

                            } else {
                                #只有一个意思

                                echo '该单词只有一个意思<br />';
                                //echo $content;


                                preg_match('/(.+?)<br\/>/is', $content, $mean);

                                if ($mean && $mean[1]) {
                                    echo '多个意思<br />';

                                    $sentence = str_replace($mean[0], '', $content);
                                    $meaning = $mean[1];
                                    //print_r($sentence);exit;

                                    /*var_dump($meaning);
                                    exit;*/
                                    if (!trim($meaning)) {
                                        continue;
                                    }

                                    echo '句子：' . $sentence . '<br />';
                                    echo '意思：' . $meaning . '<br />';

                                    #单词意思
                                    $wordMeaning = WordMeaning::model()->find('word_id=:word_id AND meaning=:meaning', array(
                                            ':word_id' => $word->id,
                                            ':meaning' => trim($meaning),
                                    ));

                                    //print_r($sentence);exit;

                                    if (!$wordMeaning) {
                                        echo '单词意思：' . $meaning . '<br />';
                                        $wordMeaning = new WordMeaning();
                                        $wordMeaning->attributes = array(
                                                'word_id' => $word->id,
                                                'word_type' => $wordtype,
                                                'meaning' => trim($meaning),
                                        );

                                        echo '单词意思数组<br />';
                                        print_r($wordMeaning->getAttributes());

                                        if (!$wordMeaning->save()) {
                                            print_r($wordMeaning->getErrors());
                                            exit('$meaning->save()');
                                        }
                                    }

                                    //print_r($sentence);exit;


                                    #例句
                                    preg_match_all('/<img src=\'http:\/\/dict\.hjenglish\.com\/images\/icon_star\.gif\' align=\'absmiddle\' \/>(.+?)</is', $sentence, $s);

                                    if ($s && $s[1]) {
                                        unset($s[0]);

                                        //print_r($s);exit;

                                        foreach ($s[1] as $k => $v) {

                                            $v = str_replace('/', '／', $v);
                                            $sen = array_filter(explode('／', $v));

                                            $sentence = Sentence::model()->find('sentence=:sentence', array(
                                                    ':sentence' => trim($sen[0]),
                                            ));

                                            if (!$sentence) {

                                                echo '例句' . $k . '：' . $v . '<br />';
                                                $sentence = new Sentence;
                                                $sentence->setAttributes(array(
                                                        'weixin_id' => 1,
                                                        'sentence' => trim($sen[0]),
                                                        'meaning' => trim($sen[1]),
                                                ));

                                                if (!$sentence->save()) {
                                                    print_r($sentence->getErrors());
                                                    exit('$sentence->save()');
                                                }
                                            }


                                            $relatioin = WordSentences::model()->find('word_meaning_id=:word_meaning_id AND sentence_id=:sentence_id', array(
                                                    ':word_meaning_id' => $wordMeaning->id,
                                                    ':sentence_id' => $sentence->id,
                                            ));

                                            if (!$relatioin) {
                                                $relatioin = new WordSentences();
                                                $relatioin->setAttributes(array(
                                                        'word_meaning_id' => $wordMeaning->id,
                                                        'sentence_id' => $sentence->id,
                                                ));
                                                if (!$relatioin->save()) {
                                                    print_r($relatioin->getErrors());
                                                    exit('$relatioin->save()');
                                                }
                                            }


                                        }


                                    }
                                } else {
                                    echo '一个意思<br />';

                                    if (!$content) {
                                        continue;
                                    }
                                    #单词意思
                                    $wordMeaning = WordMeaning::model()->find('word_id=:word_id AND meaning=:meaning', array(
                                            ':word_id' => $word->id,
                                            ':meaning' => trim($content),
                                    ));

                                    //print_r($sentence);exit;

                                    if (!$wordMeaning) {
                                        echo '单词意思：' . $content . '<br />';
                                        $wordMeaning = new WordMeaning();
                                        $wordMeaning->attributes = array(
                                                'word_id' => $word->id,
                                                'word_type' => $wordtype,
                                                'meaning' => trim($content),
                                        );

                                        echo '单词意思数组<br />';
                                        print_r($wordMeaning->getAttributes());

                                        if (!$wordMeaning->save()) {
                                            print_r($wordMeaning->getErrors());
                                            exit('$meaning->save()');
                                        }
                                    }

                                }


                            }

                        }


                    } else {
                        if (strpos($content, '同：') === 0) {
                            continue;
                        }

                        preg_match('/(.+?)<br\/>/is', $content, $type);

                        if ($type && $type[1]) {
                            if (strpos($type[1], '【') !== false) {
                                $this->success('简单结构，重试一次~', array('/console/tools/WordCurl'), 0);
                            } else {
                                $content = $type[1];
                            }

                        }

                        echo $content;

                        #单词意思
                        $wordMeaning = WordMeaning::model()->find('word_id=:word_id AND meaning=:meaning', array(
                                ':word_id' => $word->id,
                                ':meaning' => trim($content),
                        ));

                        //print_r($sentence);exit;

                        if (!$wordMeaning) {
                            echo '单词意思：' . $content . '<br />';
                            $wordMeaning = new WordMeaning();
                            $wordMeaning->attributes = array(
                                    'word_id' => $word->id,
                                    'word_type' => $wordtype,
                                    'meaning' => trim($content),
                            );

                            echo '单词意思数组<br />';
                            print_r($wordMeaning->getAttributes());

                            if (!$wordMeaning->save()) {
                                print_r($wordMeaning->getErrors());
                                exit('$meaning->save()');
                            }
                        }


                        /*echo '简单结构<br />';
                        #类型



                        */
                    }


                    if ($structure) {

                    }

                    echo '<br /><br />######################<br /><br />';

                }


                $word->setAttributes(array(
                        'roman' => $roman,
                        'tone' => $tone,
                        'hiragara' => $hiragara,
                        'level' => $level,
                        'status' => 'Y',
                ));

                print_r($word->getAttributes());

                if (!$word->save()) {
                    print_r($word->getErrors());
                    exit('$word->save');
                }


            }


            $transaction->commit();

        } catch (Exception $e) {
            $transaction->rollBack();
            exit('采集出错');
        }

        $count = Word::model()->count('status="N"');
        $this->success('采集成功，剩余:' . $count, array('/console/tools/wordCurl'));

    }

    public function actionTts($page = 1, $pageSize = 5)
    {
        $limit = $pageSize;
        $offset = max($page - 1, 0) * $pageSize;

        $model = Tts::model()->findAll(array(
                'condition' => 'status=:status',
                'params' => array(
                        ':status' => 'N',
                ),
                'limit' => $limit,
                'offset' => $offset,
        ));

        $count = Tts::model()->count('status=:status', array(
                ':status' => 'N'
        ));

        if ($model) {

            foreach ($model as $key => $value) {
                var_dump($value->id);
                $result = TtsHelper::voice($value->word);
                //var_dump($result);exit;
            }

            $this->success('继续同步中，还有：' . $count, array('/console/tools/tts'), 0);
        } else {
            $this->success('同步完毕', array('/console/tools'));

        }
    }

    public function actionFlushCache()
    {
        Yii::app()->cache->flush();

    }

    public function actionEmail($id)
    {
        set_time_limit(0);

        $email = WeixinEmail::model()->findByPk($id);

        if ($email && Yii::app()->request->isPostRequest) {
            $criteria = new CDbCriteria();
            $criteria->compare('weixin_account_id', $this->account->id);
            $criteria->addCondition('email IS NOT NULL');

            if (isset($_POST['Weixin']['id']) && $_POST['Weixin']['id']) {
                $criteria->compare('id', $_POST['Weixin']['id']);
            }

            if (isset($_POST['Weixin']['province']) && $_POST['Weixin']['province']) {
                $criteria->compare('province', $_POST['Weixin']['province']);
            }


            if (isset($_POST['Weixin']['sex']) && $_POST['Weixin']['sex']) {
                $criteria->compare('sex', $_POST['Weixin']['sex']);
            }


            if (isset($_POST['Weixin']['jp_level']) && $_POST['Weixin']['jp_level']) {
                $criteria->compare('jp_level', $_POST['Weixin']['jp_level']);
            }

            if (isset($_POST['Weixin']['identity']) && $_POST['Weixin']['identity']) {
                $criteria->compare('identity', $_POST['Weixin']['identity']);
            }

            if (isset($_POST['Weixin']['status']) && $_POST['Weixin']['status']) {
                $criteria->compare('status', $_POST['Weixin']['status']);
            }

            //echo $criteria->condition;exit;

            $weixin = Weixin::model()->findAll($criteria);

            if ($weixin) {
                $transaction = Yii::app()->db->beginTransaction();
                try {

                    $sql = 'REPLACE INTO weixin_email_log(`weixin_id`, `weixin_email_id`, `email`, `title`, `content`, `status`) VALUES';
                    $temp_sql = array();
                    foreach ($weixin as $key => $value) {
                        $title = addslashes(trim($value->nickname) . '，' . $email->title);
                        $temp_sql[] = "($value->id, $id, '{$value->email}', '{$title}', '{$email->content}', 'N')";
                    }
                    $sql .= implode(', ', $temp_sql) . ';';

                    //exit($sql);

                    $total = Yii::app()->db->createCommand($sql)->execute();

                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    print_r($e->getMessage());
                    exit;

                }


                $email->status = 'Y';
                $email->dateline = time();
                $email->save();

                $this->success('共有' . $total . '封邮件加入计划任务。', array('/console/weixinEmail'));
            } else {
                $this->success('没有找到指定条件的用户', array('/console/weixinEmail'));

            }
        } else {
            $model = new Weixin();
            $this->render('email', array(
                    'model' => $model,
                    'email' => $email,
            ));
        }
    }

    /**
     * 清空素材库
     */
    public function actionCleanWeixinMaterial()
    {

        $model = WeixinMaterial::model()->findAll(array(
                'condition' => '',
                'order' => 'id ASC',
                'limit' => 50,
        ));

        if ($model) {
            foreach ($model as $key => $value) {
                $value->delete();
            }
            $this->success($value->title . '　删除成功', array('/console/tools/cleanWeixinMaterial'), 0);
        } else {
            $this->success('清除完毕', array('/console/tools'));
        }
    }

    public function actionFixedKeyword($page = 1, $pageSize = 20)
    {
        $limit = $pageSize;
        $offset = max($page - 1, 0) * $pageSize;
        $model = Keyword::model()->findAll(array(
                'condition' => '',
                'order' => 'id ASC',
                'limit' => $limit,
                'offset' => $offset,
        ));

        if ($model) {
            foreach ($model as $key => $value) {
                $count = KeywordRelation::model()->count('keyword_id=:keyword_id', array(
                        ':keyword_id' => $value->id,
                ));

                $value->count_hot = $count;
                $value->save();
            }
            $this->success($value->name . '　修复成功', array('/console/tools/fixedKeyword', 'page' => ++$page, 'pageSize' => $pageSize));
        } else {
            $this->success('修复完毕', array('/console/tools'));
        }
    }
}