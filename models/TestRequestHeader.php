<?php

namespace app\models;

use Yii;

class TestRequestHeader extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'test_request_header';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['description'], 'string'],
            [['header', 'value'], 'string', 'max' => 255],
            [['requestid'], 'exist', 'skipOnError' => true, 'targetClass' => TestRequest::className(), 'targetAttribute' => ['requestid' => 'id']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'requestid' => Yii::t('models', 'Requestid'),
            'description' => Yii::t('models', 'Description'),
            'header' => Yii::t('models', 'Header'),
            'value' => Yii::t('models', 'Value'),
        ]);
    }

    public function getTestRequest()
    {
        return $this->hasOne(TestRequest::className(), ['id' => 'requestid']);
    }
}
