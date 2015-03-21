<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use albertborsos\yii2tagger\components\DataProvider;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\TimePicker;
use \vova07\imperavi\Widget as Redactor;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2tagger\models\Tags */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tags-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => '{label}<div class="col-md-8">{input}</div><div class="col-md-8 col-md-offset-4">{error}</div>',
            'labelOptions' => ['class' => 'col-md-4 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => 160]) ?>

      <?= $form->field($model, 'status')->dropDownList(DataProvider::items('status')) ?>

    <div class="form-group">
        <div class="col-md-8 col-md-offset-4">
            <?= Html::submitButton($model->isNewRecord ? 'Létrehoz' : 'Módosít', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
