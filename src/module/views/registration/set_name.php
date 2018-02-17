<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii\base\Model */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('account/registration', 'set_name_title');
Yii::$app->navigation->breadcrumbs->create(['account/registration', 'title']);
Yii::$app->navigation->breadcrumbs->create($this->title);
?>
<div class="user-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('account/registration', 'set_name_text') ?></p>

    <div class="row">
        <div class="col-lg-5">
        
			<?php $form = ActiveForm::begin(['id' => 'form-create']); ?>

            <?= $form->field($model, 'first_name') ?>
	
	        <?= $form->field($model, 'last_name') ?>
            
            <div class="form-group">
				<?= Html::submitButton(t('action', 'next'), [
					'class' => 'btn btn-primary',
					'name' => 'create-button',
				]) ?>
				<?= Html::a(t('action', 'skip'), ['/user/registration/set-address'], [
					'class' => 'btn btn-default',
				]) ?>
            </div>
			
			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
