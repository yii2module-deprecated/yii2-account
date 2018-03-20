<?php
/**
 * @var $this yii\web\View
 * @var $model yii\base\Model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'email')->textInput(['placeholder'=>$model->email]) ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('action', 'update'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
