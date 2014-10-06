
<?php


echo CHtml::image(ImageHelper::thumb('/attachments/user/4/images/20140811/1407726347d0b9a6.jpg', 60, 60));


$params = array(
        'total' => 5,
        'group' => 'member',
);

if (Yii::app()->user->checkAccess('member.default.test', $params)) {
    echo '通过';
} else {
    echo '失败';
}


?>
