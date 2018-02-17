<?php

namespace yii2module\account\domain\repositories\core;

use yii2module\account\domain\interfaces\repositories\RegistrationInterface;
use yii2module\account\domain\repositories\tps\RegistrationRepository as TpsRegistrationRepository;

class RegistrationRepository extends TpsRegistrationRepository implements RegistrationInterface {

}