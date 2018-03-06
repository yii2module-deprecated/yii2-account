<?php

namespace yii2module\account\api\v2\controllers;

use yii2lab\domain\rest\Controller as Controller;
use yii\filters\VerbFilter;
use yii2lab\helpers\Behavior;

class SecurityController extends Controller
{

	public $serviceName = 'account.security';

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'authenticator' => Behavior::apiAuth(),
			'verb' => Behavior::verb([
				'email' => ['PUT'],
				'password' => ['PUT'],
			]),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		$actions = parent::actions();
		$actions['email'] = [
			'class' => 'yii2lab\domain\rest\UniAction',
			'successStatusCode' => 204,
			'serviceMethod' => 'changeEmail',
		];
		$actions['password'] = [
			'class' => 'yii2lab\domain\rest\UniAction',
			'successStatusCode' => 204,
			'serviceMethod' => 'changePassword',
		];
		return $actions;
	}

}