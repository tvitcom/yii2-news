<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Thanks!';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('success')) { ?>
  <div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
  <h4><i class="icon fa fa-check"></i><?= Yii::t('app','Success!');?></h4>
  <?= Yii::$app->session->getFlash('success') ?>
  </div>
<?php } ?>

<div class="site-about">
    
    <h1><?php echo Html::encode($this->title); ?></h1>
    
    <p>Thank you for you request. You can read their emails and follow our further instructions.</p>
</div>
