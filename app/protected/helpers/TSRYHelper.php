<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-5-8
 * Time: 下午11:47
 * To change this template use File | Settings | File Templates.
 */
class TSRYHelper
{

    /**
     * 获取单个
     * @param $id
     * @return mixed
     */
    public static function get($id)
    {
        if ($id) {
            #$sql = "SELECT * FROM `{{tsry_original}}` WHERE tid='{$id}' ORDER BY sn ASC LIMIT 6";
            $sql = "SELECT t.subject, o.* FROM `{{tsry}}` t LEFT JOIN `{{tsry_original}}` o ON t.tid=o.tid WHERE t.tid='{$id}'";
            $data = Yii::app()->db_ribenyu->cache(0)->createCommand($sql)->queryAll();
            return $data;
        }
    }

    /**
     * 获取翻译
     * @param null $oid
     * @return array
     */
    public static function getTranslation($oid = null)
    {
        if ($oid) {
            $sql = "SELECT o.original, o.sn, r.* FROM `{{tsry_original}}` o LEFT JOIN `{{tsry_relation}}` r ON o.oid=r.oid WHERE o.oid='{$oid}' ORDER BY dig DESC, dateline ASC LIMIT 30";

            $data = Yii::app()->db_ribenyu->cache(0)->createCommand($sql)->queryAll();

            $note = $relation = array();
            $original = $sn = $oid = $tid = '';
            foreach ($data as $key => $value) {
                if ($value['note']) {
                    $note[] = $value['note'];
                }

                if ($key == 0) {
                    $original = $value['original'];
                    $sn = $value['sn'];
                    $tid = $value['tid'];
                    $oid = $value['oid'];
                }

                $relation[] = array(
                        'relation' => $value['relation'],
                        'uid' => $value['uid'],
                        'dig' => $value['dig'],
                        'dateline' => $value['dateline'],
                );
            }

            return array(
                    'tid' => $tid,
                    'oid' => $oid,
                    'original' => $original,
                    'sn' => $sn,
                    'relation' => $relation,
                    'note' => $note,
            );
        }
    }

    /**
     * 获取列表
     * @param null $date
     * @return mixed
     */
    public static function getLastest($date = null)
    {

        if ($date) {
            $sql = "SELECT o.*,t.subject FROM {{tsry}} t LEFT JOIN {{tsry_original}} o ON t.tid=o.tid WHERE t.dateline='{$date}' ORDER BY t.tid DESC, o.sn ASC LIMIT 6";
        } else {
            $sql = "SELECT o.*,t.subject FROM {{tsry}} t LEFT JOIN {{tsry_original}} o ON t.tid=o.tid ORDER BY t.tid DESC, o.sn ASC LIMIT 6";
        }

        $data = Yii::app()->db_ribenyu->cache(1800)->createCommand($sql)->queryAll();
        return $data;
    }
} 