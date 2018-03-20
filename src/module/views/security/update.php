<?php
/**
 * @var $this yii\web\View
 * @var $modelPassword yii\base\Model
 * @var $modelEmail yii\base\Model
 */

use yii\bootstrap\Tabs;

// todo: записать Pills::widget в лайфхаки

?>

<div class="row">
	
    <div class="col-lg-4">
	    <?= Tabs::widget([
		    'items' => [
			    [
				    'label' => Yii::t('profile/security', 'password'),
				    'content' => BR . $this->context->renderPartial('password', ['model' => $modelPassword]),
			    ],
			    [
				    'label' => Yii::t('profile/security', 'email'),
				    'content' => BR . $this->context->renderPartial('email', ['model' => $modelEmail]),
			    ],
		    ],
	    ]) ?>
	</div>

</div>
