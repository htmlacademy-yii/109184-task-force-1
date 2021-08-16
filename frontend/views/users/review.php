<?php
use yii\helpers\Url;
?>

<div class="feedback-card__reviews">
  <p class="link-task link">Задание <a href="<?= Url::to(['tasks/view', 'id' => $review->task->id]);?>" class="link-regular">«<?= $review->task->title?>»</a></p>
  <div class="card__review">
    <a href="<?= Url::to(['/users/view', 'id' => $review->user->id]);?>"><img src="<?=  Url::to([$review->user->avatar])?>" width="55" height="54"></a>
    <div class="feedback-card__reviews-content">
      <p class="link-name link"><a href="<?= Url::to(['/users/view', 'id' => $review->user->id]);?>" class="link-regular"><?= $review->user->name?></a></p>
      <p class="review-text">
        <?= $review->text?>
      </p>
    </div>
    <div class="card__review-rate">
      <p class="five-rate big-rate"><?= $review->rate?><span></span></p>
    </div>
  </div>
</div>