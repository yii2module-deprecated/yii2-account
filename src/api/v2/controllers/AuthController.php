<?php

namespace yii2module\account\api\v2\controllers;

use Yii;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\rest\Controller;
use yii2lab\helpers\Behavior;
use yii2lab\helpers\ClientHelper;
use yii2module\account\console\forms\LoginForm;
use yii2module\account\console\forms\PseudoLoginForm;
use yii2module\account\domain\v2\interfaces\services\AuthInterface;

/**
 * Class AuthController
 *
 * @package yii2module\account\api\v2\controllers
 * @property AuthInterface $service
 */
class AuthController extends Controller {
	
	public $serviceName = 'account.auth';
	
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
			Helper::validateForm(LoginForm::class,$body);
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
			Helper::validateForm(PseudoLoginForm::class,$body);
			$address = ClientHelper::ip();
			$entity = Yii::$domain->account->authPseudo->pseudoAuthentication($body['login'], $address, $body['email'], $body['parentLogin']);
			return ['token'=> $entity->token];
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
			$response = $e->getErrors();
			return $response;
		}
	}
}