<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField; ?>
<div class="main-container page-container">
<?php ActiveForm::begin(['id' => 'filter-form',  'action' => ['/site/signup'], 'options' => ['class' => 'choose-form', 'name' => 'test']]); ?>
	<h1>Кто я?</h1>
	<div class="form_radio_btn">
		<input required id="radio-3" type="radio" name="role" value="3">
		<label for="radio-3">Исполнитель</label>
	</div>
	<div class="form_radio_btn">
		<input required id="radio-4" type="radio" name="role" value="4">
		<label for="radio-4">Заказчик</label>
	</div>
	<div>
		<button class="button width100 mb-1" type="submit">Продолжить</button>
	</div>
<?php ActiveForm::end(); ?>
</div>