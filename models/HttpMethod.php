<?php

namespace app\models;

use Yii;

class HttpMethod extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'http_method';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name'], 'string', 'max' => 15],
            ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('models', 'Name'),
        ]);
    }

    public function getTests()
    {
        return $this->hasMany(Test::className(), ['method' => 'id']);
    }
}
