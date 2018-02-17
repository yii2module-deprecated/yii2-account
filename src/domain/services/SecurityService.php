<?php

namespace yii2module\account\domain\services;

use yii2module\account\domain\forms\ChangeEmailForm;
use yii2module\account\domain\forms\ChangePasswordForm;
use yii2lab\domain\services\BaseService;

class SecurityService extends BaseService {
	
	public function changeEmail($body) {
		$body = $this->validateForm(ChangeEmailForm::className(), $body);
		$this->repository->changeEmail($body['password'], $body['email']);
	}
	
	public function changePassword($body) {
		
		$body = $this->validateForm(ChangePasswordForm::className(), $body);
		$this->repository->changePassword($body['password'], $body['new_password']);
	}

}
