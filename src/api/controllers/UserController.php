<?php

namespace yii2module\account\api\controllers;

use yii2lab\domain\rest\ActiveController as Controller;

class UserController extends Controller
{

	public $serviceName = 'account.login';

	public function format() {
		return [
			'creation_date' => 'time:api',
			'birth_date' => 'time:api',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'create' => [
				'class' => 'yii2lab\domain\rest\CreateAction',
			],
			'view' => [
				'class' => 'yii2lab\domain\rest\ViewActionWithQuery',
			],
		];
	}

}