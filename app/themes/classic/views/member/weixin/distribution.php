<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo $baidu_ak; ?>"></script>

<style type="text/css">
    #allmap {
        position: fixed !important;;
        top: 41px;
        left: 201px;
        right: 0px;
        bottom: 0;
        z-index: 1000;
    }

    #l-map {
        height: 100%;
        width: 78%;
        float: left;
        border-right: 2px solid #bcbcbc;
    }

    #r-result {
        height: 100%;
        width: 20%;
        float: left;
    }
</style>

<div id="allmap"></div>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'messageModal')); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>请输入你的纸条内容...</h4>
</div>

<div class="modal-body">
    <?php echo CHtml::hiddenField('from_weixin_id', '1', array('id' => 'from_weixin_id')); ?>
    <?php echo CHtml::hiddenField('to_weixin_id', '', array('id' => 'to_weixin_id')); ?>
    <?php
    echo CHtml::textArea('answer', '', array(
            'id' => 'note',
            'rows' => 8,
            'class' => 'span3',
    ));
    ?>
</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'ajaxButton',
            'type' => 'primary',
            'label' => '提交',
            'ajaxOptions' => array(
                    'success' => 'js:modalCallback',
                    'dataType' => 'JSON',
                    'type' => 'POST',
                    'data' => 'js:"message=" + $("#note").val() + "&to_weixin_id=" + $("#to_weixin_id").val()',
            ),
            'url' => $this->createUrl('/weixin/default/sendNotification'),
            'htmlOptions' => array('data-dismiss' => 'modal'),
    )); ?>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">

    function modalCallback (data) {
        console.log(data);
        if (data) {
            alert(data.msg);
        }
    }



    // 百度地图API功能
    var map = new BMap.Map("allmap");

    <!--创建一个点，并居中-->
    var point = new BMap.Point(116.403, 39.915);
    map.centerAndZoom(point, 11);

    map.addControl(new BMap.NavigationControl());//添加默认缩放平移控件
    map.addControl(new BMap.MapTypeControl({mapTypes: [BMAP_NORMAL_MAP, BMAP_HYBRID_MAP]}));//2D图，卫星图
    map.addControl(new BMap.OverviewMapControl());//添加默认缩略地图控件

    map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用

    var markers = [];

    var i, total;
    total = markers.length;
    for (i = 0; i < total; i++) {
        marker(i);
    }


    //获取数据
    var data = eval('<?php echo $data;?>');
    var length = data.length;

    //console.log(data, length);

    for (i = 0; i < length; i++) {
        console.log(data[i]);
        var p = data[i]
        var point = new BMap.Point(p.longitude, p.latitude);
        marker(i, point);
        if (i === 0) map.centerAndZoom(point, 11);
    }


    function marker (i, point) {
        var marker = new BMap.Marker(point, {icon: new BMap.Icon(data[i].headimgurl, new BMap.Size(50, 50))});   // 创建标注
        map.addOverlay(marker);              // 将标注添加到地图中
        //marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画

        // 百度地图API功能
        var sContent =
                '<h4 style="margin:0 0 5px 0;padding:0.2em 0">' + data[i].nickname + '　'　+　data[i].dateline　+'</h4>' +
                '<img style="float:right;margin:4px" id="imgDemo" src="' + data[i].headimgurl + '" width="70" height="70" class="img-circle" />' +
                '<p style="margin:0;line-height:1.5;font-size:13px;">' +
                '性别：' + data[i].sex + '<br />' +
                '老家：' + data[i].city + '<br />' +
            //'QQ：' + data[i].qq + '<br />' +
            //'微信号：' + data[i].weixin_account + '<br />' +
                '身份：' + data[i].identity + '<br />' +
                '日语水平：' + data[i].jp_level + '<br />' +
                '</p><p></p>' +
                '<button class="btn btn-info btn-block" type="button" onclick="showMessage(' + data[i].weixin_id + ');">给Ta小纸条</button>'
        '</div>';

        var infoWindow = new BMap.InfoWindow(sContent);  //创建信息窗口对象

        marker.addEventListener("click", function () {
            //alert(i);
            this.openInfoWindow(infoWindow);
            //图片加载完毕重绘infowindow
            document.getElementById('imgDemo').onload = function () {
                infoWindow.redraw(); //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
            }
        });

    }

    function showMessage (weixin_id) {
        $('#to_weixin_id').val(weixin_id);
        $('#messageModal').modal({
            show: true
        })
    }

    $(document).ready(function () {
        $('.btn-block').live('click', function () {
            alert('234');
        });
    })

</script>