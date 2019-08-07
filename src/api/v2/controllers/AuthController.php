<?php

namespace yii2module\account\api\v2\controllers;

use Yii;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\Helper;
use yii2lab\extension\web\helpers\Behavior;
use yii2lab\extension\web\helpers\ClientHelper;
use yii2lab\rest\domain\rest\Controller;
use yii2woop\common\domain\account\v2\forms\AuthPseudoForm;
use yii2module\account\domain\v2\interfaces\services\AuthInterface;

/**
 * Class AuthController
 *
 * @package yii2module\account\api\v2\controllers
 * @property AuthInterface $service
 */
class AuthController extends Controller
{
	
	public $service = 'account.auth';
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'cors' => Behavior::cors(),
			'authenticator' => Behavior::auth(['info']),
		];
	}
	
	/**
	 * @inheritdoc
	 */
	protected function verbs()
	{
		return [
			'login' => ['POST'],
			'info' => ['GET'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'info' => [
				'class' => 'yii2lab\domain\rest\UniAction',
				'service' => Yii::$app->user,
				'successStatusCode' => 200,
				'serviceMethod' => 'getIdentity',
			],
			'options' => [
				'class' => 'yii\rest\OptionsAction',
			],
		];
	}
	
	public function actionLogin()
	{
		if (!is_string(Yii::$app->request->getUserAgent())){
			Yii::warning(json_encode( Yii::$app->request->getUserAgent()));
		} else{
			Yii::warning(Yii::$app->request->getUserAgent());
		}

//		Yii::$app->log->targets = [];
		$body = Yii::$app->request->getBodyParams();
		try {
			$ip = ClientHelper::ip();
			$entity = $this->service->authentication2($body, $ip);
			Yii::$app->response->headers->set('Authorization', $entity->token);
			return $entity;
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
			$response = $e->getErrors();
			return $response;
		}
	}
	
	public function actionPseudo()
	{
		$body = Yii::$app->request->getBodyParams();
		try {
			$body = Helper::validateForm(AuthPseudoForm::class, $body);
			$address = ClientHelper::ip();
			$parentLogin = null;
			if(!empty($body['parentLogin'])){
				$parentLogin = $body['parentLogin'];
			} elseif (!empty($body['parent_login'])){
				$parentLogin = $body['parent_login'];
			}
			$entity = \App::$domain->account->authPseudo->authentication($body['login'], $address, $body['email'], $parentLogin);
			return $entity;
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
			$response = $e->getErrors();
			return $response;
		}
	}
}