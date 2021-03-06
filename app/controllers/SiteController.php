<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Person;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;

class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'register'],
                'rules' => [
                    [
                        'actions' => ['register'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                /*
                 * // Ordinary captcha!!!
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                 */
                'class' => 'app\components\MathCaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? '42' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }
    
    /**
     * Displays user room-page.
     * @var common\models\Person $user 
     * @return string
     */
    public function actionRoom() {
        $user =  Yii::$app->user->identity;
        
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->render('room',[
            'user'=>$user,
        ]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionRegister() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if ($model->sendApproveEmail($user->id)) {
                    Yii::$app->session->setFlash('success', 'Check your email for '
                        . 'further instructions.');
                    return $this->render('thanks');
                } else {
                    Yii::$app->session->setFlash('alert', 'Sorry, we are unable to '
                        . 'approve your email - we awfully sorry. Register with another your valid email.');
                    return $this->redirect(['site/login']);
                }
            }
        }

        return $this->render('signup', [
                'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRecoveryPassword() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /* @var $user Person */
            $user = Person::findOne([
                'status' => Person::STATUS_ACTIVE,
                'email' => $model->email,
            ]);
            if ($model->sendResetLink($user)) {
                Yii::$app->session->setFlash('success', 'Check your email for '
                    . 'further instructions.');
                return $this->render('thanks');
            } else {
                Yii::$app->session->setFlash('alert', 'Sorry, we are unable to '
                    . 'reset password for email provided.');
                return $this->redirect(['site/login']);
            }
        }

        return $this->render('requestPasswordResetToken', [
                'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
            try {
                $model = new ResetPasswordForm($token);
            } catch (InvalidParamException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.You may use your login and new password!');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                'model' => $model,
        ]);
    }
    
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionApprovalEmail($token) {
        $model = new Person;  
        $user = $model->approvement($token);
        if (($user instanceof Person) 
                && Yii::$app->getUser()->login($user)) {
            \Yii::$app->session->setFlash('success', 'New user now is registered.');
            return $this->render('room',['user'=>$user]);
        } else {
            \Yii::$app->session->setFlash('alert', 'Bad link - your email not approved!');
            return $this->render('thanks');//$this->redirect(['site/login']);
        }

        
    }
    
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() 
    {  
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/site/room']);
        }
        return $this->render('login', [
                'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact() 
            {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                'model' => $model,
        ]);
    }

}
