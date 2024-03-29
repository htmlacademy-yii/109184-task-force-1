<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\Category;
use yii\web\View;
use yii\web\JsExpression;
use yii\jui\AutoComplete;
use yii\helpers\Url;
use yii\jui\DatePicker;
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
          <?= $form->field($model, 'filesUpload[]')->fileInput(['multiple' => 'multiple', 'class' => 'taskfiles'])->label('Файлы')->hint('Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу'); ?>
          <div class="files-list">
          </div>
          <!-- <div class="create__file">
          </div> -->
        </div>

        <div class="field-container">
          <?= $form->field($model, 'addressText')->widget(\yii\jui\AutoComplete::classname(), [
              'clientOptions' => [
                  // 'source' => Url::to(['/geo']),
                  'source' => new JsExpression("function(request, response) {
                        $.getJSON('".Url::to(['/geo'])."', {
                            term: request.term
                        }, function(data) {
                              var suggestions = [];
                              if (typeof data.response !== 'undefined') {
                                  jQuery.each(data.response.GeoObjectCollection.featureMember, function(index, ele) {
                                      suggestions.push({
                                          label: ele.GeoObject.metaDataProperty.GeocoderMetaData.text,
                                          value: ele.GeoObject.metaDataProperty.GeocoderMetaData.text,
                                          pos: ele.GeoObject.Point.pos,
                                          city: ele.GeoObject.name
                                      });
                                  });
                                } else {
                                  jQuery.each(data, function(index, ele) {
                                      suggestions.push({
                                          label: ele.name,
                                          value: ele.name,
                                          pos: ele.long + ' ' + ele.lat, 
                                          city: ele.city.name
                                      });
                                  });
                                }
                            response(suggestions)
                          });
                          
                      }"),
                  'minLength'=>'2',
                  'select' => new JsExpression("
                    function(event, ui) {
                      console.log(ui)
                      jQuery('#position').val(ui.item.pos)
                      jQuery('#city').val(ui.item.city)
                    }")
              ],
              'options'=>[
                  'class' => 'input-navigation input-middle input width100',
                  'autocomplete' => "off",
                  'placeholder' => 'Санкт-Петербург, Калининский район'
              ]
          ])->label('Локация')->hint('Укажите адрес исполнения, если задание требует присутствия') ?>
          <?= Html::hiddenInput('position', '', ['id' => 'position']);?>
          <?= Html::hiddenInput('city', '', ['id' => 'city']);?>
        </div>
        <div class="create__price-time">
          <div class="field-container create__price-time--wrapper">
            <?= $form->field($model, 'price')->textInput(['class' => 'input textarea input-money width100', 'placeholder' => '1000'])->hint('Не заполняйте для оценки исполнителем')->label('Бюджет'); ?>
          </div>
          <div class="field-container create__price-time--wrapper">
            <?= $form->field($model, 'expire_date')->widget(\yii\jui\DatePicker::classname(), [
                'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'options' => [
                  'class' => 'input-middle input input-date width100',
                  'placeholder' => 'ГГГГ-ММ-ДД',
                  'autocomplete' => 'off'
                ]
            ])->hint('Укажите крайний срок исполнения')->label('Сроки исполнения') ?>
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

<script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  var imgIndex = 0;
  document.querySelector('.taskfiles').addEventListener('change', function() {
      if (this.files) {
        let files = this.files;
        
        for (let i = 0; i < files.length; i++) {
          var img = document.createElement('img'); 
          
          img.onload = () => {
              URL.revokeObjectURL(img.src);  // no longer needed, free memory
          } 
          img.src = URL.createObjectURL(files[i]);
          img.width = '100';
          img.style.marginLeft = "10px";

          var wrap = document.createElement("div");
          wrap.className = "img-wrap wrap-id-" + imgIndex; 
          document.querySelector(".files-list").appendChild(wrap).appendChild(img)


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