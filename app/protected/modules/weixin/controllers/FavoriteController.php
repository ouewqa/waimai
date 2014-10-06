<?php

class FavoriteController extends FrontController
{
    public function actionIndex($sort = 'dateline')
    {


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

        if ($sort) {
            switch ($sort) {
                case 'dateline' :
                    $criteria->order = 't.dateline DESC';
                    break;
                case 'price':
                    $criteria->order = 'shopDish.price ASC';
                    break;
                case 'sales':
                    $criteria->order = 'shopDish.count_sales DESC';
                    break;
            }
        }


        $pages->pageSize = 20;
        $pages->applyLimit($criteria);
        $products = Favorite::model()->findAll($criteria);

        $this->setPageTitle('我收藏的菜品');

        $this->render('index', array(
                'sort' => $sort,
                'products' => $products,
                'pages' => $pages,
        ));


    }

    // Uncomment the following methods and override them if needed
    /*
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'inlineFilterName',
            array(
                'class'=>'path.to.FilterClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }

    public function actions()
    {
        // return external action classes, e.g.:
        return array(
            'action1'=>'path.to.ActionClass',
            'action2'=>array(
                'class'=>'path.to.AnotherActionClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }
    */
}