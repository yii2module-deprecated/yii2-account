<?php

namespace yii2module\account\module;

use Yii;
use yii\base\Module as YiiModule;
use yii\web\NotFoundHttpException;

/**
 * user module definition class
 */
class Module extends YiiModule
{
	
	//public static $langDir = 'yii2module/account/domain/v1/messages';
	
	public function beforeAction($action)
	{
		$controllerId = Yii::$app->controller->id;
		$moduleId = 'account';
		Yii::$app->view->title = Yii::t($moduleId . SL . $controllerId, 'title');
		Yii::$app->navigation->breadcrumbs->create([$moduleId . SL . 'main', 'title']);
		Yii::$app->navigation->breadcrumbs->create([$moduleId . SL . $controllerId, 'title']);
		
		if(APP == BACKEND && in_array($controllerId, ['password', 'reg'])) {
			throw new NotFoundHttpException();
		}
		return parent::beforeAction($action);
	}

}
