<?php

namespace albertborsos\yii2tagger;

use albertborsos\yii2lib\helpers\S;
use albertborsos\yii2shop\models\Categories;
use albertborsos\yii2shop\models\CodeCatalog;
use albertborsos\yii2shop\models\Documents;
use albertborsos\yii2shop\models\Orders;
use albertborsos\yii2shop\models\Products;
use rmrevin\yii\fontawesome\FA;
use yii\base\Action;
use yii\base\ActionEvent;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;
use Yii;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'albertborsos\yii2tagger\controllers';
    public $name = 'Cimkéző';

    /**
     * Module specific urlManager
     * @param $app
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            $this->id . '/<controller:\w+>/<action:\w+>' => $this->id . '/<controller>/<action>',
        ], false);
    }

}
