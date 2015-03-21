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
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Új Cimke', ['create'], ['class' => 'btn btn-success']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Szűrések törlése', ['index'], ['class' => 'btn btn-info']),
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
                'attribute' => 'status',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    return $model->editable(ActiveRecord::EDITABLE_TYPE_DROPDOWN, 'status', DataProvider::items('status'));
                },
                'filter' => DataProvider::items('status'),
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
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return [$action, 'id' => $model->id];
                },
                //'dropdown' => true,
                'width' => '120px',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                            'class' => 'btn btn-sm btn-default'
                        ]);
                    },
                    'update' => function ($url, $model) {
                        if (Yii::$app->user->can('editor')) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('yii', 'Update'),
                                'data-pjax' => '0',
                                'class' => 'btn btn-sm btn-default'
                            ]);
                        } else {
                            return '';
                        }
                    },
                    'delete' => function ($url, $model) {
                        if (Yii::$app->user->can('editor')) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                                'class' => 'btn btn-sm btn-default'
                            ]);
                        } else {
                            return '';
                        }
                    },
                ],
            ],
        ],
    ]); ?>

</div>
