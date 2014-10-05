var WX = {
    cookie: {
        set: function (key, value, duration) {
            key = WX.cookie.addExt(key);
            WX.cookie.delete(key);
            var d = new Date();
            if (duration <= 0)
                duration = 7;
            d.setTime(d.getTime() + 1000 * 60 * 60 * 24 * duration);
            document.cookie = key + "=" + encodeURI(value) + "; expires=" + d.toGMTString() + ";path=/";
        },
        delete: function (key) {
            key = WX.cookie.addExt(key);
            var d = new Date();
            if (WX.cookie.get(key) != "") {
                d.setTime(d.getTime() - (86400 * 1000 * 1));
                document.cookie = key + "=;expires=" + d.toGMTString();
            }
        },
        get: function (key) {
            key = WX.cookie.addExt(key);
            var arr = document.cookie.match(new RegExp("(^| )" + key + "=([^;]*)(;|$)"));
            if (arr != null)
                return decodeURIComponent(arr[2]);
            return "";
        },
        addExt: function (key) {
            return 'WX_' + key;
        }
    },
    action: {

    }
}


$(document).ready(function () {

    /*效果*/

    var eleFlyElement, eleShopCart = document.querySelector("#shoppingCart_count");

    // 抛物线运动

    // 绑定点击事件
    if (eleShopCart) {
        $("body").delegate('.btn_order', 'click', function (button) {

            //alert('df');

            var id = $(this).attr('data-id');

            //console.log(id);

            eleFlyElement = $("#productImage_" + id);

            //console.log(eleFlyElement);


            var position = eleFlyElement.offset();

            //console.log(position);

            //console.log(document.querySelector("#clone" + id));

            if (!document.querySelector("#clone" + id)) {
                var clone = $(eleFlyElement).clone(false).css({
                    position: 'absolute',
                    top: position.top,
                    left: position.left
                    //'border-radius': '50px'
                }).attr('id', 'clone_' + id).appendTo($('body'));
            }

            var cloneElement = document.querySelector("#clone_" + id);


            var myParabola = funParabola(cloneElement, eleShopCart, {
                speed: 80,
                curvature: 0.001,
                complete: function () {
                    cloneElement.remove();
                }
            });

            // 需要重定位
            myParabola.position().move();

        });
    }


    /*功能按钮*/
    $("body").delegate("a", "click", function (event) {
        var that = event.target,
            id = $(that).attr('id');

        switch (id) {
            case 'btn_complete_order':
                if (confirm('您已收到外卖了么？')) {
                    var id = $(this).attr('data-id');
                    $.post("/weixin/profile/setStatus", { "id": id, "status": "20" },
                        function (data) {
                            if (data.status) {
                                location.reload();
                            } else {
                                alert(data.msg);
                            }
                        }, "json");
                }
                break;
            case 'btn_cancel_order':
                if (confirm('您确认要取消订单么？')) {
                    var id = $(this).attr('data-id');
                    $.post("/weixin/profile/setStatus", { "id": id, "status": "2" },
                        function (data) {
                            if (data.status) {
                                location.reload();
                            } else {
                                alert(data.msg);
                            }
                        }, "json");
                }

                break;

            case 'btn_cuidan':
                var id = $(this).attr('data-id');
                $.get("<?php echo $this->createUrl('cuidan');?>", { "id": id},
                    function (data) {
                        if (data.status) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }, "json");
                break;
        }
    });
})


