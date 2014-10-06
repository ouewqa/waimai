<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-5-8
 * Time: 下午11:47
 * To change this template use File | Settings | File Templates.
 */
class NHKHelper
{

    /**
     * 获取列表
     * @param string $nhk_date
     * @param null $nhk_time
     * @param string $nhk_mode
     * @param int $nhk_news_number
     * @return mixed
     */
    public static function getMediaList($nhk_date = '', $nhk_time = null, $nhk_mode = 'Y', $nhk_news_number = 5)
    {

        if ($nhk_time) {
            $nhk_times = explode(',', $nhk_time);


            $nhk_time = array();
            foreach ($nhk_times as $key => $value) {
                if (in_array($value, array('7', '12', '19', '22'))) {
                    $nhk_time[] = $value;
                }
            }
            $nhk_time = implode(',', $nhk_time);
        }


        #需要新闻稿　&&　不需要新闻稿
        if ($nhk_mode == 'Y') {
            $where = " AND n.association=1";

            if ($nhk_date) {
                $where .= " AND n.ndate='{$nhk_date}'";
            }
            if ($nhk_time) {
                $where .= " AND n.ntime IN($nhk_time)";
            }

            $sql = "SELECT n.*, ft.subject, ft.author, ft.tid, nt.title AS thread_title, nt.tid FROM " .
                    '{{nhk_thread}}' . " nt LEFT JOIN " .
                    '{{nhk}}' . " n ON nt.nid=n.nid LEFT JOIN " .
                    '{{forum_thread}}' . " ft ON nt.tid=ft.tid WHERE 1=1 $where ORDER BY nt.nid DESC, nt.tid DESC LIMIT {$nhk_news_number}";

        } else {
            #不需要新闻的必须存在于dropbox
            #$where = 'dropbox != ""';
            $where = '';

            if ($nhk_date) {
                if ($where) {
                    $where .= " AND ndate='{$nhk_date}'";
                } else {
                    $where .= " ndate='{$nhk_date}'";
                }

            }
            if ($nhk_time) {
                if ($where) {
                    $where .= " AND ntime IN($nhk_time)";
                } else {
                    $where .= " ntime IN($nhk_time)";
                }

            }

            if ($nhk_time) {
                $sql = "SELECT * FROM `{{nhk}}` WHERE $where" .
                        "ORDER BY ndate DESC, ntime DESC LIMIT {$nhk_news_number}";
            } else {
                $sql = "SELECT * FROM `{{nhk}}` WHERE $where" .
                        "ORDER BY ndate DESC, ntime DESC LIMIT {$nhk_news_number}";
            }
        }

        //exit($sql);
        $data = Yii::app()->db_ribenyu->cache(0)->createCommand($sql)->queryAll();
        return $data;
    }

    /**
     * 播放某个NHK
     */
    public static function showMedia($nid, $tid)
    {

        $sql = "SELECT `nid`, `title`, `mp3`, `dropbox`, `length`, `association` FROM `{{nhk}}` WHERE nid='{$nid}';";

        $nhk = Yii::app()->db_ribenyu->cache(3600)->createCommand($sql)->queryRow();

        if ($nhk) {
            $nhk['mp3'] = self::urlConvert($nhk['mp3']);

            if ($tid) {
                $sql = "
                    SELECT * FROM `{{forum_post}}` WHERE tid='{$tid}' AND first=1;
                ";
                $content = Yii::app()->db_ribenyu->cache(3600)->createCommand($sql)->queryRow();

                if ($content) {
                    $nhk['title'] = $content['subject'];

                    $nhk['content'] = trim(str_replace(
                            array(
                                    'thread_nhk',
                            )
                            , '', $content['message']));

                    $nhk['content'] = preg_replace('/\[.+?\]/i', '', $nhk['content']);


                }
            }

            return $nhk;
        }
    }

    public static function urlConvert($url)
    {
        $filename = array_pop(array_filter(explode('/', $url)));
        $date = substr($filename, 0, 6);
        return 'http://nhkserver.ribenyu.cn/' . $date . '/' . $filename;
    }
} 