<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField; ?>

<h2>Завершение задания</h2>
<p class="form-modal-description">Задание выполнено?</p>
<?php $form = ActiveForm::begin(['id' => 'form-request', 'action' => ['request']]); ?>
  <?= $form->field($requestForm, 'completion')->radio(['label' => false, 'id' => 'completion-radio--yes', 'value' => 'yes', 'class' => 'visually-hidden completion-input completion-input--yes'])->label('Да', ['class' => 'completion-label completion-label--yes']) ?>
  <?= $form->field($requestForm, 'completion')->radio(['label' => false, 'id' => 'completion-radio--yet', 'value' => 'difficulties', 'class' => 'visually-hidden completion-input completion-input--difficult'])->label('Возникли проблемы', ['class' => 'completion-label completion-label--difficult']) ?>
  <p>
    <?= $form->field($requestForm, 'comment')->textarea(['rows' => 4, 'class' => 'input textarea width100', 'placeholder' => 'Place your text'])->label('Комментарий', ['class' => 'form-modal-description width100']); ?>
  </p>
  <p class="form-modal-description">
    Оценка
  <div class="feedback-card__top--name completion-form-star">
    <span class="star-disabled"></span>
    <span class="star-disabled"></span>
    <span class="star-disabled"></span>
    <span class="star-disabled"></span>
    <span class="star-disabled"></span>
  </div>
  </p>
  <?= Html::hiddenInput('rating', '', ['id' => 'rating']);?>
  <?= Html::hiddenInput('task_id', $task_id);?>
  <?= Html::submitButton('Отправить', ['class' => 'button modal-button']) ?>
<?php ActiveForm::end(); ?>
<button class="form-modal-close" type="button">Закрыть</button>