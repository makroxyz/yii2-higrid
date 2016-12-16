<?php
/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hal\tech\grid;

use yii\helpers\ArrayHelper;
use hal\tech\widgets\LinkSorter;
use hiqdev\assets\datatables\DataTablesAsset;
use hiqdev\assets\icheck\iCheckAsset;
use Yii;

/**
 * Class GridView.
 * HiPanel specific GridView.
 */
class GridView extends \hiqdev\higrid\GridView
{
    public $sorter = [
        'class' => LinkSorter::class,
    ];
    /**
     * {@inheritdoc}
     */
    public $resizableColumns = [
        'resizeFromBody' => false,
    ];
    /**
     * {@inheritdoc}
     */
//    public $tableOptions = [
//        'class' => 'table table-bordered table-hover dataTable'
//    ];
    /**
     * {@inheritdoc}
     */
    public $options = [
        'class' => 'dataTables_wrapper form-inline',
        'role' => 'grid',
    ];
    /**
     * {@inheritdoc}
     */
    public $layout = "<div class='dataTables_info'>{summary}</div>\n</div><div class='row'><div class='col-xs-12'>{sorter}</div></div><div class=\"table-responsive\">{items}</div>\n<div class='dataTables_paginate paging_bootstrap'>{pager}</div>";

    /**
     * {@inheritdoc}
     */
    protected static function defaultColumns()
    {
        return [
//            'seller_id' => [
//                'class' => SellerColumn::class,
//            ],
//            'client_id' => [
//                'class' => ClientColumn::class,
//            ],
            'checkbox' => [
                'class' => CheckboxColumn::class,
            ],
//            'seller' => [
//                'class' => SellerColumn::class,
//                'attribute' => 'seller_id',
//            ],
//            'client' => [
//                'class' => ClientColumn::class,
//                'attribute' => 'client_id',
//            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
//        $this->tableOptions['class'] .= ' ' . Yii::$app->themeManager->settings->getCssClass('table_condensed');
        $this->tableOptions['class'] .= ' ' . 'table_condensed';
        parent::run();
        $this->registerClientScript();
    }

    /**
     * {@inheritdoc}
     */
    public static function detailView(array $config = [])
    {
        $config = ArrayHelper::merge(['gridOptions' => ['resizableColumns' => ['resizeFromBody' => true]]], $config);
        return parent::detailView($config);
    }

    /**
     * {@inheritdoc}
     */
    private function registerClientScript()
    {
        $view = $this->getView();
        DataTablesAsset::register($view);
        iCheckAsset::register($view);
        $view->registerJs(<<<'JS'
$(function () {
    var checkAll = $('input.select-on-check-all');
    var checkboxes = $('input.icheck');
    $('input.icheck, input.select-on-check-all ').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
    checkAll.on('ifChecked ifUnchecked', function(event) {
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });
    checkboxes.on('ifChanged', function(event){
        if (checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.prop('checked', true);
        } else {
            checkAll.prop('checked', false);
        }
        checkAll.iCheck('update');
    });
});
JS
        , \yii\web\View::POS_READY);
    }
}
