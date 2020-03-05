<?php

namespace app\models;

use Yii;

class ServerStatus extends \app\models\BaseObject
{

    public static function tableName()
    {
        return 'server_status';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['createdby_id', 'updateby_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('models', 'Name'),
        ]);
    }

    public function getServers()
    {
        return $this->hasMany(Server::className(), ['status_id' => 'id']);
    }
}
