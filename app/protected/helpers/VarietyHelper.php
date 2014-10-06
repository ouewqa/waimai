<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-5-9
 * Time: 上午12:50
 * To change this template use File | Settings | File Templates.
 */
class VarietyHelper
{
    const SUBSCRIBENUMBERLIMIT = 50;

    /**
     * 获取最新的剧集
     * @param null $variety_cate
     * @return mixed
     */
    public static function getLastest($variety_cate = null)
    {
        if ($variety_cate) {
            $variety_cate = implode(',', array_filter(explode(',', $variety_cate)));
            $sql = "SELECT a.*, i.itemid as last_item_id, i.title as last_item_title, i.thumb as cover FROM `{{video_album}}` a LEFT JOIN `{{video_item}}` i ON i.albumid=a.albumid WHERE a.catid=1 AND a.albumid IN($variety_cate) ORDER BY itemid DESC LIMIT 9";
        } else {
            $sql = "SELECT * FROM `{{video_album}}` WHERE catid=1 ORDER BY dateline DESC LIMIT 9";
        }

        $data = Yii::app()->db_ribenyu->cache(3600)->createCommand($sql)->queryAll();
        return $data;
    }

    /**
     * 获取最新的专辑
     * @param null $variety_cate 专辑分类
     * @param int $page
     * @param int $pageSize
     * @return mixed
     */
    public static function getAlbum($page = 1, $pageSize = 50, $variety_cate = null)
    {
        $limit = $pageSize;
        $offset = max($page - 1, 0) * $pageSize;
        if ($variety_cate) {
            $sql = "SELECT * FROM `{{video_album}}` WHERE catid=1 AND albumid IN($variety_cate) ORDER BY dateline DESC LIMIT $offset,$limit";
        } else {
            $sql = "SELECT * FROM `{{video_album}}` WHERE catid=1 ORDER BY dateline DESC LIMIT $offset,$limit";
        }

        $data = Yii::app()->db_ribenyu->cache(1800)->createCommand($sql)->queryAll();
        return $data;
    }

    /**
     * 获取剧集内容
     * @param $album_id
     * @param $item_id
     * @return mixed
     */
    public static function getDetial($album_id, $item_id)
    {

        if ($album_id && $item_id) {
            $sql = "SELECT * FROM `{{video_item}}` WHERE itemid='{$item_id}' AND albumid='{$album_id}' LIMIT 1";


            $data = Yii::app()->db_ribenyu->cache(3600)->createCommand($sql)->queryRow();

            #查询往期剧集
            $sql = "SELECT * FROM `{{video_item}}` WHERE albumid='{$album_id}' AND subtitle!='' ORDER BY subtitle DESC LIMIT 8";
            $history = Yii::app()->db_ribenyu->cache(36000)->createCommand($sql)->queryAll();

            if ($history) {
                $data['history'] = $history;
            }

            return $data;
        }
    }

    /**
     * 检查订阅的视频，是否有更新
     * @param $uid
     * @param int $limit
     * @return mixed
     */
    public static function checkVideoSubscribeStatus($uid, $limit = 50)
    {
        $limit = min($limit, 50);
        $sql = "SELECT s.albumid, a.name, a.last_item_title, a.last_item_id,a.cover FROM `{{video_subscribe}}` s LEFT JOIN `{{video_album}}` a ON s.albumid = a.albumid WHERE a.last_item_id != s.itemid AND uid='{$uid}' ORDER BY s.dateline DESC LIMIT $limit";

        return Yii::app()->db_ribenyu->cache(3600)->createCommand($sql)->queryAll();
    }

    /**
     * 订阅视频
     * @param $uid
     * @param $albumid
     * @param $itemid
     * @return bool|string
     */
    public static function subscribe($uid, $albumid, $itemid)
    {
        if (!$uid || !$albumid) {
            return array(
                    'status' => false,
                    'msg' => "参数出错{$uid}|{$albumid}",
            );
        }


        if (!self::checkIsSubscribed($uid, $albumid)) {
            $number = self::getSubscribeNumber($uid);
            if ($number > self::SUBSCRIBENUMBERLIMIT) {
                $message = array(
                        'status' => false,
                        'msg' => "订阅失败，你最多能订阅" . self::SUBSCRIBENUMBERLIMIT . "个节目。",
                );
            } else {

                #订阅流程
                $sql = sprintf("INSERT INTO `{{%s}}` (uid, albumid, itemid, status, dateline) VALUES(%d, %d, %d, %d, %d)", 'video_subscribe', $uid, $albumid, $itemid, 1, time());
                if (Yii::app()->db_ribenyu->createCommand($sql)->execute()) {
                    $message = array(
                            'status' => true,
                            'msg' => '订阅成功，当节目更新时会以站时消息通知你。',
                            'data' => array(
                                    'label' => '退订本节目',
                                    'type' => 'btn-danger',
                            ),
                    );

                } else {
                    $message = array(
                            'status' => false,
                            'msg' => '订阅失败，请联系管理员',
                    );

                }
            }
        } else {
            #退订流程
            $sql = sprintf('DELETE FROM `{{%s}}` WHERE uid="%d" AND albumid="%d"', 'video_subscribe', $uid, $albumid);

            if (Yii::app()->db_ribenyu->createCommand($sql)->execute()) {
                $message = array(
                        'status' => true,
                        'msg' => '退订成功',
                        'data' => array(
                                'label' => '订阅本节目',
                                'type' => 'btn-success',
                        ),
                );
            } else {
                $message = array(
                        'status' => false,
                        'msg' => '退订失败，请联系管理员',
                );
            }
        }

        return $message;
    }

    /**
     * 更新订阅状态
     */
    public static function updateSubscribeStatus($uid, $albumid, $itemid)
    {
        $sql = sprintf("UPDATE `{{video_subscribe}}` SET  `itemid` = '%d', `dateline` = '%d' WHERE `uid` = '%d' AND `albumid` = '%d'", $itemid, time(), $uid, $albumid);

        return Yii::app()->db_ribenyu->createCommand($sql)->execute();
    }

    /**
     * 获取订阅的数量
     * @param $uid
     * @return mixed
     */
    public static function getSubscribeNumber($uid)
    {
        $sql = sprintf("SELECT count(`sid`) FROM `{{video_subscribe}}` WHERE `uid`='%d'", $uid);
        return Yii::app()->db_ribenyu->createCommand($sql)->queryScalar();
    }

    /**
     * 检查专题是否已订阅
     * @param $uid
     * @param $albumid
     */
    public static function checkIsSubscribed($uid, $albumid)
    {
        $sql = sprintf("SELECT `sid` FROM `{{%s}}` WHERE `uid`='%d' AND `albumid`='%d'", 'video_subscribe', $uid, $albumid);
        return Yii::app()->db_ribenyu->createCommand($sql)->queryScalar();
    }
} 