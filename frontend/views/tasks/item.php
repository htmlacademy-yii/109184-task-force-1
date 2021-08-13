<?php
use yii\helpers\Url;
?>

<div class="new-task__card">
    <div class="new-task__title">
      <a href="<?= Url::to(['tasks/view', 'id' => $task->id]);?>" class="link-regular"><h2><?=$task->title?></h2></a>
      <a class="new-task__type link-regular" href="<?= Url::to(['/tasks', 'category' => $task->category->id]);?>"><p><?=$task->category->name?></p></a>
    </div>
    <div class="new-task__icon new-task__icon--<?=$task->category->icon?>"></div>
    <p class="new-task_description">
      <?=$task->description?>
    </p>
    <b class="new-task__price new-task__price--<?=$task->category->icon?>"><?=$task->price?><b> ₽</b></b>
    <p class="new-task__place"><?=$task->address->name?></p>
    <span class="new-task__time"><?= Yii::$app->formatter->asDate($task->created_at, 'php:d.m.Y H:i');?></span>
</div>