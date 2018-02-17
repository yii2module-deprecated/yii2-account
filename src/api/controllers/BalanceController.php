<?php

namespace yii2module\account\api\controllers;

use yii2lab\domain\rest\Controller;
use yii2lab\helpers\Behavior;

class BalanceController extends Controller
{

	public $serviceName = 'account.balance';

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'authenticator' => Behavior::apiAuth(),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'index' => [
				'class' => 'yii2lab\domain\rest\IndexActionWithQuery',
				'serviceMethod' => 'oneSelf',
			],
		];
	}
	
}