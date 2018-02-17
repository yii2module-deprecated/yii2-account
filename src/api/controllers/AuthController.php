<?php

namespace yii2module\account\api\controllers;

use Yii;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\rest\Controller;
use yii2lab\helpers\Behavior;
use yii2woop\common\components\TpsTransport;

class AuthController extends Controller {
	
	public $serviceName = 'account.auth';
	
	public function format() {
		return [
			'profile' => [
				'sex' => 'boolean',
				'birth_date' => 'time:api',
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
			$entity = $this->service->authentication($body['login'], $body['password']);
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
			$address = TpsTransport::getUserHostAddress();
			$entity = $this->service->pseudoAuthenticationWithParent($body['login'], $address, $body['email'], $body['parentLogin']);
			return ['token'=> $entity->token];
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
			$response = $e->getErrors();
			return $response;
		}
	}
}