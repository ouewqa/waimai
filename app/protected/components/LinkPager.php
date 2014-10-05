<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-8-8
 * Time: 下午2:26
 * To change this template use File | Settings | File Templates.
 */
class LinkPager extends CLinkPager
{

    public $itemOptions;

    public function init()
    {


        $this->selectedPageCssClass = 'active'; //当前页的class
        $this->hiddenPageCssClass = 'disabled'; //禁用页的class
        $this->header = ''; //分页前显示的内容
        $this->maxButtonCount = 4; //显示分页数量
        $this->htmlOptions = array();
        $this->itemOptions = array(
                'data-ignore' => 'push'
        );
        $this->firstPageLabel = '首页';
        $this->nextPageLabel = '»';
        $this->prevPageLabel = '«';
        $this->lastPageLabel = '末页';

        parent::init();
    }

    protected function createPageButton($label, $page, $class, $hidden, $selected)
    {
        if ($hidden || $selected)
            $class .= ' ' . ($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
        return '<li class="' . $class . '">' . CHtml::link($label, $this->createPageUrl($page), $this->itemOptions) . '</li>';
    }

    public function run()
    {
        $this->registerClientScript();
        $buttons = $this->createPageButtons();
        if (empty($buttons))
            return;
        echo $this->header;
        echo CHtml::tag('div', array('class' => 'pagination'), CHtml::tag('ul', $this->htmlOptions, implode("\n", $buttons)));
        echo $this->footer;
    }

    public static function registerCssFile($url=null)
    {
        /*if($url===null)
            $url=CHtml::asset(Yii::getPathOfAlias('system.web.widgets.pagers.pager').'.css');
        Yii::app()->getClientScript()->registerCssFile($url);*/
    }

} 