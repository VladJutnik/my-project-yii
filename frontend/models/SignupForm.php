<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\rbac\DbManager;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот логин уже занят.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот адрес электронной почты уже занят.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = 10;//актив
        $user->generateEmailVerificationToken();
        if ($user->save())
        {
            $r = new DbManager();
            $r->init();
            $assign = $r->createRole('admin');
            $r->assign($assign, $user->id);

            $message = Yii::$app->mailer->compose();
            $message->setFrom(['1@niig.su' => '1@niig.su']);
            $message->setTo($user->email)
                ->setSubject('Программа тест')
                ->setHtmlBody(
                    '<p>Здравствуйте, ' . $user->username .
                    '!</p><p>Вы были зарегистрированы в программе. 
                    <p>Логин: ' . $this->username . ' </p> 
                    <p>Пароль: ' . $this->password . ' </p>');
            return $message->send();
        }
        else
        {
            return null;
        }
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
