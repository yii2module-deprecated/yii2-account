<?php
/**
 * @var $this yii\web\View
 * @var $model yii\base\Model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'new_password')->passwordInput() ?>

<?= $form->field($model, 'new_password_repeat')->passwordInput() ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
	<?= Html::submitButton(Yii::t('action', 'update'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
