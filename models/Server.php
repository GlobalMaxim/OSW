<?php

namespace app\models;

use Yii;

class Server extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'server';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'protocol', 'domain', 'path', 'verification_code'], 'string', 'max' => 255],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => ServerStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('models', 'Name'),
            'protocol' => Yii::t('models', 'Protocol'),
            'domain' => Yii::t('models', 'Domain'),
            'path' => Yii::t('models', 'Path'),
            'verification_code' => Yii::t('models', 'Verification Code'),
            'status_id' => Yii::t('models', 'Status ID'),
        ]);
    }

    public function getStatus()
    {
        return $this->hasOne(ServerStatus::className(), ['id' => 'status_id']);
    }

    public function getTests()
    {
        return $this->hasMany(Test::className(), ['server_id' => 'id']);
    }
}
