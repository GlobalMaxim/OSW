<?php

namespace app\models;

use Yii;

class TestResponseHeader extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'test_response_header';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['header', 'value'], 'string', 'max' => 255],
            [['response_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestResponse::className(), 'targetAttribute' => ['response_id' => 'id']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'response_id' => Yii::t('models', 'Response ID'),
            'header' => Yii::t('models', 'Header'),
            'value' => Yii::t('models', 'Value'),
        ]);
    }

    public function getTestResponse()
    {
        return $this->hasOne(TestResponse::className(), ['id' => 'response_id']);
    }
}
