<?php

class FileUploadController extends Controller
{

    public function actionDeleteImage($path)
    {

        $path = urldecode($path);

        $string = sprintf('/user/%d/images/', Yii::app()->user->id);
        if (strpos($path, $string) !== false) {
            $path = str_replace('\\', '/', YiiBase::getPathOfAlias('webroot') . $path);
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }


    public function actionYiiWheels()
    {
        $result = json_decode($this->actionIndex('uploadimage', 'return'));

        if ($result && $result->state == 'SUCCESS') {


            $result = array(
                    array(
                            'name' => $result->title,
                            'size' => $result->size,
                            'url' => $result->url,
                            'thumbnail_url' => ImageHelper::thumb($result->url, 200, 120, array('method' => 'resize')),
                            'delete_url' => $this->createUrl('/member/fileUpload/deleteImage', array('path' => urlencode($result->url))),
                            'delete_type' => 'DELETE',
                    )
            );
            echo json_encode($result);
        }
    }

    /**
     * 上传数据接收流程
     * 如果是微信端上传的数据。直接传到 weixin目录上，用所属用户的UID
     * @param $action
     * @param string $returnType
     * @return string
     * @internal param \角色ID $uid
     */
    public function actionIndex($action, $returnType = 'print')
    {

        //header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
        //header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
        date_default_timezone_set("Asia/chongqing");
        error_reporting(E_ERROR);

        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(Yii::app()->basePath . "/config/ueditor.config.json")), true);


        switch ($action) {
            case 'config':
                $result = json_encode($CONFIG);
                break;

            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = $this->_uploadfile($CONFIG);
                break;

            /* 列出图片 */
            case 'listimage':
                $result = $this->_list($CONFIG);
                break;
            /* 列出文件 */
            case 'listfile':
                $result = $this->_list($CONFIG);
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->_crawler($CONFIG);
                break;

            default:
                $result = json_encode(array(
                        'state' => '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                $result = htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                $result = json_encode(array(
                        'state' => 'callback参数不合法'
                ));
            }
        }

        if ($returnType == 'print') {
            echo $result;
        } else {
            return $result;
        }
    }


    private function _crawler($CONFIG)
    {
        set_time_limit(0);

        /* 上传配置 */
        $config = array(
                "pathFormat" => $CONFIG['catcherPathFormat'],
                "maxSize" => $CONFIG['catcherMaxSize'],
                "allowFiles" => $CONFIG['catcherAllowFiles'],
                "oriName" => "remote.png"
        );
        $fieldName = $CONFIG['catcherFieldName'];

        /* 抓取远程图片 */
        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new UploaderHelper($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                    "state" => $info["state"],
                    "url" => $info["url"],
                    "size" => $info["size"],
                    "title" => htmlspecialchars($info["title"]),
                    "original" => htmlspecialchars($info["original"]),
                    "source" => htmlspecialchars($imgUrl)
            ));
        }

        /* 返回抓取数据 */
        return json_encode(array(
                'state' => count($list) ? 'SUCCESS' : 'ERROR',
                'list' => $list
        ));
    }

    private function _list($CONFIG)
    {


        /* 判断类型 */
        switch ($_GET['action']) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $CONFIG['fileManagerAllowFiles'];
                $listSize = $CONFIG['fileManagerListSize'];
                $path = $CONFIG['fileManagerListPath'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $CONFIG['imageManagerAllowFiles'];
                $listSize = $CONFIG['imageManagerListSize'];
                $path = $CONFIG['imageManagerListPath'];
        }


        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "" : "/") . $path;

        $files = $this->_getfiles($path, $allowFiles);
        if (!count($files)) {
            return json_encode(array(
                    "state" => "no match file",
                    "list" => array(),
                    "start" => $start,
                    "total" => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }
//倒序
//for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
//    $list[] = $files[$i];
//}

        /* 返回数据 */
        $result = json_encode(array(
                "state" => "SUCCESS",
                "list" => $list,
                "start" => $start,
                "total" => count($files)
        ));

        return $result;


        /**
         * 遍历获取目录下的指定类型的文件
         * @param $path
         * @param array $files
         * @return array
         */
    }

    private function _getfiles($path, $allowFiles, &$files = array())
    {

        #手动添加

        $role = 'user';
        if (!Yii::app()->user->isGuest) {
            $uid = Yii::app()->user->id;
        } else {
            $uid = 0;
        }
        $path = str_replace("{role}", $role, $path);
        $path = str_replace("{uid}", $uid, $path);


        if (!is_dir($path)) return null;
        if (substr($path, strlen($path) - 1) != '/') $path .= '/';


        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->_getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                        $files[] = array(
                                'url' => substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                                'mtime' => filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }

    /**
     * 上传过程
     * @param $CONFIG
     * @return string
     */
    private function _uploadfile($CONFIG)
    {


        /* 上传配置 */
        $base64 = "upload";
        switch (htmlspecialchars($_GET['action'])) {
            case 'uploadimage':
                $config = array(
                        "pathFormat" => $CONFIG['imagePathFormat'],
                        "maxSize" => $CONFIG['imageMaxSize'],
                        "allowFiles" => $CONFIG['imageAllowFiles']
                );
                $fieldName = $CONFIG['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = array(
                        "pathFormat" => $CONFIG['scrawlPathFormat'],
                        "maxSize" => $CONFIG['scrawlMaxSize'],
                        "allowFiles" => $CONFIG['scrawlAllowFiles'],
                        "oriName" => "scrawl.png"
                );
                $fieldName = $CONFIG['scrawlFieldName'];
                $base64 = "base64";
                break;
            case 'uploadvideo':
                $config = array(
                        "pathFormat" => $CONFIG['videoPathFormat'],
                        "maxSize" => $CONFIG['videoMaxSize'],
                        "allowFiles" => $CONFIG['videoAllowFiles']
                );
                $fieldName = $CONFIG['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = array(
                        "pathFormat" => $CONFIG['filePathFormat'],
                        "maxSize" => $CONFIG['fileMaxSize'],
                        "allowFiles" => $CONFIG['fileAllowFiles']
                );
                $fieldName = $CONFIG['fileFieldName'];
                break;
        }

        /*print_r($config);
        exit;*/
        /* 生成上传实例对象并完成上传 */
        $up = new UploaderHelper($fieldName, $config, $base64);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */
        return json_encode($up->getFileInfo());

    }

}