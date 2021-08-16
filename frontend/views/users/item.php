<?php
use yii\helpers\Url;
?>

<div class="content-view__feedback-card user__search-wrapper">
  <div class="feedback-card__top">
    <div class="user__search-icon">
      <a href="<?= Url::to(['users/view', 'id' => $user->id]);?>"><img src="<?= Url::to([$user->avatar || $user->avatar != "" ? $user->avatar : './img/no-photo.png'])?>" width="65" height="65"></a>
      <span><?= count($user->respond) ?? 0 ?> заданий</span>
      <span><?= count($user->review) ?? 0 ?> отзывов</span>
    </div>
    <div class="feedback-card__top--name user__search-card">
      <p class="link-name"><a href="<?= Url::to(['users/view', 'id' => $user->id]);?>" class="link-regular"><?=$user->name?></a></p>
      <?php if ($user->rating) { ?>
        <?php for ($i = 0; $i < $user->rating; $i++) { ?>
         <span></span> 
        <?php } ?>
      <?php } ?>
      <?php if ($user->rating < 5) { ?>
        <?php for ($i = 0; $i < (5 - $user->rating); $i++) { ?>
         <span class="star-disabled"></span>
        <?php } ?>
      <?php } ?>
      <b><?= $user->rating ?? 0 ?></b>
      <p class="user__search-content">
        <?=$user->about?>
      </p>
    </div>
    <span class="new-task__time"><?= ($user->last_online < 60*60*24*365) ? Yii::$app->formatter->asRelativeTime($user->last_online) : Yii::$app->formatter->asDate($user->last_online);?></span>
  </div>
  <div class="link-specialization user__search-link--bottom">
    <?php foreach ($user->category as $key => $category): ?>
      <a href="<?= Url::to(['/users', 'category' => $category->id]);?>" class="link-regular"><?=$category->name?></a>
    <?php endforeach; ?>
  </div>
</div>