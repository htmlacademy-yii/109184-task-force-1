<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField; ?>

<h2>Отказ от задания</h2>
<p>
  Вы собираетесь отказаться от выполнения задания.
  Это действие приведёт к снижению вашего рейтинга.
  Вы уверены?
</p>
<button class="button__form-modal button" id="close-modal"
        type="button">Отмена
</button>

<?php $form = ActiveForm::begin(['id' => 'form-refuse', 'action' => ['refuse'], 'method' => 'post']); ?>
	<?= Html::hiddenInput('task_id', $task_id);?>
  	<?= Html::submitButton('Отказаться', ['class' => 'button__form-modal refusal-button button']) ?>
<?php ActiveForm::end(); ?>
<button class="form-modal-close" type="button">Закрыть</button>