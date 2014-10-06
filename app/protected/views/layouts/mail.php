<html>
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
</head>
<table cellspacing="0" cellpadding="10" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
    <tbody>
    <tr>
        <td style="color:#4D90FE;font-size:22px;border-bottom: 2px solid #4D90FE;">
            <?php echo CHtml::encode($data['title']); ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $data['content'] ?>
        </td>
    </tr>
    <tr>
        <td style="padding:15px 20px;text-align:right;padding-top:5px;border-top:solid 1px #dfdfdf">
            <p>本邮件为系统通知，请勿回复本邮件，有问题请联系：admin@bo-u.cn。</p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>