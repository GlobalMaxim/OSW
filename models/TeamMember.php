<?php

namespace app\models;

use Yii;

class TeamMember extends \app\models\BaseObject
{
    public static function tableName()
    {
        return 'team_member';
    }

    public function rules()
    {
        return  array_merge(parent::rules(),[
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['member_id' => 'id']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'team_id' => Yii::t('models', 'Team ID'),
            'member_id' => Yii::t('models', 'Member ID'),
        ]);
    }

    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    public function getMember()
    {
        return $this->hasOne(User::className(), ['id' => 'member_id']);
    }
}
