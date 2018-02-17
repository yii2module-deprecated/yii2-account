<?php

namespace yii2module\account\domain\repositories\ar;

use Yii;
use yii\rbac\Assignment;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2module\account\domain\entities\LoginEntity;
use yii2module\account\domain\helpers\LoginEntityFactory;
use yii2lab\domain\repositories\ActiveArRepository;
use yii\helpers\ArrayHelper;
use yii2module\account\domain\interfaces\repositories\LoginInterface;
use yii2module\account\domain\models\User;

class PartnerRepository extends ActiveArRepository{

	protected $modelClass = 'yii2module\account\domain\models\PartnerPrefixes';

	public function getPrefixByPartnerLogin($partnerLogin) {
		$query = Query::forge();
		$query->where('partner_login', $partnerLogin);
		return $this->one($query);
	}

}