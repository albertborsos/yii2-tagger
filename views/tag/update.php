<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model albertborsos\yii2tagger\models\Tags */

?>
<div class="row">
<div class="col-md-6">
<div class="tags-update">

    <legend>Cimke módosítása</legend>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
