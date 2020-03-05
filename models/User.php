<?php

namespace app\models;

use app\components\Converter;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const FEMALE = 0;
    const MALE = 1;

    const DATE_FORMAT = 'd.m.Y';

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['is_authenticated', 'boolean'],
            ['name', 'string'],
            [['id'], 'integer'],
            ['email', 'email'],
            [['gender'], 'boolean']
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expireTime = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expireTime >= time();
    }

    public static function createWithRandomPassword()
    {
        $user = new User();
        $user->password = Yii::$app->security->generateRandomString();
        $user->generateAuthKey();
        return $user;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'rating' => 'Social Rating',
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->is_authenticated = false;
        }
        return parent::beforeSave($insert);
    }

    public function sendConfirmationEmail($user)
    {
        $link = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm', 'id' => $user->id, 'auth_key' => $user->auth_key]);

        return Yii::$app
            ->mailer
            ->compose('register', [
                'user' => $user,
                'link' => $link,
            ])
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name . ' Robot'])
            ->setTo($user->email)
            ->setSubject('Register for ' . Yii::$app->name)
            ->send();
    }

    public function getCreatedProjects()
{
    $this->hasMany(Project::className(), ['createdby_id' => 'id']);
}

    public function getUpdatedProjects()
    {
        $this->hasMany(Project::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedTeams()
    {
        $this->hasMany(Team::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedTeams()
    {
        $this->hasMany(Team::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedTeamMembers()
    {
        $this->hasMany(TeamMember::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedTeamMembers()
    {
        $this->hasMany(TeamMember::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedServerStatuses()
    {
        $this->hasMany(ServerStatus::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedServerStatuses()
    {
        $this->hasMany(ServerStatus::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedProtocols()
    {
        $this->hasMany(Protocol::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedProtocols()
    {
        $this->hasMany(Protocol::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedServers()
    {
        $this->hasMany(Server::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedServers()
    {
        $this->hasMany(Server::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedResponseStatuses()
    {
        $this->hasMany(ResponseStatus::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedResponseStatuses()
    {
        $this->hasMany(ResponseStatus::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedHttpMethods()
    {
        $this->hasMany(HttpMethod::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedHttpMethods()
    {
        $this->hasMany(HttpMethod::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedTests()
    {
        $this->hasMany(Test::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedTests()
    {
        $this->hasMany(Test::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedJobs()
    {
        $this->hasMany(Job::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedJobs()
    {
        $this->hasMany(Job::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedTestRequests()
    {
        $this->hasMany(TestRequest::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedTestRequests()
    {
        $this->hasMany(TestRequest::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedTestResponses()
    {
        $this->hasMany(TestResponse::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedTestResponses()
    {
        $this->hasMany(TestResponse::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedTestRequestHeaders()
    {
        $this->hasMany(TestRequestHeader::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedTestRequestHeaders()
    {
        $this->hasMany(TestRequestHeader::className(), ['updatedby_id' => 'id']);
    }

    public function getCreatedTestResponseHeaders()
    {
        $this->hasMany(TestResponseHeader::className(), ['createdby_id' => 'id']);
    }

    public function getUpdatedTestResponseHeaders()
    {
        $this->hasMany(TestResponseHeader::className(), ['updatedby_id' => 'id']);
    }

    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['user_id' => 'id']);
    }

    public function getTeamMembers()
    {
        return $this->hasMany(TeamMember::className(), ['user_id' => 'id']);
    }

    public function getMoqups()
    {
        return $this->hasMany(Moqup::className(), ['user_id' => 'id']);
    }

    public function getMoqupsCount()
    {
        return count($this->moqups);
    }

    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['user_id' => 'id']);
    }

    public function getIssuesCount()
    {
        return count($this->issues);
    }

    public function getSupportGroup()
    {
        return $this->hasMany(SupportGroup::className(), ['user_id' => 'id']);
    }

    public function getSupportGroupCount()
    {
        return count($this->supportGroup);
    }

    public function getSupportGroupMember()
    {
        return $this->hasMany(SupportGroupMember::className(), ['support_group_id' => 'id'])->viaTable('support_group', ['user_id' => 'id']);
    }

    public function getSupportGroupCommand()
    {
        return $this->hasMany(SupportGroupCommand::className(), ['support_group_id' => 'id'])->viaTable('support_group', ['user_id' => 'id']);
    }

    public function getSupportGroupBot()
    {
        return $this->hasMany(SupportGroupBot::className(), ['support_group_id' => 'id'])->viaTable('support_group', ['user_id' => 'id']);
    }

    public function getSupportGroupMemberCount()
    {
        return count($this->supportGroupMember);
    }

    public function getBotsCount()
    {
        return count($this->supportGroupBot);
    }

    public function getFollowedMoqups()
    {
        return $this->hasMany(Moqup::className(), ['id' => 'moqup_id'])->viaTable('user_moqup_follow', ['user_id' => 'id']);
    }

    public function getFollowedMoqupsId()
    {
        $ids = [];

        if (!empty($this->followedMoqups)) {
            $ids = array_merge($ids, \yii\helpers\ArrayHelper::getColumn($this->followedMoqups, 'id'));
        }

        return $ids;
    }

    public function getMaxMoqupsNumber()
    {
        $setting = Setting::findOne(['key' => 'moqup_quantity_value_per_one_rating']);
        $maxMoqup = ($setting != null) ? $setting->value : 1;

        return $maxMoqup * $this->rating;
    }

    public function getMaxIssuesNumber()
    {
        $setting = Setting::findOne(['key' => 'issue_quantity_value_per_one_rating']);
        $maxIssue = ($setting != null) ? $setting->value : 1;

        return $maxIssue * $this->rating;
    }

    public function getMaxSupportGroup()
    {
        $setting = Setting::findOne(['key' => 'support_group_quantity_value_per_one_rating']);
        $settingQty = ($setting != null) ? $setting->value : 1;

        return $settingQty * $this->rating;
    }

    public function getMaxSupportGroupMember()
    {
        $setting = Setting::findOne(['key' => 'support_group_member_quantity_value_per_one_rating']);
        $settingQty = ($setting != null) ? $setting->value : 1;

        return $settingQty * $this->rating;
    }

    public function getMaxBots()
    {
        $setting = Setting::findOne(['key' => 'support_group_bot_quantity_value_per_one_rating']);
        $settingQty = ($setting != null) ? $setting->value : 1;

        return $settingQty * $this->rating;
    }

    public function getReachMaxMoqupsNumber()
    {
        return $this->moqupsCount >= $this->maxMoqupsNumber;
    }

    /**
     * @return integer The total amount of moqups size in bytes
     */
    public function getTotalMoqupsSize()
    {
        $size = 0;

        if (!empty($this->moqups)) {
            foreach ($this->moqups as $moq) {
                $size += strlen($moq->html);

                if ($moq->css != null) {
                    $size += strlen($moq->css->css);
                }
            }
        }

        return Converter::byteToMega($size);
    }

    public function getMaxMoqupsSize()
    {
        $maxLength = $this->maxMoqupsHtmlSize + $this->maxMoqupsCssSize;

        return Converter::byteToMega($maxLength * $this->rating);
    }

    public function getMaxMoqupsHtmlSize()
    {
        $setting = Setting::findOne(['key' => 'moqup_html_field_max_value']);
        return ($setting != null) ? $setting->value : 1;
    }

    public function getMaxMoqupsCssSize()
    {
        $setting = Setting::findOne(['key' => 'moqup_css_field_max_value']);
        return ($setting != null) ? $setting->value : 1;
    }

    public function getReachMaxMoqupsSize()
    {
        return $this->totalMoqupsSize >= $this->maxMoqupsSize;
    }

    public function getRatings()
    {
        return $this->hasMany(Rating::className(), ['user_id' => 'id']);
    }

    public function getRating()
    {
        $balance = Rating::find()->select(['balance' => 'sum(amount)'])->where(['user_id' => $this->id])->groupBy('user_id')->scalar();

        return ($balance != null) ? $balance : 0;
    }

    public function getOverallRatingPercent($format = true)
    {
        $totalRating = Rating::getTotalRating();
        return Converter::percentage($this->rating, $totalRating, $format);
    }

    public function getActiveRating()
    {
        $setting = Setting::findOne(['key' => 'days_count_to_calculate_active_rating']);
        $daysActiveRating = intval($setting->value);

        $balance = Rating::find()
            ->where(['>', 'created_at', time() - 3600 * 24 * $daysActiveRating])
            ->andWhere(['user_id' => $this->id])
            ->sum('amount');

        return ($balance != null) ? $balance : 0;
    }

    public function addRating($ratingType = Rating::CONFIRM_EMAIL, $ratingAmount = 1, $existMultiple = true)
    {
        $id = $this->id;

        $commit = false;
        $rating = null;

        //If a rating can exist only once
        if (!$existMultiple) {
            $rating = Rating::findOne([
                'user_id' => $id,
                'type' => $ratingType,
            ]);
        }

        if ($rating == null) {
            $rating = new Rating([
                'user_id' => $id,
                'amount' => $ratingAmount,
                'type' => $ratingType,
            ]);

            if ($rating->save()) {
                $commit = true;
            }
        }
        return $commit;
    }

    public function getReferrals(int $level = 1)
    {
        return User::find()->where([
            'referrer_id' => $this->id,
            'is_authenticated' => true,
        ]);
    }

    public function getReferrer()
    {
        return $this->hasOne(User::class, ['id' => 'referrer_id']);
    }

    public function getContact()
    {
        return $this->hasOne(Contact::class, ['link_user_id' => 'id'])
            ->onCondition(['user_id' => Yii::$app->user->id]);
    }

    public function getDisplayName()
    {
        return $this->contact->getContactName();
    }

    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'projectid']);
    }
}
