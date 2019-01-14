<?php

namespace yii2module\account\api\v2\controllers;

use Yii;
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
		Yii::$app->response->setStatusCode(201);
		return $this->service->register($body);
	}
}