/**
 * Created by Administrator on 14-8-8.
 */

function Cart (config) {
    if (!window.clientInformation.cookieEnabled) {
        alert('你的浏览器不支持Cookie无法使用此 购物车 系统');
        return false;
    }

    this.config = config;
    this.total = 0;

    //初始化数组
    this.init = function () {
        this.items = new Array();
        var cart = WX.cookie.get('cart');
        if (cart) {
            var items, item;
            items = cart.split('|');
            for (var i = 0; i < items.length; i++) {
                item = items[i].split(',');
                this.items.push(new this.item(item[0], item[1], item[2], item[3], item[4]));
            }
        }
        //统计总数
        this.countItems();
        this.makeup();
    }

    //所有子项
    //this.items = new Array();

    //每个子项结构
    this.item = function (id, number, price, name, image) {
        this.id = id;
        this.number = number;
        this.price = price;
        this.name = name;
        this.image = image;
    }

    //统计数量
    this.countItems = function () {

        var total = 0, money = 0;
        for (var i = 0; i < this.items.length; i++) {
            if (this.items[i].id > 0) {
                total += parseInt(this.items[i].number);
                money += parseInt(this.items[i].number) * this.items[i].price;
            }
        }
        //console.log('统计数量', total);
        this.total = total;
        this.money = money;
    }


    /**
     * 获取指定名称的数组下标，-1为不存在
     * @param id
     */
    this.getPoint = function (id) {

        var point = -1, j, obj = this.items;

        for (j = 0; j < obj.length; j++) {
            if (obj[j].id == id) {
                point = j;
                break;
            }
        }

        return point;
    }

    this.getIds = function () {
        var ids = new Array();
        for (var i = 0; i < this.items.length; i++) {
            ids.push(this.items[i].id);
        }
        return ids.join(',');
    }


    //格式化保存起来
    this.format = function () {
        var string = new Array(),
            total = 0, money = 0;
        for (var i = 0; i < this.items.length; i++) {
            if (this.items[i].id > 0) {
                var id = this.items[i].id;
                var number = this.items[i].number;
                var price = this.items[i].price;
                var name = this.items[i].name;
                var image = this.items[i].image;
                string.push(id + ',' + number + ',' + price + ',' + name + ',' + image);

                total += parseInt(number);
                money += parseInt(number) * price;
            }
        }
        this.total = total;
        this.money = money;

        /*console.log('总数', this.total);
         console.log('总价', this.money);
         console.log('格式化前的数组', string);*/

        WX.cookie.set('cart', string.join('|'));

        this.makeup();
    }

    //加
    this.add = function (id, price, name, image) {
        var point = this.getPoint(id);

        //判断数量
        if (this.items.length >= 50) {
            alert('你的购买车已经满了，请先结算。');
            return false;
        }
        //不存在，添加，否则更新数量
        if (point == -1) {
            this.items.push(new this.item(id, 1, price, name, image));
        } else {
            this.items[point].number = parseInt(this.items[point].number) + 1;
        }

        this.format();

    }

    //减
    this.sub = function (id) {
        var point = this.getPoint(id);
        //不存在，跳过，存在，数量减一
        if (point == -1) {
        } else {
            this.items[point].number -= 1;
            if (this.items[point].number <= 0) {
                this.items[point] = new Array();
            }
        }

        this.format();
    }

    /**
     * 清空
     */
    this.clean = function () {
        this.items = new Array();
        this.format();
    }

    //输入
    this.makeup = function () {
        var html;
        if (this.items.length > 0 && this.money > 0) {
            html = '\
        <ul class="table-view">\
            <li class="table-view-cell table-view-divider">当前你已选择以下菜品</li>\
        </ul>\
        <table id="table-cart">\
        <thead>\
         <tr>\
            <th>名称</th>\
            <th>数量</th>\
            <th></th>\
        </tr>\
        </thead>\
        <tbody>\
        ';


            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].id > 0) {
                    html += '\
            <tr>\
                <td>' + this.items[i].name + '</td>\
                <td>' + this.items[i].number + '×' + this.items[i].price + '</td>\
                <td>\
                \<button class="btn btn-negative btn_sub" onclick="cart.sub(' + this.items[i].id + ');"><strong>-</strong></button>\
                \<button class="btn btn-positive btn_add" onclick="cart.add(' + this.items[i].id + ');"><strong>+</strong></button>\
                 </td>\
            </tr>\
            ';
                }

            }

            if (this.config.minimum_charge && this.config.express_fee && this.money.toFixed(2) < this.config.minimum_charge
                ) {
                html += '<tr>\
                        <td>送餐费</td>\
                        <td>（' + this.config.minimum_charge + '元起免）</td>\
                        <td>¥ ' + this.config.express_fee.toFixed(2) + '</td>\
                    </tr>';
                this.money += this.config.express_fee;
            }

            html += '<tfoot>\
                    <tr>\
                        <td>共计：</td>\
                        <td>' + this.total + '份</td>\
                        <td>¥ ' + this.money.toFixed(2) + '</td>\
                    </tr>\
                </tfoot>\
                </tbody>\
            </table>';

            var myDate = new Date();
            var hour = myDate.getHours();

            if (hour >= parseInt(this.config.business_hours.open) && hour < parseInt(this.config.business_hours.close)) {
                if (this.items.length > 0 && this.money > 0) {
                    html += '<div class="content-padded">\
                            <a class="btn btn-primary btn-block" href="/weixin/product/order" data-ignore="push">下一步</a>\
                        </div>';
                }
            } else if (hour < parseInt(this.config.business_hours.open)) {
                html += '<div class="content-padded">\
                            <span class="btn btn-block">还未到营业时间 ' + this.config.business_hours.open + '</span>\
                        </div>';
            } else if (hour > parseInt(this.config.business_hours.close)) {
                html += '<div class="content-padded">\
                            <span class="btn btn-block">已经过了营业时间 ' + this.config.business_hours.close + '</span>\
                        </div>';
            } else {
                html += '<div class="content-padded">\
                            <span class="btn btn-block">营业时间 ' + this.config.business_hours.open + '~' + this.config.business_hours.close + '</span>\
                        </div>';
            }
        } else {
            html = '<div class="ajaxTips">当前购物车为空</div>';
        }


        var obj = document.querySelector('#shoppingContent');

        if (obj) {
            obj.innerHTML = html;
        }

        var obj = document.querySelector('#shoppingCart_count');
        if (obj) {
            obj.innerHTML = this.total == 0 ? '' : this.total;
        }
    }
}