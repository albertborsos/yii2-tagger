<?php

namespace albertborsos\yii2tagger\models;

use albertborsos\yii2lib\db\ActiveRecord;
use albertborsos\yii2lib\wrappers\Select2;
use Yii;

/**
 * This is the model class for table "ext_tagger_tags".
 *
 * @property string $id
 * @property string $label
 * @property integer $created_at
 * @property integer $created_user
 * @property integer $updated_at
 * @property integer $updated_user
 * @property string $status
 *
 * @property Assigns[] $extTaggerAssigns
 */
class Tags extends ActiveRecord
{
    const STATUS_ACTIVE   = 'a';
    const STATUS_INACTIVE = 'i';
    const STATUS_DELETED  = 'd';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ext_tagger_tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['label'], 'string', 'max' => 160],
            [['status'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Cimke',
            'created_at' => 'Létrehozva',
            'created_user' => 'Létrehozta',
            'updated_at' => 'Módosítva',
            'updated_user' => 'Módosította',
            'status' => 'Státusz',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssigns()
    {
        return $this->hasMany(Assigns::className(), ['tag_id' => 'id']);
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()){
            return true;
        }else{
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)){
            $this->setOwnerAndTime();
            return true;
        }else{
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()){
            return true;
        }else{
            return false;
        }
    }

    public static function Widget(){
        return Select2::baseWidget('tagger', []);
    }

}
