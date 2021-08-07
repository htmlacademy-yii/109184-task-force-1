<?php
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
?>
<div class="main-container page-container">
  <section class="content-view">
    <div class="content-view__card">
      <div class="content-view__card-wrapper">
        <div class="content-view__header">
          <div class="content-view__headline">
            <h1><?=$task->title?></h1>
            <span>Размещено в категории
              <a href="<?= Url::to(['/tasks', 'category' => $task->category->id]);?>" class="link-regular"><?=$task->category->name?></a>
              <?= ($task->created_at < 60*60*24*365) ? Yii::$app->formatter->asRelativeTime($task->created_at) : Yii::$app->formatter->asDate($task->created_at);?></span>
          </div>
          <b class="new-task__price new-task__price--<?=$task->category->icon?> content-view-price"><?=$task->price?><b> ₽</b></b>
          <div class="new-task__icon new-task__icon--<?=$task->category->icon?> content-view-icon"></div>
        </div>
        <div class="content-view__description">
          <h3 class="content-view__h3">Общее описание</h3>
          <p>
            <?=$task->description?>
          </p>
        </div>
        <?php if (!empty($task->gallery)) { ?>
        <div class="content-view__attach">
          <h3 class="content-view__h3">Вложения</h3>          
          <?php  foreach ($task->gallery as $key => $image) { ?>
            <img data-fancybox width="62" height="62" src="<?= $image->link; ?>">
          <?php }  ?>
        </div>
        <?php } ?>
        <div class="content-view__location">
          <h3 class="content-view__h3">Расположение</h3>
          <div class="content-view__location-wrapper">
            <div class="content-view__map">
              <div id="map" style="width: 361px; height: 292px"></div>
            </div>
            <div class="content-view__address">
              <span class="address__town"><?=$task->city->name?></span><br>
              <span><?=$task->address->name?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="content-view__action-buttons">
        <?php $actions = $task->getAvailableActionsByStatus($task->status, \Yii::$app->user->identity->role_id);?>
        <?php if (!empty($actions)) { ?>
          <?php foreach ($actions as $action) { ?>
            <?php if (!$task->checkRespond()) { ?>
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
          <img src="<?= ($task->user->avatar || $task->user->avatar != "") ? $task->user->avatar : '/img/no-photo.png'?>" width="62" height="62" alt="Аватар заказчика">
          <div class="profile-mini__name five-stars__rate">
            <p><?= $task->user->login ?></p>
          </div>
        </div>
        <p class="info-customer"><span><?= count($task->user->task)?> заданий</span><span class="last-"><?= $task->duration ?> на сайте</span></p>
        <a href="<?= Url::to(['users/view', 'id' => $task->user_created]);?>" class="link-regular">Смотреть профиль</a>
      </div>
    </div>
    <div id="chat-container">
      <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
      <chat class="connect-desk__chat" task="<?= $task->id?>" ufrom="<?= \Yii::$app->user->identity->id?>" uto="<?= $task->user_created?>"></chat>
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
<script src="/js/moment.js"></script>
<script src="/js/messenger.js"></script>

<?php
$this->registerJs(
    "ymaps.ready(init);
      function init(){
          // Создание карты.
          var myMap = new ymaps.Map('map', {
              center: [".$task->address->lat.", ".$task->address->long."],
              zoom: 17
          });
          var myPlacemark = new ymaps.GeoObject({
              geometry: {
                  type: \"Point\",
                  coordinates: [".$task->address->lat.", ".$task->address->long."]
              }
          });
      }
  ",
    View::POS_READY,
    'my-button-handler'
);
?>

