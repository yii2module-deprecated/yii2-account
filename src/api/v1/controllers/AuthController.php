<?php

namespace yii2module\account\api\v1\controllers;

use Yii;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\rest\domain\rest\Controller;
use yii2lab\helpers\Behavior;
use yii2lab\helpers\ClientHelper;
use yii2module\account\domain\v2\services\AuthService;
use yii2woop\common\components\TpsTransport;

/**
 * Class AuthController
 *
 * @package yii2module\account\api\v1\controllers
 *
 * @property AuthService $service
 */
class AuthController extends Controller {
	
	public $service = 'account.auth';
	
	public function format() {
		return [
			'profile' => [
				'sex' => 'boolean',
			],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'authenticator' => Behavior::apiAuth(['info']),
		];
	}
	
	/**
	 * @inheritdoc
	 */
	protected function verbs() {
		return [
			'login' => ['POST'],
			'info' => ['GET'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'info' => [
				'class' => 'yii2lab\domain\rest\UniAction',
				'service' => Yii::$app->user,
				'successStatusCode' => 200,
				'serviceMethod' => 'getIdentity',
			],
		];
	}
	
	public function actionLogin() {
		$body = Yii::$app->request->getBodyParams();
		try {
			$ip = ClientHelper::ip();
			$entity = $this->service->authentication($body['login'], $body['password'], $ip);
			Yii::$app->response->headers->set('Authorization', $entity->token);
			return $entity;
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
			$response = $e->getErrors();
			return $response;
		}
	}

	public function actionPseudo() {
		$body = Yii::$app->request->getBodyParams();
		try {
			$address = ClientHelper::ip();
			$entity = $this->service->pseudoAuthenticationWithParent($body['login'], $address, $body['email'], $body['parentLogin']);
			return ['token'=> $entity->token];
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
			$response = $e->getErrors();
			return $response;
		}
	}
}