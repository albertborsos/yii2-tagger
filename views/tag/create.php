<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model albertborsos\yii2tagger\models\Tags */

?>
<div class="row">
<div class="col-md-6">
<div class="tags-create">

    <legend>Új cimke létrehozása</legend>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
