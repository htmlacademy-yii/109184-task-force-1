<?php
use yii\helpers\Url;
?>

<div class="content-view__feedback-card">
  <div class="feedback-card__top">
    <a href="<?= Url::to(['users/view', 'id' => $respond->user->id]);?>"><img src="<?=$respond->user->avatar?>" width="55" height="55"></a>
    <div class="feedback-card__top--name">
      <p><a href="<?= Url::to(['users/view', 'id' => $respond->user->id]);?>" class="link-regular"><?=$respond->user->name?></a></p>
      <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
      <b><?=$respond->user->rating?></b>
    </div>
    <span class="new-task__time"><?= Yii::$app->formatter->asDate($respond->created_at, 'php:d.m.Y H:i');?></span>
  </div>
  <div class="feedback-card__content">
    <p>
      <?=$respond->comment?>
    </p>
    <span><?=$respond->price?> ₽</span>
  </div>
  <?php if (\Yii::$app->user->identity->role_id == 4) { ?>
    <div class="feedback-card__actions">
      <?php if ($respond->is_accepted !== null || $task->status == 2) { ?>
        <?php if ($respond->is_accepted == 0) { ?>
          <span>Отказано</span>
        <?php } else if ($respond->is_accepted == 1) { ?>
          <span>Выбран</span>
        <?php } ?>
      <?php } else { ?>
        <a class="button__small-color response-button button" href="<?= Url::to(['tasks/accept', 'id' => $respond->id]);?>"
           type="button">Подтвердить</a>
        
        <a class="button__small-color refusal-button button" href="<?= Url::to(['tasks/refuse', 'id' => $respond->id]);?>"
           type="button">Отказать</a>
      <?php } ?>
    </div>
  <?php } ?>
</div>
