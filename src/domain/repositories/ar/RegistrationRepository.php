<?php

namespace yii2module\account\domain\repositories\ar;

use yii2module\account\domain\helpers\ConfirmHelper;
use yii2module\account\domain\interfaces\repositories\RegistrationInterface;
use yii2lab\domain\repositories\TpsRepository;
use Yii;

class RegistrationRepository extends TpsRepository implements RegistrationInterface {

	public function generateActivationCode() {
		return ConfirmHelper::generateCode();;
	}
	
	public function create($data) {
		Yii::$app->account->login->create($data);
	}
	
	public function isExists($login) {
		return $this->domain->repositories->login->isExistsByLogin($login);
	}
	
}