<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Person;
use yii\captcha\Captcha;
use yii\base\InvalidParamException;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\Person', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 64],
            ['email', 'unique', 'targetClass' => 'app\models\Person', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'required'],
            ['password_repeat', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'E-mail'),
            'password' => Yii::t('app', 'Password'),
            'password_repeat'=> Yii::t('app','Repeat password'),
            'rememberMe' => Yii::t('app', 'Remember login and password'),
            'verifyCode'=> Yii::t('app','Verify captcha code:'),
        ];
    }
    
    /**
     * Signs user up.
     *
     * @return Person|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $user = new Person();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->created_at = '2000-01-01 00:00:00';
        $user->updated_at = '2000-01-01 00:00:00';

        if ($user->save())
            return $user;
        else {
            throw new InvalidParamException('error saved model person!!!');
        }
    }
    
    /**
     * Sends an email with a link, for approve the registration.
     *
     * @return bool whether the email was send
     */
    public function sendApproveEmail($approve_token='132token123') {
        /* @var $user Person */
        $user = Person::findOne([
                'status' => Person::STATUS_APPROVE,
                'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!Person::isPasswordResetTokenValid($user->password_reset_token)) {
            $new_token = $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
                ->mailer
                ->compose(
                    [
                    'html' => 'passwordResetToken-html',
                    'text' => 'passwordResetToken-text',
                    ], ['user' => $user, 'token' => $new_token]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name
                    . ' robot'])
                ->setTo($this->email)
                ->setSubject('Password reset for ' . Yii::$app->name)
                ->send();
    }
}
