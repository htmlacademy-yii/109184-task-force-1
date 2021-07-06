<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => ['login'], 'options' => ['name' => 'login']]); ?>
            <?= $form->field($model, 'email')->textInput(['class' => 'enter-form-email input input-middle width100'])->label('E-mail', ["class" => "form-modal-description"]) ?>
            <?= $form->field($model, 'password')->passwordInput(['class' => 'enter-form-email input input-middle width100'])->label('Пароль', ["class" => "form-modal-description"]) ?>
            <?= yii\authclient\widgets\AuthChoice::widget([
                         'baseAuthUrl' => ['site/auth'],
                         'popupMode' => false,
                    ]) ?>   
                <?//= $form->field($model, 'rememberMe')->checkbox() ?>

                <!-- <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    <br>
                    Need new verification email? <?= Html::a('Resend', ['site/resend-verification-email']) ?>
                </div> -->

            <?= Html::submitButton('Войти', ['class' => 'button']) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
