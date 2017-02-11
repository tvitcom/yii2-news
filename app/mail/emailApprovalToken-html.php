<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\Person */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl([
    'site/approval-email', 
    'token' => $token
]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to approve your E-mail address or this message may be sent to your by mistake - delete this mail and we awfully sorry:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
