<?php
/* @var $this yii\web\View */
/* @var $user app\models\Person */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/approval-email', 'token' => $token]);
?>
Hello <?= $user->username ?>,

Follow the link below to approve your E-mail address or this message may be sent to your by mistake - delete this mail and we awfully sorry:

<?= $resetLink ?>
