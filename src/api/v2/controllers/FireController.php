<?php

namespace yii2module\account\api\v2\controllers;

use yii2lab\extension\web\helpers\Behavior;
use yii2lab\rest\domain\rest\Controller;
use yii2woop\common\domain\account\v2\interfaces\services\FireUserInterface;

/**
 * Class FireController
 *
 * @package yii2module\account\api\v2\controllers
 * @property FireUserInterface $service
 */
class FireController extends Controller {
	
	public $service = 'account.fireUser';
	
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'authenticator' => Behavior::auth([
				'register-user',
			]),
			'verb' => Behavior::verb($this->verbs()),
		];
	}
	
	/**
	 * @inheritdoc
	 */
	protected function verbs() {
		return [
			'register-user' => ['POST'],
		];
	}
	
	public function actionRegisterUser() {
		$body = \Yii::$app->request->getBodyParams();
		return $this->service->register($body);
	}
}