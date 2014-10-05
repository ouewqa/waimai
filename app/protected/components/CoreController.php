<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Keen
 * Date: 13-8-23
 * Time: 下午4:24
 * To change this template use File | Settings | File Templates.
 */
class CoreController extends Controller
{
    public $navbar;

    public function jsonout($data)
    {
        header("Content-type:json/application;charset=utf-8");
        echo $this->out(CJSON::encode($data));
    }

    public function out($data)
    {
        echo $data;
        Yii::app()->end();
    }


    protected function checkActionActive($route)
    {
        $module = Yii::app()->controller->module->id;
        $controller = Yii::app()->controller->id;
        $action = Yii::app()->controller->action->id;
        $string = $module . '/' . $controller . '/' . $action;

        return strpos($route[0], $string) !== false;
    }


    public function success($message, $url = null, $time = 5, $extra = null)
    {
        $this->showmessage($message, __FUNCTION__, $url, $time, $extra);
    }


    public function error($message, $url = null, $time = 5, $extra = null)
    {
        $this->showmessage($message, __FUNCTION__, $url, $time, $extra);
    }

    public function warning($message, $url = null, $time = 5, $extra = null)
    {
        $this->showmessage($message, __FUNCTION__, $url, $time, $extra);
    }


    public function showmessage($message = '成功', $status = 'success', $url, $time, $extra)
    {

        if (is_array($url)) {
            $route = isset($url[0]) ? $url[0] : '';
            $url = $this->createUrl($route, array_splice($url, 1));
        }
        if ($url) {
            $go = "window.location.href='{$url}';";
        } else {
            $go = "history.back();";
        }

        $this->render('//show_message', array(
                'url' => $url,
                'go' => $go,
                'time' => $time,
                'message' => $message,
                'stauts' => $status,
                'extra' => $extra
        ));
        Yii::app()->end();
    }

    /**
     * 浏览器直接下载
     * @param $fileName
     * @param $filePath
     * @throws CHttpException
     */
    public function download($filePath, $fileName = null)
    {

        #文件名
        $fileName = $fileName ? $fileName : FileHelper::getFileName($filePath, true);

        #文件路径
        $basePath = dirname(Yii::app()->basePath);
        $filePath = $basePath . $filePath;

        #非LINUX系统需要转码
        if (PATH_SEPARATOR != ':') {
            $filePath = iconv('UTF-8', 'GB2312', $filePath);
        }

        #文件是否存在
        if (!file_exists($filePath)) {
            throw new CHttpException(404, '图片不存在');
        }

        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
        header("Content-type:application/octet-stream;charset=utf-8");

        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = urlencode($fileName);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);

        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8/' / '' . $fileName . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
        }
        header("Content-Transfer-Encoding: binary");
        readfile($filePath);
    }

}