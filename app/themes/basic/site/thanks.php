<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->title = 'Thanks!';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('success')) { ?>
<?= Alert::widget([
   'options' => ['class' => 'alert-info'],
   'body' => Yii::$app->session->getFlash('success'),
]);?>
<?php } ?>

<div class="site-about">
    
    <h1><?php echo Html::encode($this->title); ?></h1>
    
    <p>Thank you for you request. You can read their emails and follow our further instructions.</p>
</div>
