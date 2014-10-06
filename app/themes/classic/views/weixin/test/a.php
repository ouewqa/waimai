<script src="/js/parabola.min.js"></script>
<header class="bar bar-nav">
    <h1 class="title">Title</h1>
</header>


<nav class="bar bar-tab">
    <a class="tab-item active" href="#">
        <span class="icon icon-home"></span>
        <span class="tab-label">Home</span>
    </a>
    <a class="tab-item" href="#">
        <span class="icon icon-person"></span>
        <span class="tab-label">Profile</span>
    </a>
    <a class="tab-item" href="#">
        <span class="icon icon-star-filled"></span>
        <span class="tab-label">Favorites</span>
    </a>
    <a class="tab-item" href="#" id="shopCart">
        <span class="icon icon-search"></span>
        <span class="tab-label">Search</span>
    </a>
    <a class="tab-item" href="#">
        <span class="icon icon-gear"></span>
        <span class="tab-label">Settings</span>
    </a>
</nav>


<div class="content">
    <ul class="table-view">
        <li class="table-view-cell">
            <img class="media-object pull-left" src="http://placehold.it/42x42" id="p1">

            <div class="media-body">
                Item 1
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                   et dolore. Lorem ipsum dolor sit amet.</p>
            </div>
            <button class="btn" data-id="1">Button</button>
        </li>
        <li class="table-view-cell">
            <img class="media-object pull-left" src="http://placehold.it/42x42" id="p2">

            <div class="media-body">
                Item 1
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                   et dolore. Lorem ipsum dolor sit amet.</p>
            </div>
            <button class="btn" data-id="2" btn-primary">Button</button>
        </li>
        <li class="table-view-cell">Item 3
            <button class="btn btn-positive">Button</button>
        </li>
        <li class="table-view-cell">Item 4
            <button class="btn btn-negative">Button</button>
        </li>
    </ul>
</div>


<script>

    var eleFlyElement, eleShopCart = document.querySelector("#shopCart");

    // 抛物线运动

    // 绑定点击事件
    if (eleShopCart) {
        $("body").delegate('.btn', 'click', function (button) {


            var id = $(this).attr('data-id');

            console.log(id);

            eleFlyElement = document.querySelector("#p" + id);

            var position = $("#p" + id).offset();

            console.log(position);

            //console.log(document.querySelector("#clone" + id));

            if (!document.querySelector("#clone" + id)) {
                var clone = $(eleFlyElement).clone(true).css({
                    position: 'absolute',
                    top: position.top,
                    left: position.left,
                    'border-radius': '50px'
                }).attr('id', 'clone' + id).appendTo($('body'));
            }

            var cloneElement = document.querySelector("#clone" + id);


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
</script>

</script>