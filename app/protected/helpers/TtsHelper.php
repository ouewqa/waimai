<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-3-24
 * Time: 上午11:04
 * To change this template use File | Settings | File Templates.
 */
class TtsHelper
{

    const API = 'http://208.109.168.116/GetAudio1.ashx?';
    const JAPANESE_SPEAKER = 302; #　日语发音　302女声　301男声
    const SAVE_PATH = 'attachments/tts'; #　日语发音　302女声　301男声

    public static function getFilename($word)
    {
        $word = trim($word);
        $filename = md5($word) . '.mp3';
        return $filename;
    }

    /**
     * 下载TTS
     * @param $word
     * @return null|string
     */
    public static function download($word)
    {

        $filename = self::getFilename($word);
        $url = sprintf('%sspeaker=%d&content=%s', self::API, self::JAPANESE_SPEAKER, '%20' . $word);

        try {
            $resouce = @file_get_contents($url);
        } catch (Exception $e) {
            print_r($e->getMessage());
            return null;
        }

        if ($resouce) {
            #保存文件
            $fileSize = strlen($resouce);

            if ($fileSize < 1000) {
                return null;
            }

            file_put_contents(self::getAudioPath($filename, true), $resouce);
            $result = self::getAudioPath($filename);
        } else {
            $result = null;
        }

        #保存到数据库
        if ($result) {
            //var_dump($result);exit('!!');
            self::save($word, $fileSize, self::getAudioPath($filename));
        }
        return $result;
    }

    /**
     * 获取文字的本地链接或远程链接
     * @param $word
     * @return string
     */
    public static function voice($word)
    {

        //$filename = self::getFilename($word);


        $result = Yii::app()->createUrl('weixin/default/tts', array(
                'word' => $word,
        ));


        /*if (!file_exists(self::getAudioPath($filename, true))) {
            $result = Yii::app()->createUrl('weixin/default/tts', array(
                    'word' => $word,
            ));
        } else {
            $result = self::getAudioPath($filename);
        }

        #更新使用次数
        $model = Tts::model()->find('word=:word', array(
                ':word' => $word,
        ));

        if ($model) {
            $model->view_count = $model->view_count + 1;
            $model->save();
        }*/

        return $result;
    }


    /**
     * 获取音频的保存路径
     * @param $filename
     * @param bool $absolute
     * @return string
     */
    public static function getAudioPath($filename, $absolute = false)
    {
        $dirName = substr($filename, 0, 2);

        $filePath = DIRECTORY_SEPARATOR . self::SAVE_PATH . DIRECTORY_SEPARATOR . self::JAPANESE_SPEAKER . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR;

        $realPath = dirname(Yii::app()->basePath) . $filePath;

        if (!file_exists($realPath)) {
            mkdir($realPath, 0777, true);
        }

        return $absolute ? $realPath . $filename : str_replace('\\', '/', $filePath . $filename);
    }

    public static function save($word, $fileSize, $filePath)
    {
        //var_dump($word , $fileSize);exit;
        if ($word && $fileSize) {
            $model = Tts::model()->find('word=:word', array(
                    ':word' => $word,
            ));

            if (!$model) {
                $model = new Tts();
                $model->attributes = array(
                        'word' => $word,
                        'filesize' => $fileSize,
                        'path' => $filePath,
                        'length' => strlen($word),
                        'status' => 'Y',
                );
            } else {
                /* $model->attributes = array(
                         'view_count' => $model->view_count + 1,
                 );*/
            }


            if (!$model->save()) {
                throw new CHttpException(500, '保存TTS表出错');
            }
        }

    }
} 