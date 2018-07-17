<?php

namespace yii2module\account\api\v2\controllers;

use yii2lab\rest\domain\rest\Controller;
use yii2lab\helpers\Behavior;

class RegistrationController extends Controller
{
	public $service = 'account.registration';
	
	public function behaviors() {
		return [
			'cors' => Behavior::cors(),
		];
	}
	
	/**
	 * @inheritdoc
	 */
	protected function verbs()
	{
		return [
			'create-account' => ['POST'],
			'activate-account' => ['POST'],
			'set-password' => ['POST'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'create-account' => [
				'class' => 'yii2lab\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'createTempAccount',
				'serviceMethodParams' => ['login', 'email'],
			],
			'activate-account' => [
				'class' => 'yii2lab\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'activateAccount',
				'serviceMethodParams' => ['login', 'activation_code'],
			],
			'set-password' => [
				'class' => 'yii2lab\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'createTpsAccount',
				'serviceMethodParams' => ['login', 'activation_code', 'password'],
			],
		];
	}

}