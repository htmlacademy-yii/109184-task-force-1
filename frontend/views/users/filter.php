<?php

use yii\widgets\ActiveForm;
use yii\widgets\ActiveField; ?>

<div class="search-task__wrapper">
  <?php ActiveForm::begin(['id' => 'signup-form', 'options' => ['class' => 'search-task__form', 'name' => 'users']]); ?>
    <fieldset class="search-task__categories">
        <legend>Категории</legend>
    	<?php foreach ($model['category'] as $attr => $label): ?>
    		<label class="checkbox__legend">
				<input class="visually-hidden checkbox__input" type="checkbox" name="category[]" value="<?= $label->id ?>" <?= (isset($filter['category']) && in_array($label->id, $filter['category'])) ? 'checked' : ''?>>
				<span><?= $label->name ?></span>
			</label>
		<?php endforeach; ?>
    </fieldset>
    <fieldset class="search-task__categories">
      <legend>Дополнительно</legend>
      <label class="checkbox__legend">
        <input class="visually-hidden checkbox__input" type="checkbox" name="free" value="1" <?= (isset($filter['free'])) ? 'checked' : ''?>>
        <span>Сейчас свободен</span>
      </label>
      <label class="checkbox__legend">
        <input class="visually-hidden checkbox__input" type="checkbox" name="online" value="1" <?= (isset($filter['online'])) ? 'checked' : ''?>>
        <span>Сейчас онлайн</span>
      </label>
      <label class="checkbox__legend">
        <input class="visually-hidden checkbox__input" type="checkbox" name="has_reviews" value="1" <?= (isset($filter['has_reviews'])) ? 'checked' : ''?>>
        <span>Есть отзывы</span>
      </label>
      <label class="checkbox__legend">
        <input class="visually-hidden checkbox__input" type="checkbox" name="favourite" value="1" <?= (isset($filter['favourite'])) ? 'checked' : ''?>>
        <span>В избранном</span>
      </label>
    </fieldset>
    <div class="field-container">
      <label class="search-task__name" for="search">Поиск по имени</label>
      <input class="input-middle input" id="search" type="search" name="sQuery" placeholder="" value="<?= (isset($filter['sQuery'])) ? $filter['sQuery'] : '' ?>">
    </div>
    <button class="button" type="submit">Искать</button>
  <?php ActiveForm::end(); ?>
</div>