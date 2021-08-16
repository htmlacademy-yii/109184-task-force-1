<?php
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use frontend\models\City;
use yii\jui\DatePicker;
use yii\helpers\Url;
?>

<div class="main-container page-container">
  <section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php $form = ActiveForm::begin(['id' => 'account', 'options' => ['enctype' => 'multipart/form-data']]); ?>
      <div class="account__redaction-section">
        <h3 class="div-line">Настройки аккаунта</h3>
        <div class="account__redaction-section-wrapper">
          <div class="account__redaction-avatar">
            <img id="avatar" src="<?= Url::to([($model->avatar) ? $model->avatar : './img/no-photo.png']);?>" width="150" height="150">
            <?= $form->field($model, 'avatarUpload')->fileInput(['id' => 'upload-avatar'])->label('Сменить аватар', ['class' => 'link-regular']); ?>
          </div>
          <div class="account__redaction">
            <div class="account__input account__input--name">
              <?= $form->field($model, 'name')->textInput(['class' => 'input textarea width100', 'placeholder' => 'Титов Денис', 'disabled' => 'disabled'])->label('Ваше имя'); ?>
            </div>
            <div class="account__input account__input--email">
              <?= $form->field($model, 'email')->textInput(['class' => 'input textarea width100', 'placeholder' => 'DenisT@bk.ru'])->label('email'); ?>
            </div>
            <div class="account__input account__input--name">
              <?= $form->field($model, 'city_id')->dropdownList(City::find()->select(['name', 'id'])->indexBy('id')->column(),
                  ['prompt'=>'Выберите город', 'class' => 'multiple-select input multiple-select-big width100']
              )->label('Город');?>
            </div>
            <div class="account__input account__input--date">
              <?= $form->field($model, 'birthdate')->widget(\yii\jui\DatePicker::classname(), [
                'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'value' => \Yii::$app->formatter->asDate($model->birthdate, 'php:d.m.Y'),
                'options' => [
                  'class' => 'input textarea width100',
                  'placeholder' => 'ГГГГ-ММ-ДД',
                  'autocomplete' => 'off'
                ]
            ])->label('День рождения') ?>
            </div>
            <div class="account__input account__input--info">
              <?= $form->field($model, 'about')->textarea(['rows' => 11, 'class' => 'input textarea width100', 'placeholder' => 'Place your text'])->label('Информация о себе'); ?>
            </div>
          </div>
        </div>
        <h3 class="div-line">Выберите свои специализации</h3>
        <div class="account__redaction-section-wrapper">
          <div class="search-task__categories account_checkbox--bottom">
            <?php foreach ($categories as $attr => $label): ?>
              <div>
                <label class="checkbox__legend">
                  <input class="visually-hidden checkbox__input" type="checkbox" name="categories[]" value="<?= $label->id ?>" <?= (in_array($label->id, $userCategories)) ? 'checked' : ''?>>
                  <span><?= $label->name ?></span>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <h3 class="div-line">Безопасность</h3>
        <div class="account__redaction-section-wrapper account__redaction">
          <div class="account__input">
            <?= $form->field($model, 'password')->passwordInput(['class' => 'input textarea width100'])->label('Новый пароль'); ?>
          </div>
          <div class="account__input">
            <?= $form->field($model, 'password_repeat')->passwordInput(['class' => 'input textarea width100'])->label('Повтор пароля'); ?>
          </div>
        </div>

        <h3 class="div-line">Фото работ</h3>

        <div class="account__redaction-section-wrapper account__redaction">
            <?= $form->field($model, 'portfolioUpload[]')->fileInput(['multiple' => 'multiple', 'class' => 'portfolio'])->label('Выбрать фотографии'); ?>
            <div class="portfolio-list">
              <?php if (!empty($model->gallery)) { ?>
                <?php foreach ($model->gallery as $img) { ?>
                  <div class="img-wrap">
                    <button type="button" class="clear" data-id="<?= $img->id?>"></button>
                    <img data-fancybox src="<?=  Url::to([$img->link])?>" alt="" width="100">
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
        </div>

        <h3 class="div-line">Контакты</h3>
        <div class="account__redaction-section-wrapper account__redaction">
          <div class="account__input">
            <?= $form->field($model, 'phone')->textInput(['class' => 'input textarea width100', 'placeholder' => '8 (555) 187 44 87'])->label('Телефон'); ?>
          </div>
          <div class="account__input">
            <?= $form->field($model, 'skype')->textInput(['class' => 'input textarea width100', 'placeholder' => 'DenisT'])->label('Skype'); ?>
          </div>
          <div class="account__input">
            <?= $form->field($model, 'telegram')->textInput(['class' => 'input textarea width100', 'placeholder' => '@DenisT'])->label('Telegram'); ?>
          </div>
        </div>
        <h3 class="div-line">Настройки сайта</h3>
        <h4>Уведомления</h4>
        <div class="account__redaction-section-wrapper account_section--bottom">
          <div class="search-task__categories account_checkbox--bottom">
            <label class="checkbox__legend">
              <input class="visually-hidden checkbox__input" type="checkbox" name="new_message" value="1" <?= ($model->new_message == 1) ? 'checked' : ''?>>
              <span>Новое сообщение</span>
            </label>
            <label class="checkbox__legend">
              <input class="visually-hidden checkbox__input" type="checkbox" name="task_actions" value="1" <?= ($model->task_actions == 1) ? 'checked' : ''?>>
              <span>Действия по заданию</span>
            </label>
            <label class="checkbox__legend">
              <input class="visually-hidden checkbox__input" type="checkbox" name="new_review" value="1" <?= ($model->new_review == 1) ? 'checked' : ''?>>
              <span>Новый отзыв</span>
            </label>
          </div>
          <div class="search-task__categories account_checkbox account_checkbox--secrecy">
            <label class="checkbox__legend">
              <input class="visually-hidden checkbox__input" type="checkbox" name="show_contacts" value="1" <?= ($model->show_contacts == 1) ? 'checked' : ''?>>
              <span>Показывать мои контакты только заказчику</span>
            </label>
            <label class="checkbox__legend">
              <input class="visually-hidden checkbox__input" type="checkbox" name="show_profile" value="1" <?= ($model->show_profile == 1) ? 'checked' : ''?>>
              <span>Не показывать мой профиль</span>
            </label>
          </div>
        </div>
      </div>
      <?= Html::submitButton('Сохранить изменения', ['class' => 'button']) ?>
    <?php ActiveForm::end() ?>
  </section>
</div>
<script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  document.querySelector('input[type="file"]').addEventListener('change', function() {
      if (this.files && this.files[0]) {
          var img = document.querySelector('#avatar');
          img.onload = () => {
              URL.revokeObjectURL(img.src);  // no longer needed, free memory
          }

          img.src = URL.createObjectURL(this.files[0]); // set src to blob url
      }
  });  
  var imgIndex = 0;
  document.querySelector('.portfolio').addEventListener('change', function() {
      if (this.files) {
        let files = this.files;
        
        for (let i = 0; i < this.files.length; i++) {
          var img = document.createElement('img'); 
          img.src = URL.createObjectURL(this.files[i]);
          img.onload = () => {
              URL.revokeObjectURL(img.src);  // no longer needed, free memory
          } 
          img.width = '100';
          img.style.marginLeft = "10px";
          img.setAttribute('data-fancybox', '');

          var wrap = document.createElement("div");
          wrap.className = "img-wrap wrap-id-" + imgIndex; 
          document.querySelector(".portfolio-list").appendChild(wrap).appendChild(img)

          var button = document.createElement("button");
          button.className = "clear";
          button.setAttribute('type', 'button');
          button.setAttribute('data-id', '0');

          document.querySelector(".wrap-id-" + imgIndex).prepend(button)
          imgIndex++;
        }
      }
  });  

</script>
<?php
$script = <<< JS
$('body').on('click', '.clear', function(e) {
  e.preventDefault();
    let butt = $(this)
    if (butt.data('id') == 0) {
      butt.next().remove()
      butt.remove()
    }
    $.ajax({
       url: 'account/delete',
       data: {id: butt.data('id')},
       success: function(data) {
          butt.next().remove()
          butt.remove()
       }
    });
});
JS;
$this->registerJs($script, View::POS_READY);