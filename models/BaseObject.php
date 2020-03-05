<?php

namespace app\models;

use Yii;
use yii\db\mssql\QueryBuilder;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\db\ActiveRecord;

class BaseObject extends ActiveRecord
{
    public function rules()
    {
        return [
            [['createdby_id', 'updateby_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['createdby_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['createdby_id' => 'id']],
            [['updateby_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updateby_id' => 'id']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'createdby_id' => Yii::t('models', 'Createdby ID'),
            'updateby_id' => Yii::t('models', 'Updateby ID'),
            'created_at' => Yii::t('models', 'Created At'),
            'updated_at' => Yii::t('models', 'Updated At'),
        ];
    }

    public function getCreatedby()
    {
        return $this->hasOne(User::className(), ['id' => 'createdby_id']);
    }

    public function getUpdateby()
    {
        return $this->hasOne(User::className(), ['id' => 'updateby_id']);
    }
}
