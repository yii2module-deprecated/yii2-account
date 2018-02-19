<?php

namespace yii2module\account\domain\v1\repositories\tps;

use yii2module\account\domain\v1\helpers\ConfirmHelper;
use yii2module\account\domain\v1\helpers\LoginHelper;
use yii2module\account\domain\v1\interfaces\repositories\RegistrationInterface;
use yii2lab\domain\repositories\TpsRepository;
use Yii;

class RegistrationRepository extends TpsRepository implements RegistrationInterface {

	public function generateActivationCode() {
		return ConfirmHelper::generateCode();
	}
	
	public function create($data) {
		Yii::$app->account->login->create($data);
	}
	
	public function isExists($login) {
		return $this->domain->repositories->login->isExistsByLogin($login);
	}
	
}