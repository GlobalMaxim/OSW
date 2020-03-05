<?php

namespace app\models;

use Yii;

class Team extends \app\models\BaseObject
{
    public static function tableName()
    {
        return 'team';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name'], 'string', 'max' => 255]
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => Yii::t('models', 'Name'),
        ]);
    }

    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['team_id' => 'id']);
    }

    public function getTeamMembers()
    {
        return $this->hasMany(TeamMember::className(), ['team_id' => 'id']);
    }
}
