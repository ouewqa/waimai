<?php

/**
 * Image helper functions
 *
 * @author Chris
 * @link http://con.cept.me
 */
class ImageHelper
{

    /**
     * Directory to store thumbnails
     * @var string
     */
    const THUMB_DIR = 'thumb';

    /**
     * 检查图片是否存在
     * @param $path
     * @return bool
     */
    public static function checkPath($path)
    {
        if (!file_exists($path)) {
            $path = str_replace('\\', '/', YiiBase::getPathOfAlias('webroot') . $path);
            if (file_exists($path)) {
                return $path;
            }
        } else {
            return $path;
        }
    }

    /**
     * 删除图片
     * @param $path
     */
    public static function delete($path)
    {
        if ($path = self::checkPath($path)) {
            unlink($path);
        }
    }

    /**
     * 设置完整图片路径
     * @param $path
     * @return string
     */
    public static function setFullPath($path)
    {
        if (strpos($path, Yii::app()->request->hostInfo) === false) {
            $path = Yii::app()->request->hostInfo . $path;
        }
        return $path;
    }

    /**
     * Create a thumbnail of an image and returns relative path in webroot
     * the options array is an associative array which can take the values
     * quality (jpg quality) and method (the method for resizing)
     *
     * @param string $img
     * @param int $width
     * @param int $height
     * @param array $options
     * @throws CHttpException
     * @return string $path
     */


    public static function thumb($img, $width, $height, $options = null)
    {

        if (!$img || strpos($img, 'http://placehold.it') !== false || !$img = self::checkPath($img)) {
            return $img;
        }


        // Jpeg quality
        $quality = 100;
        // Method for resizing
        $method = 'adaptiveResize';

        if ($options) {
            extract($options, EXTR_IF_EXISTS);
        }


        $pathinfo = pathinfo($img);
        $thumb_name = "thumb_" . $pathinfo['filename'] . '_' . $method . '_' . $width . '_' . $height . '.' . $pathinfo['extension'];


        $temp_path = substr($pathinfo['dirname'], strlen(YiiBase::getPathOfAlias('webroot')));
        $temp_path = substr_replace($temp_path, '/attachments/' . self::THUMB_DIR . '/', 0, strlen('/attachments/'));

        $thumb_path = YiiBase::getPathOfAlias('webroot') . $temp_path . '/';


        if (!file_exists($thumb_path)) {
            mkdir($thumb_path, 0777, true);
        }

        if (!file_exists($thumb_path . $thumb_name) || filemtime($thumb_path . $thumb_name) < filemtime($img)) {

            Yii::import('ext.phpThumb.PhpThumbFactory');
            $options = array('jpegQuality' => $quality);
            $thumb = PhpThumbFactory::create($img, $options);
            $thumb->{$method}($width, $height);
            $thumb->save($thumb_path . $thumb_name);
        }

        $relative_path = str_replace(YiiBase::getPathOfAlias('webroot'), '', $thumb_path . $thumb_name);

        return self::setFullPath($relative_path);
    }

    public static function watermark($img)
    {
        $obj = new WaterMaskHelper($img); //实例化对象
        $obj->waterType = 1; //类型：0为文字水印、1为图片水印
        $obj->pos = 0; //水印位置
        $obj->transparent = 100; //水印透明度
        $obj->output(); //输出水印图片文件覆盖到输入的图片文件
    }


    /**
     * 自动将HTML中的所有图片缩小
     * @param $html
     * @return mixed
     */
    public static function htmlImageAutoThumb($html)
    {
        return $html;
        if (preg_match_all("/<img.+?src=\"(.+?)\"/is", $html, $match)) {
            $newArr = array();
            $matches = array_unique($match[1]);

            foreach ($matches as $key => $value) {

                #不是http打开的，或者http打开，并且是本站的。必须不带有/.thumb/
                if (((strpos($value, 'http') !== false && strpos($value, Yii::app()->request->hostInfo) !== false) || strpos($value, 'http') === false) && strpos($value, '/.thumb/') == false) {
                    $newArr[$key] = self::thumb($value, 320, 160);
                } else {
                    $newArr[$key] = $value;
                }
            }
            $html = str_replace($matches, $newArr, $html);
        }
        return $html;
    }
}