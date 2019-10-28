<?php

namespace yii2module\account\api\v2\controllers;

use App;
use Yii;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\extension\web\helpers\Behavior;
use yii2lab\rest\domain\rest\Controller;
use yii2module\account\api\v2\actions\registration\CreateAccountAction;
use yii2module\account\domain\v2\entities\AppIdentityEntity;


class RegistrationController extends Controller
{
	public $service = 'account.registration';

	public function behaviors()
	{
		return [
			'cors' => Behavior::cors(),
		];
	}

	/**
	 * @inheritdoc
	 */
	protected function verbs()
	{
		return [
			'create-account' => ['POST'],
			'activate-account' => ['POST'],
			'set-password' => ['POST'],
			'pseudo' => ['POST'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'create-account' => [
				'class' => CreateAccountAction::class,
				'successStatusCode' => 204,
				'serviceMethod' => 'createTempAccount',
				'serviceMethodParams' => ['login', 'email'],
			],
			'activate-account' => [
				'class' => 'yii2lab\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'activateAccount',
				'serviceMethodParams' => ['login', 'activation_code'],
			],
			'set-password' => [
				'class' => 'yii2lab\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'createTpsAccount',
				'serviceMethodParams' => ['login', 'activation_code', 'password'],
			],
		];
	}

	public function actionPseudo()
	{
		$body = Yii::$app->request->getBodyParams();
		$appIdentityEntity = new AppIdentityEntity();
		$appIdentityEntity->load($body);
		if(!empty($appIdentityEntity->sms_code)){
			return App::$domain->account->appIdentity->smsCheck($appIdentityEntity->login, $appIdentityEntity->sms_code);;
		}
		App::$domain->account->appIdentity->sendSms($body);
		return;
	}

}