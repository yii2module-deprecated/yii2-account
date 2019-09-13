<?php

namespace yii2module\account\api\v2\controllers;

use Yii;
use yii2lab\rest\domain\rest\Controller;
use yii\web\Response;

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
            'request-by-partner' => ['POST']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
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

	public function actionRequest() {
		$body = Yii::$app->request->getBodyParams();
		$entity = \App::$domain->account->restorePassword->request($body);
		if($entity){
			return $entity;
		}
		Yii::$app->response->format = Response::FORMAT_RAW;
	}

    public function actionRequestByPartner()
    {
        $body = Yii::$app->request->getBodyParams();
        $entity = \App::$domain->account->restorePassword->requestByPartner($body);
        if ($entity) {
            return $entity;
        }
        Yii::$app->response->format = Response::FORMAT_RAW;
    }
}