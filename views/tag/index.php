<?php

use albertborsos\yii2lib\db\ActiveRecord;
use yii\helpers\Html;
use kartik\grid\GridView;
use albertborsos\yii2tagger\components\DataProvider;

/* @var $this yii\web\View */
/* @var $searchModel albertborsos\yii2tagger\models\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="tags-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Cimkék</h3>',
            'type' => 'default',
            'showFooter' => false
        ],
        'floatHeader' => true,
        'export' => false, // [], ha exportálni szeretnél
        'exportConfig' => [
            GridView::CSV => [
                'label' => 'CSV',
                'icon' => 'floppy-open',
                'showHeader' => true,
                'showPageSummary' => true,
                'showFooter' => true,
                'showCaption' => true,
                'colDelimiter' => ",",
                'rowDelimiter' => "\r\n",
                'filename' => 'grid-export',
                'alertMsg' => 'The CSV export file will be generated for download.',
                'options' => ['title' => 'Mentés CSV-ként']
            ],
            GridView::EXCEL => [
                'label' => 'Excel',
                'icon' => 'floppy-remove',
                'showHeader' => true,
                'showPageSummary' => true,
                'showFooter' => true,
                'showCaption' => true,
                'worksheet' => 'ExportWorksheet',
                'filename' => 'grid-export',
                'alertMsg' => 'The EXCEL export file will be generated for download.',
                'cssFile' => '',
                'options' => ['title' => 'Mentés XLS-ként']
            ],
        ],
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'sort_order',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return $model->editable(ActiveRecord::EDITABLE_TYPE_DROPDOWN, 'sort_order', DataProvider::items('sortorder'));
                },
                'filter' => false,
            ],
            [
                'attribute' => 'label',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return $model->editable(ActiveRecord::EDITABLE_TYPE_TEXTINPUT, 'label');
                },
            ],
            [
                'attribute' => 'updated_at',
                //'header'      => 'Utolsó módosítás',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'format' => 'raw',
                'headerOptions' => ['class' => 'text-center'],
                'value' => function ($model, $index, $widget) {
                    return \albertborsos\yii2lib\db\ActiveRecord::showLastModifiedInfo($model);
                },
            ],
        ],
    ]); ?>

</div>
