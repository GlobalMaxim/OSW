<?php

namespace app\models;

use Yii;

class Protocol extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'protocol';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 15],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('models', 'Name'),
            'code' => Yii::t('models', 'Code'),
        ]);
    }
}
