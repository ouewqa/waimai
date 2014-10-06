<?php

class FileHelper extends CFileHelper
{
    /**
     * 获取物理路径
     * @param $path
     * @return string
     */
    public static function getPathPhysical($path)
    {
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        return dirname(Yii::app()->BasePath) . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * 获取外网访问的绝对路径
     * @param $path
     * @return string
     */
    public static function getPathAbsolute($path)
    {
        if (strpos($path, 'http') !== 0) {
            $path = Yii::app()->request->hostInfo . $path;
        }

        return $path;
    }

    public static function getFileName($name, $needExt = true)
    {
        $filename = array_pop(explode(DIRECTORY_SEPARATOR, $name));
        if (!$filename) {
            $filename = array_pop(explode('/', $name));
        }


        if (!$filename) {
            throw new CHttpException(404, '文件名称不存在');
        }
        if (!$needExt) {
            $filename = str_replace('.' . self::getExtension($name), '', $filename);
        }

        return $filename;
    }

    public static function getFileExt($name)
    {
        return array_pop(explode('.', $name));
    }


    public static function getTranslatedName($word)
    {
        return YText::translit($word);
    }

    /**
     * 检查路径是否可写
     * @param $name
     * @param $ext
     * @param $path
     * @return bool|string
     */
    public static function pathIsWritable($name, $ext, $path)
    {
        if (self::checkPath($path))
            return $path . self::getTranslatedName($name) . '.' . $ext;
        else
            return false;
    }

    /**
     * 将相对网址替换成绝对网站
     * @param $convertUrl 　相对网站
     * @param $URI  当前网址
     * @return string
     */
    public static function fillUrl($convertUrl, $URI)
    {
        $urls = @parse_url($URI);
        $_HomeUrl = $urls["host"];
        $_BaseUrlPath = $_HomeUrl . $urls["path"];
        $_BaseUrlPath = preg_replace("/\/([^\/]*)\.(.*)$/", "/", $_BaseUrlPath);
        $_BaseUrlPath = preg_replace("/\/$/", "", $_BaseUrlPath);

        $i = 0;
        $dstr = "";
        $pathStep = 0;
        $convertUrl = trim($convertUrl);
        if ($convertUrl == "")
            return "";
        $pos = strpos($convertUrl, "#");
        if ($pos > 0)
            $convertUrl = substr($convertUrl, 0, $pos);
        if ($convertUrl[0] == "/") {
            $okurl = "http://" . $_HomeUrl . $convertUrl;
        } else
            if ($convertUrl[0] == ".") {
                if (strlen($convertUrl) <= 1)
                    return "";
                else
                    if ($convertUrl[1] == "/") {
                        $okurl = "http://" . $_BaseUrlPath . "/" . substr($convertUrl, 2, strlen($convertUrl) - 2);
                    } else {
                        $urls = explode("/", $convertUrl);
                        foreach ($urls as $u) {
                            if ($u == "..")
                                $pathStep++;
                            else
                                if ($i < count($urls) - 1)
                                    $dstr .= $urls[$i] . "/";
                                else
                                    $dstr .= $urls[$i];
                            $i++;
                        }
                        $urls = explode("/", $_BaseUrlPath);
                        if (count($urls) <= $pathStep)
                            return "";
                        else {
                            $pstr = "http://";
                            for ($i = 0; $i < count($urls) - $pathStep; $i++) {
                                $pstr .= $urls[$i] . "/";
                            }
                            $okurl = $pstr . $dstr;
                        }
                    }
            } else {
                if (strlen($convertUrl) < 7)
                    $okurl = "http://" . $_BaseUrlPath . "/" . $convertUrl;
                else
                    if (strtolower(substr($convertUrl, 0, 7)) == "http://")
                        $okurl = $convertUrl;
                    else
                        $okurl = "http://" . $_BaseUrlPath . "/" . $convertUrl;
            }
        $okurl = str_replace("http://", "", $okurl);
        $okurl = preg_replace("/\/{1,}/is", "/", $okurl);
        return "http://" . $okurl;
    }


    /**
     * 检查路径是否存在及可写
     * @param $path
     * @return bool
     */
    public static function checkPath($path)
    {
        if (!is_dir($path))
            return mkdir($path);
        else if (!is_writable($path))
            return false;
        return true;
    }

    /**
     * 判断文件是否存在
     * @param $path
     * @return bool
     */
    public static function checkFileExist($path)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

        $contents = curl_exec($ch);
        //echo $contents;
        return !preg_match("/\s404\s/", $contents);
    }


    /**
     * Рекрусивное удаление директорий.
     *
     * @param $path Если $path оканчивается на *, то удаляется только содержимое директории.
     * @since 0.5
     * @return bool
     */
    public static function rmDir($path)
    {
        static $doNotRemoveBaseDirectory = false, $baseDirectory;

        $path = trim($path);
        if (substr($path, -1) == '*') {
            $doNotRemoveBaseDirectory = true;
            $path = substr($path, 0, -1);
        }
        if (substr($path, -1) == '/') {
            $path = substr($path, 0, -1);
        }
        if ($doNotRemoveBaseDirectory) {
            $baseDirectory = $path;
        }

        if (is_dir($path)) {
            $dirHandle = opendir($path);
            while (false !== ($file = readdir($dirHandle))) {
                if ($file != '.' && $file != '..') {
                    $tmpPath = $path . '/' . $file;

                    if (is_dir($tmpPath)) {
                        self::rmDir($tmpPath);
                    } else {
                        if (file_exists($tmpPath)) {
                            unlink($tmpPath);
                        }
                    }
                }
            }
            closedir($dirHandle);

            // удаляем текущую папку
            if ($doNotRemoveBaseDirectory === true && $baseDirectory == $path) {
                return true;
            }
            return rmdir($path);
        } elseif (is_file($path) || is_link($path)) {
            return unlink($path);
        } else {
            return false;
        }
    }


    /**
     * 重写file_get_contents，加入超时及代理
     * @param $url
     * @param null $proxy 代理IP:端口
     * @param string $charset
     * @param int $time 设置一个超时时间，单位为秒
     * @return string
     */
    public static function file_get_contents($url, $proxy = null, $charset = null, $time = 20)
    {


        $ctx = array(
                'http' => array(
                        'request_fulluri' => true,
                        'timeout' => $time
                ),
        );

        if ($proxy) {
            $ctx['http']['proxy'] = $proxy;
        }

        try {
            $data = file_get_contents($url, false, stream_context_create($ctx));

        } catch (Exception $e) {
            $proxys = array(
                    '120.237.91.242:3128', #深圳
                    '121.10.252.139:3128', #四会
            );
            $ctx['http']['proxy'] = $proxys[rand(0, count($proxys) - 1)];
            $data = file_get_contents($url, false, stream_context_create($ctx));
        }

        #设置字符集
        if ($charset && $charset != 'utf-8') {
            $data = mb_convert_encoding($data, 'utf-8', $charset);
        }

        return $data;
    }

}