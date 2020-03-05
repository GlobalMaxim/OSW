<?php

namespace app\models;

use Yii;

class TestResponse extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'test_response';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['duration'], 'number'],
            [['body', 'cookies'], 'string'],
            [['response_code'], 'string', 'max' => 63],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestRequest::className(), 'targetAttribute' => ['request_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => ResponseStatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['job_id'], 'exist', 'skipOnError' => true, 'targetClass' => Job::className(), 'targetAttribute' => ['job_id' => 'id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestRequest::className(), 'targetAttribute' => ['request_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => ResponseStatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['job_id'], 'exist', 'skipOnError' => true, 'targetClass' => Job::className(), 'targetAttribute' => ['job_id' => 'id']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'response_code' => Yii::t('models', 'Response Code'),
            'status' => Yii::t('models', 'Status'),
            'duration' => Yii::t('models', 'Duration'),
            'body' => Yii::t('models', 'Body'),
            'cookies' => Yii::t('models', 'Cookies'),
            'length' => Yii::t('models', 'Length'),
            'job_id' => Yii::t('models', 'Job ID'),
            'request_id' => Yii::t('models', 'Request ID'),
        ]);
    }

    public function getTestRequest()
    {
        return $this->hasOne(TestRequest::className(), ['id' => 'request_id']);
    }

    public function getResponseStatus()
    {
        return $this->hasOne(ResponseStatus::className(), ['id' => 'status']);
    }

    public function getJob()
    {
        return $this->hasOne(Job::className(), ['id' => 'job_id']);
    }

    public function getTestResponseHeaders()
    {
        return $this->hasMany(TestResponseHeader::className(), ['response_id' => 'id']);
    }
}
