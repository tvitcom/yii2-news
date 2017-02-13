<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Person;
use yii\captcha\Captcha;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

    public $email;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\Person',
                'filter' => ['status' => Person::STATUS_ACTIVE],
                'message' => 'There is no approved user with such email.'
            ],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail() {
        /* @var $user Person */
        $user = Person::findOne([
                'status' => Person::STATUS_ACTIVE,
                'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!Person::isPasswordResetTokenValid($user->password_reset_token)) {
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
                ->mailer
                ->compose(
                    [
                    'html' => 'passwordResetToken-html',
                    'text' => 'passwordResetToken-text'], ['user' => $user, 'token' => $user->generatePasswordResetToken()]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name
                    . ' robot'])
                ->setTo($this->email)
                ->setSubject('Password reset for ' . Yii::$app->name)
                ->send();
    }

}
