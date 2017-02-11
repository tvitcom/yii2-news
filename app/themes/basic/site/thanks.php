<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Thanks!';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?php echo Html::encode($this->title); ?></h1>
    <?php if (\Yii::$app->session->hasFlash('success')) \Yii::$app->session->getFlash('success');?>
    <p>Thank you for you request. You can read their emails and follow our further instructions.</p>
</div>
