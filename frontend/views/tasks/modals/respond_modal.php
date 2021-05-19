<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField; ?>

<h2>Отклик на задание</h2>
<?php $form = ActiveForm::begin(['id' => 'form-response', 'action' => ['respond']]); ?>
  <?= Html::hiddenInput('task_id', $task_id);?>
  <p>
    <?= $form->field($responseForm, 'price')->textInput(['class' => 'response-form-payment input input-middle input-money', 'id' => 'response-payment'])->label('Ваша цена', ['class' => 'form-modal-description width100']) ?>
  </p>
  <p>
    <?= $form->field($responseForm, 'comment')->textarea(['rows' => 4, 'class' => 'input textarea width100', 'placeholder' => 'Place your text'])->label('Комментарий', ['class' => 'form-modal-description width100']); ?>
  </p>
  <?= Html::submitButton('Отправить', ['class' => 'button modal-button']) ?>
<?php ActiveForm::end(); ?>
<button class="form-modal-close" type="button">Закрыть</button>