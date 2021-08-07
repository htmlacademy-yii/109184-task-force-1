<?php foreach($tasks as $task) { ?>
    <div class="landing-task">
       <div class="landing-task-top task-<?=$task->category->icon?>"></div>
       <div class="landing-task-description">
           <h3><a href="#" class="link-regular"><?=$task->title?></a></h3>
           <p style=""><?=$task->description?></p>
       </div>
       <div class="landing-task-info">
           <div class="task-info-left">
               <p><a href="#" class="link-regular"><?=$task->category->name?></a></p>
               <p><?= ($task->created_at < 60*60*24*365) ? Yii::$app->formatter->asRelativeTime($task->created_at) : Yii::$app->formatter->asDate($task->created_at);?></p>
           </div>
           <span><?=$task->price?> <b>₽</b></span>
       </div>
    </div>
<?php } ?>