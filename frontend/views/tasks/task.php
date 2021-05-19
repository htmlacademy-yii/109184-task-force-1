<?php
use yii\helpers\Url;
?>
<div class="main-container page-container">
  <section class="content-view">
    <div class="content-view__card">
      <div class="content-view__card-wrapper">
        <div class="content-view__header">
          <div class="content-view__headline">
            <h1><?=$task->title?></h1>
            <span>Размещено в категории
                                <a href="browse.html" class="link-regular"><?=$task->category->name?></a>
                                25 минут назад</span>
          </div>
          <b class="new-task__price new-task__price--clean content-view-price"><?=$task->price?><b> ₽</b></b>
          <div class="new-task__icon new-task__icon--clean content-view-icon"></div>
        </div>
        <div class="content-view__description">
          <h3 class="content-view__h3">Общее описание</h3>
          <p>
            <?=$task->description?>
          </p>
        </div>
        <div class="content-view__attach">
          <h3 class="content-view__h3">Вложения</h3>
          <?php if (!empty($task->gallery)) {
            foreach ($task->gallery as $key => $file) { ?>
              <img width="62" height="62" src="<?= $file->link ?>">
          <?php  } 
          } ?>
        </div>
        <div class="content-view__location">
          <h3 class="content-view__h3">Расположение</h3>
          <div class="content-view__location-wrapper">
            <div class="content-view__map">
              <a href="#"><img src="/img/map.jpg" width="361" height="292"
                               alt="Москва, Новый арбат, 23 к. 1"></a>
            </div>
            <div class="content-view__address">
              <span class="address__town"><?=$task->city->name?></span><br>
              <span>Новый арбат, 23 к. 1</span>
              <p>Вход под арку, код домофона 1122</p>
            </div>
          </div>
        </div>
      </div>
      <div class="content-view__action-buttons">
        <?php $actions = $task->getAvailableActionsByStatus($task->status, \Yii::$app->user->identity->role_id);?>
        <?php if (!empty($actions)) { ?>
          <?php foreach ($actions as $action) { ?>
            <?php if ($action != 'respond' && !$task->checkRespond()) { ?>
              <button class=" button button__big-color <?= $action ?>-button open-modal"
                      type="button" data-for="<?= $action ?>-form"><?= $task->getActionName($action)?>
              </button>
            <?php } ?>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
    <div class="content-view__feedback">
      <h2>Отклики <span>(<?= count($responds)?>)</span></h2>
      <div class="content-view__feedback-wrapper">
		<?php if (!empty($responds)) {
			foreach ($responds as $key => $respond) { 
        if ($respond['user_id'] == \Yii::$app->user->identity->id || \Yii::$app->user->identity->role_id == 4) {
				  echo $this->render('respond', ['respond' => $respond, 'task' => $task]);
        }
			} 
		} else {
			echo "<p>Еще никто не оставил отклик. Будьте первым!</p>";
		}?>
      </div>
    </div>
  </section>
  <section class="connect-desk">
    <div class="connect-desk__profile-mini">
      <div class="profile-mini__wrapper">
        <h3>Заказчик</h3>
        <div class="profile-mini__top">
          <img src="<?= $task->user->avatar ?>" width="62" height="62" alt="Аватар заказчика">
          <div class="profile-mini__name five-stars__rate">
            <p><?= $task->user->login ?></p>
          </div>
        </div>
        <p class="info-customer"><span>12 заданий</span><span class="last-">2 года на сайте</span></p>
        <a href="<?= Url::to(['users/view', 'id' => $task->user_created]);?>" class="link-regular">Смотреть профиль</a>
      </div>
    </div>
    <div id="chat-container">
      <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
      <chat class="connect-desk__chat"></chat>
    </div>
  </section>
</div>
<section class="modal response-form form-modal" id="respond-form">
  <?= $this->render('modals/respond_modal', ['responseForm' => $responseForm, 'task_id' => $task->id]); ?>
</section>
<section class="modal completion-form form-modal" id="request-form">
  <?= $this->render('modals/request_modal', ['requestForm' => $requestForm, 'task_id' => $task->id]); ?>
</section>
<section class="modal form-modal refusal-form" id="refusal-form">
  <?= $this->render('modals/refuse_modal', ['task_id' => $task->id]); ?>
</section>
<div class="overlay"></div>
<script src="/js/main.js"></script>
<script src="/js/messenger.js"></script>