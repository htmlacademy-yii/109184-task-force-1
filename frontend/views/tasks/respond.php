<?php
use yii\helpers\Url;
?>

<div class="content-view__feedback-card">
  <div class="feedback-card__top">
    <a href="<?= Url::to(['users/view', 'id' => $respond->user->id]);?>"><img src="<?=$respond->user->avatar || $respond->user->avatar != "" ? $respond->user->avatar : '/img/no-photo.png'?>" width="55" height="55"></a>
    <div class="feedback-card__top--name">
      <p><a href="<?= Url::to(['users/view', 'id' => $respond->user->id]);?>" class="link-regular"><?=$respond->user->name?></a></p>
      <?php if ($respond->user->rating) { ?>
        <?php for ($i = 0; $i < $respond->user->rating; $i++) { ?>
         <span></span> 
        <?php } ?>
      <?php } ?>
      <?php if ($respond->user->rating < 5) { ?>
        <?php for ($i = 0; $i < (5 - $respond->user->rating); $i++) { ?>
         <span class="star-disabled"></span>
        <?php } ?>
      <?php } ?>
      <b><?= $respond->user->rating ?? 0 ?></b>
    </div>
    <span class="new-task__time"><?= Yii::$app->formatter->asDate($respond->created_at, 'php:d.m.Y H:i');?></span>
  </div>
  <div class="feedback-card__content">
    <p>
      <?=$respond->comment?>
    </p>
    <span><?=$respond->price?> ₽</span>
  </div>
  <?php if (\Yii::$app->user->identity->role_id == 4 && $task->user_created == \Yii::$app->user->identity->id) { ?>
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
