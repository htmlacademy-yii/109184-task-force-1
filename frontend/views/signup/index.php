<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\City;
?>

<div class="main-container page-container">
  <section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
      <?php $form = ActiveForm::begin(['id' => 'signup-form', 'options' => ['class' => 'registration__user-form form-create', 'name' => 'signup']]); ?>
        <div class="field-container field-container--registration">
          <?= $form->field($model, 'email')->textInput(['class' => 'input textarea width100'])->label('Электронная почта'); ?>
        </div>
        <div class="field-container field-container--registration">
          <?= $form->field($model, 'login')->textInput(['class' => 'input textarea width100'])->label('Ваш логин'); ?>
        </div>
        <div class="field-container field-container--registration">
          <?= $form->field($model, 'city_id')->dropdownList(
              City::find()->select(['name', 'id'])->indexBy('id')->column(),
              ['prompt'=>'Выберите город', 'class' => 'multiple-select input town-select registration-town width100']
          )->label('Город проживания');?>
        </div>
        <div class="field-container field-container--registration">
          <?= $form->field($model, 'password')->passwordInput(['class' => 'input textarea width100'])->label('Пароль'); ?>
        </div>
        <?= Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration']) ?>
      <?php ActiveForm::end() ?>
    </div>
  </section>
</div>