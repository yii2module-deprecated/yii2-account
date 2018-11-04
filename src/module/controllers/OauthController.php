<?php

namespace yii2module\account\module\controllers;

use yii\authclient\BaseOAuth;
use yii\web\Controller;

class OauthController extends Controller {
	
	public function actions() {
		return [
			'auth' => [
				'class' => 'yii\authclient\AuthAction',
				'successCallback' => [$this, 'onAuthSuccess'],
			],
		];
	}
	
	public function onAuthSuccess(BaseOAuth $client) {
		$loginEntity = \App::$domain->account->oauth->forgeAccount($client);
		\App::$domain->account->auth->login($loginEntity, true);
	}
	
}
