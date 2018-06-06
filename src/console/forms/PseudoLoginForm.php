<?php

namespace yii2module\account\console\forms;

use Yii;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii\base\Model;
use yii2module\account\domain\v2\validators\LoginValidator;
use domain\v1\account\models\Subjects;
use domain\v1\account\models\User;
use domain\v1\account\models\TypeSubject;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
use common\helpers\RBACRoles;

class PseudoLoginForm extends Model
{
    public $login;
    public $email;
	public $parentLogin;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'email', 'parentLogin'], 'trim'],
            [['login', 'email', 'parentLogin'], 'required'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login'         => Yii::t('main', 'login'),
        ];
    }
 
 
}
