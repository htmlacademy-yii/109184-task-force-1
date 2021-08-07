<?php
use yii\helpers\Url;
?>

<div class="new-task__card">
    <div class="new-task__title">
      <a href="<?= Url::to(['tasks/view', 'id' => $task->id]);?>" class="link-regular"><h2><?=$task->title?></h2></a>
      <a class="new-task__type link-regular" href="<?= Url::to(['/tasks', 'category' => $task->category->id]);?>"><p><?=$task->category->name?></p></a>
    </div>
    <div class="task-status <?=$task->statusName->description?>-status"><?= $task->statusesList[$task->statusName->description] ?></div>
    <p class="new-task_description">
      <?=$task->description?>
    </p>
    <div class="feedback-card__top ">
    <a href="#"><img src="<?= $task->executant->user->avatar || $task->executant->user->avatar != "" ? $task->executant->user->avatar : './img/no-photo.png'?>" width="36" height="36"></a>
    <div class="feedback-card__top--name my-list__bottom">
      <p class="link-name"><a href="<?= Url::to(['users/view', 'id' => $task->executant->user_id]);?>" class="link-regular"><?= $task->executant->user->login?></a></p>
      <a href="#" class="my-list__bottom-chat  my-list__bottom-chat--new"><b><?= count($task->messages) ?></b></a>
      <?php if ($task->executant->user->rating) { ?>
        <?php for ($i = 0; $i < $task->executant->user->rating; $i++) { ?>
         <span></span> 
        <?php } ?>
      <?php } ?>
      <?php if ($task->executant->user->rating < 5) { ?>
        <?php for ($i = 0; $i < (5 - $task->executant->user->rating); $i++) { ?>
         <span class="star-disabled"></span>
        <?php } ?>
      <?php } ?>
      <b><?= $task->executant->user->rating ?? 0?></b>
    </div>
  </div>
</div>
