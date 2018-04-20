<?php

namespace yii2module\account\domain\v2\services;

use yii\web\NotFoundHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\ActiveBaseService;
use yii2module\account\domain\v2\helpers\ConfirmHelper;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii2module\account\domain\v2\interfaces\services\ConfirmInterface;

/**
 * Class ConfirmService
 *
 * @package yii2module\account\domain\v2\services
 * @property \yii2module\account\domain\v2\interfaces\repositories\ConfirmInterface $repository
 */
class ConfirmService extends ActiveBaseService implements ConfirmInterface {
	
	public function delete($login, $action) {
		$login = LoginHelper::getPhone($login);
		$this->beforeAction(self::EVENT_DELETE);
		$this->repository->cleanOld($login, $action, -1);
		return $this->afterAction(self::EVENT_DELETE);
	}
	
	public function isVerifyCode($login, $action, $code, $smsCodeExpire) {
		$login = LoginHelper::getPhone($login);
		$confirmEntity = $this->oneByLoginAndAction($login, $action, $smsCodeExpire);
		if ($confirmEntity->code != $code) {
			return false;
		}
		return true;
	}
	
	public function oneByLoginAndAction($login, $action, $smsCodeExpire) {
		$login = LoginHelper::getPhone($login);
		$this->cleanOld($login, $action, $smsCodeExpire);
		return $this->repository->oneByLoginAndAction($login, $action);
	}
	
	public function createNew($login, $action, $smsCodeExpire, $data = null) {
		$login = LoginHelper::getPhone($login);
		$this->cleanOld($login, $action, $smsCodeExpire);
		$entityArray = compact(['login', 'action', 'data']);
		$entityArray['code'] = ConfirmHelper::generateCode();
		try {
			$this->repository->oneByLoginAndAction($login, $action);
			$error = new ErrorCollection();
			$error->add('login', 'account/auth', 'already_sended_code');
			throw new UnprocessableEntityHttpException($error);
		} catch(NotFoundHttpException $e) {
			return parent::create($entityArray);
		}
	}
	
	private function cleanOld($login, $action, $smsCodeExpire) {
		$login = LoginHelper::getPhone($login);
		if(!empty($smsCodeExpire)) {
			$this->repository->cleanOld($login, $action, $smsCodeExpire);
		}
	}
	
}
