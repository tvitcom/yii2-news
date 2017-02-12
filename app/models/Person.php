<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\base\InvalidParamException;

/**
 * This is the model class for table "person".
 *
 * @property string $id
 * @property string $username
 * @property string $auth_key
 * @property string $pass_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $messenger
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $type_notify
 * @property string $notify_about
 *
 * @property Post[] $post
 */
class Person extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%person}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'pass_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_APPROVE, self::STATUS_DELETED]],
            [['created_at', 'updated_at'], 'safe'],
            ['email', 'email'],
            [['username'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['pass_hash', 'email'], 'string', 'max' => 64],
            [['password_reset_token'], 'string', 'max' => 128],
            [['messenger', 'type_notify', 'notify_about'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'pass_hash' => Yii::t('app', 'Pass Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'messenger' => Yii::t('app', 'Messenger'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Last Visit'),
            'type_notify' => Yii::t('app', 'Type Notify'),
            'notify_about' => Yii::t('app', 'Notify About'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost() {
        return $this->hasMany(Post::className(), ['author_id' => 'id']);
    }

    /* -------- PART FOR AUTHENTICATION USER IN APPLICATION ---- */

    const STATUS_DELETED = 0;
    const STATUS_APPROVE = 1;
    const STATUS_ACTIVE = 10;

    /**
     * For get datetime value in datetime format
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('SYSDATE()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function setUsername($username) {
        return $this->username = $username;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->pass_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->pass_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }
    
    /**
     * The proccess approvement for registered user:
     * -find user by token
     * -fix his status and rewrite auth_key
     * -return as result the user object
     * @param string $token
     * @return object $user - user object
     */
    public static function approvement($token) {
        if ($user = static::findOne([
                    'auth_key' => $token, 
                    'status' => self::STATUS_APPROVE
                ])) {
            $user->status = Person::STATUS_ACTIVE;
            //$user->generateAuthKey();
            if ($user->save()) {
                return $user;
            } else {
                throw new InvalidParamException('Error save approval user');
            }
        }
        return null;
    }
}
