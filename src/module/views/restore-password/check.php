<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('account/password', 'title');
Yii::$app->navigation->breadcrumbs->create($this->title);
?>
<div class="user-request-password-reset">
	<h1><?= Html::encode($this->title) ?></h1>

	<p><?= Yii::t('account/password', 'check_text {0}', $model->login) ?></p>

	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(); ?>

				<?= $form->field($model, 'activation_code')->textInput(['autofocus' => true]) ?>

				<div class="form-group">
					<?= Html::submitButton(Yii::t('account/password', 'request_action'), ['class' => 'btn btn-primary']) ?>
				</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
