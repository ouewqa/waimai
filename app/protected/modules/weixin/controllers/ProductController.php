<?php

class ProductController extends FrontController
{
    public function actionIndex($category_id = 0, $sort = 'recommend')
    {
        /*$products = ShopDish::model()->findOwnerProducts($this->account->id);


        $this->render('index', array(
                'products' => $products,
        ));*/

        $categories = ShopDishCategory::model()->findOwerCategory($this->account->id, true);
        if ($category_id) {
            $category = ShopDishCategory::model()->findByPk($category_id);
        } else {
            $category = null;
        }


        if ($sort == 'favorite') {

            $criteria = new CDbCriteria();
            $criteria->with = 'shopDish';
            $criteria->order = 't.dateline DESC';
            $criteria->condition = 'weixin_id=:weixin_id AND shopDish.status=:status';
            $criteria->params = array(
                    ':status' => 'Y',
                    ':weixin_id' => $this->weixin->id
            );


            $count = Favorite::model()->count($criteria);

            $pages = new CPagination($count);


            $pages->pageSize = 20;
            $pages->applyLimit($criteria);
            $products = Favorite::model()->findAll($criteria);


        } else {
            $criteria = new CDbCriteria();
            $criteria->with = 'shopDishCategory';
            $criteria->order = 't.dateline DESC';
            $criteria->condition = 't.status=:status AND t.weixin_account_id=:weixin_account_id';
            $criteria->params = array(
                    ':status' => 'Y',
                    ':weixin_account_id' => $this->account->id
            );

            if ($category_id) {
                $criteria->addCondition("shop_dish_category_id='{$category_id}'");
            }


            if ($sort) {
                switch ($sort) {
                    case 'views' :
                        $criteria->order = 't.count_views DESC';
                        break;
                    case 'price':
                        $criteria->order = 't.price ASC';
                        break;
                    case 'sales':
                        $criteria->order = 't.count_sales DESC';
                        break;
                    case 'recommend':
                        $criteria->order = 't.ob ASC, t.id DESC';
                        break;
                }
            }

            $count = ShopDish::model()->count($criteria);


            $pages = new CPagination($count);

            $pages->pageSize = 20;
            $pages->applyLimit($criteria);
            $products = ShopDish::model()->findAll($criteria);
        }


        $this->setPageTitle('菜单');
        $this->render('index', array(
                'sort' => $sort,
                'category_id' => $category_id,
                'category' => $category,
                'categories' => $categories,
                'products' => $products,
                'pages' => $pages,
        ));


    }

    public function actionView($id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            $product = ShopDish::model()->findByPk($id);

            $album = ShopDishAlbum::model()->findAll('shop_dish_id=:shop_dish_id', array(
                    ':shop_dish_id' => $product->id,
            ));
            if (!$product) {
                throw new CHttpException(404, '不存在');
            } else {
                $product->count_views += 1;
                $product->save();
            }

            #是否已收藏
            $favorite = Favorite::model()->findByPk(array(
                    'weixin_id' => $this->weixin->id,
                    'shop_dish_id' => $product->id,
            ));


            $html = $this->renderPartial('view', array('product' => $product, 'album' => $album, 'favorite' => $favorite), true);
            $this->out($html);
        }
    }

    public function actionGetShoppingCartItems($ids)
    {
        if (Yii::app()->request->isAjaxRequest && $ids) {

            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', array_filter(explode(',', $ids)));
            $products = ShopDish::model()->findAll($criteria);

            $cookieCart = CookieHelper::get('cart');

            $cartitems = explode('|', $cookieCart);


            $cart = array();
            foreach ($cartitems as $key => $value) {
                list($id, $number, $price) = explode(',', $value);
                $cart["$id"] = $number;
            }

            //print_r($cart);exit;
            $output = $this->renderPartial('shoppingCartItems', array(
                    'products' => $products,
                    'cart' => $cart,
            ), true);

            echo $output;

        }
    }

    public function actionOrder()
    {
        $cookieCart = CookieHelper::get('cart');
        $cartitemsFromCookie = array_filter(explode('|', $cookieCart));
        /*echo $cookieCart, PHP_EOL;
        print_r($cartitems);
        echo PHP_EOL;
        echo count($cartitems), PHP_EOL;
        exit('~~~');*/
        if (count($cartitemsFromCookie) <= 0) {
            $this->redirect(Yii::app()->request->urlReferrer);
        }

        $model = new ShopOrder();
        $model->weixin_id = $this->weixin->id;
        $model->shop_id = $this->shop->id;


        if (isset($_POST['ShopOrder'])) {

            $model->attributes = $_POST['ShopOrder'];
            $model->status = 0;

            $transaction = Yii::app()->db->beginTransaction();
            try {

                if ($model->save()) {
                    #保存订单项
                    $ids = $numbers = $printData = array();

                    $address = UsedAddresses::model()->findByPk($model->used_addresses_id);
                    if (!$address) {
                        throw new CHttpException(404, '不存在这个地址');
                    } else {
                        #保存地址使用时间
                        $address->dateline = time();
                        $address->save();
                    }


                    /*$printData = array(
                            'name' => $this->shop->name,
                            'comment' => $model->comment .
                                    "<BR>用户ID：" . $this->weixin->id .
                                    "<BR>送餐时间：" .
                                    $model->delivery_time .
                                    "<BR>付款方式：" . $model->paymentMethod->name,
                            'address' => $address->district . $address->address . $address->realname,
                            'mobile' => $address->mobile,
                            'dateline' => date('Y-m-d H:i:s'),
                            'qrcode' => '',
                    );

                    $printData['dateline'] .= '<BR>--------------------------------<BR>本店地址：' .
                            $this->shop->address .
                            '<BR>本店电话：' . $this->shop->telephone .
                            '<BR>--------------------------------';*/


                    foreach ($cartitemsFromCookie as $value) {

                        $data = explode(',', $value);
                        $item = new ShopOrderItem();
                        $item->setAttributes(array(
                                'shop_order_id' => $model->id,
                                'shop_dish_id' => $data[0],
                                'number' => $data[1],
                                'price' => $data[2],
                        ));

                        if (!$item->save()) {
                            throw new CHttpException(500, '订单项保存失败');
                        }
                        $ids[] = intval($data[0]);
                        $numbers[] = intval($data[1]);

                        #打印数据,从COOKIE中获取，存在安全隐患
                        /*$printData['items'][] = array(
                                'name' => $data[3],
                                'price' => $data[2],
                                'number' => $data[1],
                                'money' => intval($data[1]) * $data[2],
                        );*/

                    }


                    //更新产品卖出数量，遍历所有商品，统计订单金额
                    $money = 0;
                    foreach ($ids as $key => $value) {
                        $shopDish = ShopDish::model()->findByPk($value);
                        if ($shopDish) {
                            if (!$model->image) {
                                $model->image = $shopDish->image;
                            }
                            $shopDish->count_sales += 1;
                            $shopDish->save();
                            $money += $shopDish->price * $numbers[$key];
                        }

                        #todo 打印数据从COOKIE中获取，有安全性问题
                    }

                    if ($this->shop->minimum_charge && $this->shop->express_fee && $money < $this->shop->minimum_charge) {
                        $money += $this->shop->express_fee;
                    }


                    #订单价格
                    $model->money = $money;

                    #如果订单价格小于送餐最低价格，需要加配送费

                    /*if ($money < $this->shop->minimum_charge) {
                        $printData['items'][] = array(
                                'name' => '送餐费',
                                'price' => $this->shop->express_fee,
                                'number' => 1,
                                'money' => $this->shop->express_fee,
                        );
                    }*/


                    #订单二维码
                    try {
                        #生成二维码的场景ID
                        if ($this->account->advanced_interface == 'Y' && $qrcode = WeixinQrcode::model()->getNewSceneId($this->account)) {
                            if ($qrcode->scene_id > 100000) {
                                $qrcode->scene_id = $qrcode->scene_id % 100000;
                            }

                            $model->scene_id = $qrcode->scene_id;

                            #打印机中二维码
                            /*$printData['dateline'] .= '<BR>扫描以下二维码，即可确认收货。';
                            $printData['qrcode'] = $qrcode->path;*/

                        } else {
                            $qrcode->scene_id = null;
                        }
                    } catch (Exception $e) {
                        /*$printData['qrcode'] = '';*/
                    }


                    /*$printData['money'] = $model->money . ' 元';*/


                    #将打印数据保存起来
                    #Yii::app()->cache->set('printData_' . $model->id, $printData, 3600 * 8);

                    #判断支付方式
                    if ($model->paymentMethod->sign == 'normal') {
                        $model->paid = 'N';
                    }

                    $model->save();
                    $transaction->commit();

                    #清除COOKIE
                    CookieHelper::del('cart');

                    #如果不是货到付款，跳转到支付环节。
                    if ($model->paymentMethod->sign == 'normal') {
                        $this->orderPrint($model);
                        $this->redirect(array('profile/orderView', 'id' => $model->id));
                    } else {
                        $this->redirect(array('product/payment', 'id' => $model->id));
                    }
                }


            } catch (Exception $e) {
                $transaction->rollBack();
                /*print_r($e->getMessage());
                exit;*/
                #$this->redirect(array('product/order'));
            }
        }


        $addresses = UsedAddresses::model()->findOwnerAddress($this->weixin->id);
        $payments = PaymentConfig::model()->findOwnerPayments($this->account->id);

        //$this->checkOrderCondition();


        $this->setPageTitle('订单详情');

        $this->render('order', array(
                'model' => $model,
                'addresses' => $addresses,
                'payments' => $payments,
        ));
    }


    /**
     * 验证条件
     */
    public function checkOrderCondition()
    {
        if ($this->account->need_mobile_verify == 'Y' && $this->weixin->mobile_is_verify == 'N') {
            Yii::app()->user->setReturnUrl(Yii::app()->request->url);
            #验证手机号码
            $this->warning('你必须先验证手机号码！', array('profile/mobileVerify'), 3);
        }
    }

    /**
     * 收藏与取消收藏
     * @param $id
     */
    public function actionFavorite($id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = intval($id);
            $model = Favorite::model()->findByPk(array(
                    'weixin_id' => $this->weixin->id,
                    'shop_dish_id' => $id,
            ));

            if (!$model) {
                $model = new Favorite();
                $model->setAttributes(array(
                        'weixin_id' => $this->weixin->id,
                        'shop_dish_id' => $id,
                        'dateline' => DATELINE,
                ));
                if ($model->save()) {
                    $result = array(
                            'status' => true,
                            'msg' => '收藏成功',
                            'class' => 'icon-star-filled',
                    );
                } else {
                    $result = array(
                            'status' => false,
                            'msg' => '收藏失败',
                    );
                }
            } else {
                if ($model->delete()) {
                    $result = array(
                            'status' => true,
                            'msg' => '取消收藏成功',
                            'class' => 'icon-star',
                    );
                } else {
                    $result = array(
                            'status' => false,
                            'msg' => '取消收藏失败',
                    );
                }
            }

            $this->jsonout($result);
        }
    }

    public function actionOrderProcess()
    {

    }

    /**
     * 打印数据
     * @param ShopOrder $model
     */
    public function orderPrint(ShopOrder $model)
    {

        #$printData = Yii::app()->cache->get('printData_' . $model->id);
        #订单通知　todo
        try {

            $pusher = new PushHelper($this->shop);
            if ($pusher) {
                $data = $pusher->buildData($model);
                $pusher->send($data);

                #删除缓存
                #Yii::app()->cache->delete('printData_' . $model->id);

                #订单状态已受理
                $model->status = 1;
                $model->save();
            }
        } catch (Exception $e) {

        }


    }

    /**
     * 支付
     */
    public function actionPayment($id)
    {
        /*$shopOrder = ShopOrder::model()->with('paymentMethod')->findByPk($id);
        $payment = $shopOrder->paymentMethod;

        switch ($payment->sign) {
            case 'alipay':
                $payment_url = AlipayHelper::getRequestURL($shopOrder->id, $shopOrder->money, '外卖', '');
                #支付流程
                echo $payment_url;
                break;

            case 'tenpay':
                $payment_url = TenpayHelper::getRequestURL($model->order_sn, $model->siteProduct->price, $model->siteProduct->name, $model->siteProduct->description);
                #支付流程
                $this->redirect($payment_url);
                break;
        }*/
    }
}