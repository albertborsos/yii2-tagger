<?php

namespace albertborsos\yii2tagger\components;

use albertborsos\yii2lib\helpers\Seo;
use albertborsos\yii2shop\models\Documents;
use albertborsos\yii2shop\models\Modes;
use albertborsos\yii2shop\models\Orders;
use albertborsos\yii2shop\models\PaymentModes;
use albertborsos\yii2shop\models\ShippingModes;
use albertborsos\yii2shop\models\ShippingRules;
use albertborsos\yii2tagger\models\Tags;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class DataProvider {

    const YES = 1;
    const NO = 0;

    public static function items($category, $id = null, $returnArray = true)
    {
        $array = array();
        switch ($category) {
            case 'status':
                $array = [
                    Tags::STATUS_ACTIVE   => 'Aktív',
                    Tags::STATUS_INACTIVE => 'Inaktív',
                    Tags::STATUS_DELETED  => 'Törölt',
                ];
                break;
            case 'noyes':
                $array = [
                    self::NO  => 'Nem',
                    self::YES => 'Igen',
                ];
                break;
            case 'yesno':
                $array = [
                    self::YES => 'Igen',
                    self::NO  => 'Nem',
                ];
                break;
            case 'sortorder':
                for($i = 1; $i <= 99; $i++){
                    $array[$i] = $i;
                }
                break;
        }
        if (is_null($id) && $returnArray) {
            return $array;
        } else {
            return isset($array[$id]) ? $array[$id] : $id;
        }
    }
}