<?php
/* @var $this yii\web\View */
/* @var $user app\model\Person */

use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->title = $user->username . ' room';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('success')) { ?>
<?= Alert::widget([
   'options' => ['class' => 'alert-success'],
   'body' => Yii::$app->session->getFlash('success'),
]);?>
<?php } ?>

<div class="site-about">
    
    <h1><?php echo Html::encode($this->title); ?></h1>
    
    <p>Thank you for you visit. You can read their information and see our news.</p>
</div>
