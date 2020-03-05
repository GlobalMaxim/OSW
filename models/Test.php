<?php

namespace app\models;

use Yii;

class Test extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'test';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['request_endpoint'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['server_id'], 'exist', 'skipOnError' => true, 'targetClass' => Server::className(), 'targetAttribute' => ['server_id' => 'id']],
            [['method'], 'exist', 'skipOnError' => true, 'targetClass' => HttpMethod::className(), 'targetAttribute' => ['method' => 'id']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('models', 'Name'),
            'server_id' => Yii::t('models', 'Server ID'),
            'request_endpoint' => Yii::t('models', 'Request Endpoint'),
            'method' => Yii::t('models', 'Method'),
        ]);
    }

    public function getServer()
    {
        return $this->hasOne(Server::className(), ['id' => 'server_id']);
    }

    public function getHttpMethod()
    {
        return $this->hasOne(HttpMethod::className(), ['id' => 'method']);
    }

    public function getTestRequests()
    {
        return $this->hasMany(TestRequest::className(), ['test_id' => 'id']);
    }

}
