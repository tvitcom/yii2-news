<?php

namespace app\models;

use Yii;
use yii\rbac\PhpManager;
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
            'rememberMe' => Yii::t('app', 'Remember in browser the login and password'),
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
        $user->status = Person::STATUS_APPROVE;
        $user->created_at = '2000-01-01 00:00:00';
        $user->updated_at = '2000-01-01 00:00:00';

        /* RBAC assign role temporary disable */
        //adding RBAC assign default role:'reader':
        //$auth = Yii::$app->authManager;
        //$authorRole = $auth->getRole('reader');
        //$auth->assign($authorRole, $user->getId());
        
        if ($user->save())
            return $user;
        else {
            throw new InvalidParamException('error saved model person!!!');
        }
    }
    
    /**
     * Method for create unique link for approval user entered email
     * @var $user Person
     * @return bool whether the email was send
     */
    public function sendApproveEmail($id=0) {
        $user = Person::findOne([
            'id'=>$id,
            'status' => Person::STATUS_APPROVE,
        ]);

        if (!$user) {
            throw new InvalidParamException('Error send mail - the user not found');
        }
        
        if ($approval_token = $this->createSecretLink($user->auth_key)) {
         return Yii::$app
                ->mailer
                ->compose(
                    [
                    'html' => 'emailApprovalToken-html',
                    'text' => 'emailApprovalToken-text',
                    ], ['user' => $user, 'token' => $approval_token]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name
                    . ' robot'])
                ->setTo($this->email)
                ->setSubject('E-mail approval for ' . Yii::$app->name)
                ->send();   
        } else {
            throw new InvalidParamException('Error create token for approve email');
        }
    }
    
    /**
     * Create secret link for the user as uniq secrete link
     */
    private function createSecretLink($str='') {
        //some shamanian procedure:)
        if ($str == true) {
            return $str;
        } else {
            return null;
        }
    }
}
