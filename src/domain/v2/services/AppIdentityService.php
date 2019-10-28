<?php

namespace yii2module\account\domain\v2\services;

use App;
use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\domain\data\Query;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\base\BaseActiveService;
use yii2module\account\domain\v2\entities\AppIdentityEntity;
use yii2module\account\domain\v2\helpers\ConfirmHelper;
use yii2module\account\domain\v2\interfaces\services\AppIdentityInterface;
use yii2woop\notify\domain\v1\strategies\sms\SmsStrategy;
use yii2woop\operation\domain\v2\helpers\TpsHelper;

/**
 * Class AppIdentityService
 *
 * @package yii2module\account\domain\v2\services
 *
 * @property-read \yii2module\account\domain\v2\Domain $domain
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\AppIdentityInterface $repository
 */
class AppIdentityService extends BaseActiveService implements AppIdentityInterface
{

	public function sendSms($body)
	{
		if ($this->isExist($body['login'])) {
			Yii::$app->response->setStatusCode(204);
			return;
		}
		$body['sms_code'] = (string)ConfirmHelper::generateCode();
		$appIdentityEntity = $this->create($body);
		$smsStrategy = new SmsStrategy(TpsHelper::getMobileOperator($appIdentityEntity->getLogin()));
		$smsStrategy->send($appIdentityEntity->getLogin(), $appIdentityEntity->getSmsCode());
		Yii::$app->response->setStatusCode(201);
	}

	public function smsCheck($login, $smsCode)
	{

		if ($this->isKeyExist($login)) {
			Yii::$app->response->setStatusCode(204);
			return;
		};

		$query = Query::forge();
		$query->where(['login' => $login]);
		$appIdentityEntity = $this->one($query);

		if ($appIdentityEntity->sms_code != $smsCode) {
			$errors = new ErrorCollection();
			$errors->add('', Yii::t('account/appIdentity', 'wrong_sms'));
			throw new UnprocessableEntityHttpException($errors);
		}

		$appIdentityEntity->key = md5($login + $smsCode);

		$this->update($appIdentityEntity);
		return [
			'key' => $appIdentityEntity->key
		];
	}

	private function isExist($login)
	{
		$query = Query::forge();
		$query->where(['login' => $login]);
		try {
			App::$domain->account->appIdentity->one($query);
		} catch (NotFoundHttpException $e) {
			return false;
		}
		return true;
	}

	private function isKeyExist($login)
	{
		$query = Query::forge();
		$query->where(['login' => $login]);
		$appIdentityEntity = App::$domain->account->appIdentity->one($query);
		return !empty($appIdentityEntity->key);
	}

	public function checkKey($login, $key)
	{
		$appIdentityRequest = new AppIdentityEntity();
		$appIdentityRequest->load(['login' => $login, 'key' => $key]);

		$query = Query::forge();
		$query->where(['login' => $appIdentityRequest->getLogin()]);
		$appIdentityEntity = App::$domain->account->appIdentity->one($query);
		if($appIdentityEntity->getKey() !== $appIdentityRequest->getKey()){
			$errors = new ErrorCollection();
			$errors->add('login', Yii::t('account/appIdentity', 'wrong_key'));
			throw new UnprocessableEntityHttpException($errors);
		}

		// TODO: Implement checkKey() method.
	}
}
