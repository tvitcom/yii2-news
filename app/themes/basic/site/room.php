<?php
/* @var $this yii\web\View */
/* @var $user app\model\Person */

use yii\helpers\Html;

$this->title = $user->username . ' room';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">
    
    <h1><?php echo Html::encode($this->title); ?></h1>
    
    <p>Thank you for you visit. You can read their information and see our news.</p>
</div>
