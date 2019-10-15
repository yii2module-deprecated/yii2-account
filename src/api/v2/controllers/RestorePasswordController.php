<?php

namespace yii2module\account\api\v2\controllers;

use Yii;
use yii\web\Response;
use yii2lab\domain\helpers\Helper;
use yii2lab\rest\domain\rest\Controller;
use yii2module\account\domain\v2\forms\RestorePasswordForm;

class RestorePasswordController extends Controller
{
	public $service = 'account.restorePassword';

	/**
	 * @inheritdoc
	 */
	protected function verbs()
	{
		return [
			'request' => ['POST'],
			'check-code' => ['POST'],
			'confirm' => ['POST'],
			'resend-code' => ['POST']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'check-code' => [
				'class' => 'yii2lab\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'checkActivationCode',
				'serviceMethodParams' => ['login', 'activation_code', 'password'],
			],
			'confirm' => [
				'class' => 'yii2lab\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'confirm',
				'serviceMethodParams' => ['login', 'activation_code', 'password'],
			],
		];
	}

	public function actionRequest()
	{
		$body = Yii::$app->request->getBodyParams();
		$form = new RestorePasswordForm();
		$form->setAttributes(Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_REQUEST), false);
		$entity = \App::$domain->account->restorePassword->request($form->login, $form->email);
		if ($entity) {
			return $entity;
		}
		Yii::$app->response->format = Response::FORMAT_RAW;
	}


}