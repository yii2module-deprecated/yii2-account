<?php

namespace yii2module\account\domain\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii2module\account\domain\interfaces\services\LoginInterface;
use yii2module\account\domain\forms\LoginForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\ActiveBaseService;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class LoginService
 *
 * @package yii2module\account\domain\services
 *
 * @property \yii2module\account\domain\interfaces\repositories\LoginInterface $repository
 */
class PartnerService extends ActiveBaseService {
	
	
	public function getPrefixByPartnerLogin($partnerLogin) {
		return $this->repository->getPrefixByPartnerLogin($partnerLogin);
	}

	
}
