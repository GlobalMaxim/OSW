<?php

namespace app\models;

use Yii;

class TestRequest extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'test_request';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['body'], 'string'],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::className(), 'targetAttribute' => ['test_id' => 'id']],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::className(), 'targetAttribute' => ['test_id' => 'id']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'body' => Yii::t('models', 'Body'),
            'test_id' => Yii::t('models', 'Test ID'),
        ]);
    }

    public function getTest()
    {
        return $this->hasOne(Test::className(), ['id' => 'test_id']);
    }

    public function getTestRequestHeaders()
    {
        return $this->hasMany(TestRequestHeader::className(), ['requestid' => 'id']);
    }

    public function getTestResponses()
    {
        return $this->hasMany(TestResponse::className(), ['request_id' => 'id']);
    }

}
