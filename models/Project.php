<?php

namespace app\models;

use Yii;

class Project extends BaseObject
{
    public static function tableName()
    {
        return 'project';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['description', 'type'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('models', 'Name'),
            'description' => Yii::t('models', 'Description'),
            'type' => Yii::t('models', 'Type'),
            'team_id' => Yii::t('models', 'Team ID'),
            'owner_id' => Yii::t('models', 'Owner ID'),
        ]);
    }


    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }
}
