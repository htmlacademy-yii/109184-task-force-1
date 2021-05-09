<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\Category;
?>

<div class="main-container page-container">
  <section class="create__task">
    <h1>Публикация нового задания</h1>
    <div class="create__task-main">
      <?php $form = ActiveForm::begin(['id' => 'task-form', 'options' => ['class' => 'create__task-form form-create', 'name' => 'task-form', 'enctype' => 'multipart/form-data']]); ?>
        <div class="field-container">
          <?= $form->field($model, 'title')->textInput(['class' => 'input textarea width100', 'placeholder' => 'Повесить полку'])->hint('Кратко опишите суть работы')->label('Мне нужно'); ?>
        </div>
        <div class="field-container">
          <?= $form->field($model, 'description')->textarea(['rows' => 11, 'class' => 'input textarea width100', 'placeholder' => 'Place your text'])->hint('Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться')->label('Подробности задания'); ?>
        </div>
        <div class="field-container">
          <?= $form->field($model, 'category_id')->dropdownList(
              Category::find()->select(['name', 'id'])->indexBy('id')->column(),
              ['prompt'=>'Выберите категорию', 'class' => 'multiple-select input multiple-select-big width100']
          )->hint('Выберите категорию')->label('Категория');?>
        </div>
        <div class="field-container">
          <?= $form->field($model, 'filesUpload[]')->fileInput(['multiple' => 'multiple', 'class' => ''])->label('Файлы'); ?>
          <!-- <div class="create__file">
          </div> -->
          <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
        </div>
        <div class="field-container">
          <label for="13">Локация</label>
          <input class="input-navigation input-middle input" id="13" type="search" name="q"
                 placeholder="Санкт-Петербург, Калининский район">
          <span>Укажите адрес исполнения, если задание требует присутствия</span>
        </div>
        <div class="create__price-time">
          <div class="field-container create__price-time--wrapper">
            <?= $form->field($model, 'price')->textInput(['class' => 'input textarea input-money width100', 'placeholder' => '1000'])->hint('Не заполняйте для оценки исполнителем')->label('Бюджет'); ?>
          </div>
          <div class="field-container create__price-time--wrapper">
            <?= $form->field($model, 'expire_date')->textInput(['class' => 'input-middle input input-date width100', 'placeholder' => 'YYYY-MM-DD'])->hint('Укажите крайний срок исполнения')->label('Сроки исполнения'); ?>
          </div>
        </div>

      <?php ActiveForm::end() ?>
      <div class="create__warnings">
        <div class="warning-item warning-item--advice">
          <h2>Правила хорошего описания</h2>
          <h3>Подробности</h3>
          <p>Друзья, не используйте случайный<br>
            контент – ни наш, ни чей-либо еще. Заполняйте свои
            макеты, вайрфреймы, мокапы и прототипы реальным
            содержимым.</p>
          <h3>Файлы</h3>
          <p>Если загружаете фотографии объекта, то убедитесь,
            что всё в фокусе, а фото показывает объект со всех
            ракурсов.</p>
        </div>
        <?php if (!empty($errors)) { ?>
          <div class="warning-item warning-item--error">
            <h2>Ошибки заполнения формы</h2>
            <?php foreach ($errors as $name => $errorList) { ?>
            <h3><?= $model->getAttributeLabel($name) ?></h3>
              <?php foreach($errorList as $error) { ?>
                <p><?= $error ?></p>
              <?php } ?>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
    </div>
    <?= Html::submitButton('Опубликовать', ['class' => 'button', 'form' => 'task-form']) ?>
  </section>
</div>