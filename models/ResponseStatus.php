<?php

namespace app\models;

use Yii;

class ResponseStatus extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'response_status';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 7],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('models', 'Name'),
            'color' => Yii::t('models', 'Color'),
        ]);
    }

    public function getTestResponses()
    {
        return $this->hasMany(TestResponse::className(), ['status' => 'id']);
    }

}
