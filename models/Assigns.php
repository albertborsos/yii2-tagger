<?php

namespace albertborsos\yii2tagger\models;

use albertborsos\yii2lib\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "ext_tagger_assigns".
 *
 * @property string $id
 * @property string $tag_id
 * @property string $model_class
 * @property integer $model_id
 * @property integer $created_at
 * @property integer $created_user
 * @property integer $updated_at
 * @property integer $updated_user
 * @property string $status
 *
 * @property Tags $tag
 */
class Assigns extends ActiveRecord
{
    const STATUS_ACTIVE   = 'a';
    const STATUS_INACTIVE = 'i';
    const STATUS_DELETED  = 'd';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ext_tagger_assigns';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'model_id'], 'trim'],
            [['tag_id', 'model_id'], 'default'],
            [['tag_id', 'model_id'], 'required'],
            [['tag_id', 'model_id', 'created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
            [['model_class'], 'string', 'max' => 160],
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
            'tag_id' => 'Cimke',
            'model_class' => 'Osztály',
            'model_id' => 'Példány',
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
    public function getTag()
    {
        return $this->hasOne(Tags::className(), ['id' => 'tag_id']);
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

}
