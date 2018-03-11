<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii\base\Model */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->navigation->breadcrumbs->create(['account/registration', 'set_address_title']);
Yii::$app->navigation->breadcrumbs->create(['account/registration', 'title']);
Yii::$app->navigation->breadcrumbs->create($this->title);
?>
<div class="user-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('account/registration', 'set_address_text') ?></p>

    <div class="row">
        <div class="col-lg-5">
        
			<?php $form = ActiveForm::begin(['id' => 'form-create']); ?>

            <?= $form->field($model, 'district') ?>
	
	        <?= $form->field($model, 'zip_code') ?>
            
            <div class="form-group">
				<?= Html::submitButton(Yii::t('action', 'complete'), [
					'class' => 'btn btn-primary',
					'name' => 'create-button',
				]) ?>
            </div>
			
			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
