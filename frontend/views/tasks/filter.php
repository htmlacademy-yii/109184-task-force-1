<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField; ?>

<div class="search-task__wrapper">
  <?php ActiveForm::begin(['id' => 'filter-form', 'options' => ['class' => 'search-task__form', 'name' => 'test']]); ?>
    <fieldset class="search-task__categories">
        <legend>Категории</legend>
    	<?php foreach ($model['category'] as $attr => $label): ?>
    		<label class="checkbox__legend">
				<input class="visually-hidden checkbox__input" type="checkbox" name="category[]" value="<?= $label->id ?>" <?= (isset($filter['category']) && in_array($label->id, (array)$filter['category'])) ? 'checked' : ''?>>
				<span><?= $label->name ?></span>
			</label>
		<?php endforeach; ?>
    </fieldset>
    <!-- <fieldset class="search-task__categories">
        <legend>Дополнительно</legend>
      	<?php foreach ($model['worktype'] as $attr => $label): ?>
      		<div>
    	    	<label class="checkbox__legend">
    					<input class="visually-hidden checkbox__input" type="checkbox" name="work_type[]" value="<?= $label->id ?>" <?= (isset($filter['work_type']) && in_array($label->id, $filter['work_type'])) ? 'checked' : ''?>>
    					<span><?= $label->name ?></span>
    				</label>
    			</div>
		    <?php endforeach; ?>
    </fieldset> -->
    <div class="field-container">
      <label class="search-task__name" for="period">Период</label>
      <select class="multiple-select input" id="period" size="1" name="period">
        <option value="">Не выбрано</option>
      	<?php foreach ($model['period'] as $attr => $label): ?>
        	<option <?= (isset($filter['period']) && $filter['period'] == $attr) ? 'selected' : '' ?> value="<?= $attr ?>"><?= $label ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field-container">
      <label class="search-task__name" for="search">Поиск по названию</label>
      <input class="input-middle input" id="search" type="search" name="sQuery" placeholder="" value="<?= (isset($filter['sQuery'])) ? $filter['sQuery'] : '' ?>">
    </div>
    <button class="button width100 mb-1" type="submit">Искать</button>
    <?= Html::resetButton('Сбросить', [ 'class'=> "button grey-button width100", 'onclick' => 'window.location.replace(window.location.pathname);']); ?>
  <?php ActiveForm::end(); ?>
</div>