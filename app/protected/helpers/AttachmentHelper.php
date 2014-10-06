<?php

class AttachmentHelper
{

    public static $allow_ext = array(
            'jpg', 'jpeg', 'png', 'gif', 'mp3', 'mp4'
    );

    /**
     * @param $model
     * @param $field
     * @throws CHttpException
     * @internal param null $oldValue如果是更新且没有新文件上传 ，需要将旧值传递进来
     * @return string
     */
    public static function upload($model, $field)
    {
        if (isset($_POST[$field . '2']) && $_POST[$field . '2']) {
            $oldFile = $_POST[$field . '2'];
        } else {
            $oldFile = null;
        }
        $instance = CUploadedFile::getInstance($model, $field);
        if ($instance) {
            if (!in_array($instance->extensionName, self::$allow_ext)) {
                throw new CHttpException(500, '上传的文件格式：' . $instance->extensionName . '不支持。');
            }

            $monthDir = date('Ymd');

            #$modelName = $model->tableName();


            $attachmemntPath = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . Yii::app()->user->id . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $monthDir . DIRECTORY_SEPARATOR;

            $absolutePath = dirname(Yii::app()->BasePath) . $attachmemntPath;


            if (!is_dir($absolutePath) || !file_exists($absolutePath)) {
                mkdir($absolutePath, 0777, true);
            } else if (!is_writable($absolutePath)) {
                chmod($absolutePath, 0777);
            }

            $newFile = time() . substr(md5(rand(1, 9999)), 0, 6) . '.' . $instance->extensionName;

            $instance->saveAs($absolutePath . $newFile);
            $path = $attachmemntPath . $newFile;

            #删除旧的图片
            if ($oldFile) {
                self::delete($oldFile);
            }


        } else {
            $path = $oldFile ? $oldFile : null;
        }

        $path = str_replace("\\", '/', $path);
        return $path;
    }

    public static function delete($path)
    {
        if ($path) {
            $filePath = dirname(Yii::app()->BasePath) . $path;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

}