<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>文件未找到</title>

    <style>
        html {
            height: 100%;
        }

        body {
            font-size: 13px;
            line-height: 150%;
            font-family: "Helvetica Neue", Helvetica, Arial, 'Microsoft YaHei', sans-serif;
            color: #666;
            margin: 0px;
            height: 100%;
            background: #f4f4f4;
        }

        .wrap {
            margin: 100px auto 0;
            text-align: center;
        }

        .wrap p {
            font-size: 18px;

        }

        .wrap p a {
            font-size: 14px;
        }

        blockquote {
            margin: 50px auto;
            font-size: 22px;
            line-height: 24px;
        }

    </style>
</head>
<body>
<div class="wrap">
    <img src="/images/404-logo.png" />

    <blockquote><?php echo $message; ?></blockquote>

    <p><a href="<?php echo $url; ?>">页面即将跳转，请等待<span class="jump_time" id="sec"><?php echo $time ?></span>秒！</a></p>
</div>

<script type="text/javascript">
    var handler;
    function run () {
        var s = document.getElementById("sec");
        if (s.innerHTML <= 0) {
            window.clearInterval(handler);
            <?php echo $go?>
            return false;
        }
        s.innerHTML = s.innerHTML * 1 - 1;
    }
    handler = window.setInterval("run();", 1000);
</script>
</body>
</html>
