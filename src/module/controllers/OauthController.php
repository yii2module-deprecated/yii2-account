<?php

namespace yii2module\account\module\controllers;

use yii\authclient\BaseOAuth;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;

class OauthController extends Controller {
	
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['login'],
						'allow' => true,
						'roles' => ['?'],
					],
				],
			],
		];
	}
	
	public function actions() {
		return [
			'login' => [
				'class' => 'yii\authclient\AuthAction',
				'successCallback' => [$this, 'onLoginSuccess'],
			],
		];
	}
	
	public function init() {
		if(!\App::$domain->account->oauth->isEnabled()) {
			throw new ServerErrorHttpException('Auth clients not defined');
		}
		parent::init();
	}
	
	public function onLoginSuccess(BaseOAuth $client) {
		$loginEntity = \App::$domain->account->oauth->forgeAccount($client);
		\App::$domain->account->auth->login($loginEntity, true);
	}
	
}
