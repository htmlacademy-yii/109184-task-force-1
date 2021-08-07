<?php 
use yii\helpers\Html;
use yii\helpers\Url;?>
<div class="main-container page-container">
  <section class="content-view">
    <div class="user__card-wrapper">
      <div class="user__card">
        <img src="<?= $user->avatar || $user->avatar != "" ? $user->avatar : '/img/no-photo.png'?>" width="120" height="120" alt="Аватар пользователя">
        <div class="content-view__headline">
          <h1><?= $user->login?></h1>
          <p>Россия, <?= $user->city->name?></p>
          <div class="profile-mini__name five-stars__rate">
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
          </div>
          <b class="done-task">Выполнил 5 заказов</b><b class="done-review">Получил <?= count($reviews)?> отзывов</b>
        </div>
        <div class="content-view__headline user__card-bookmark <?= ($user->favouriteMe) ? 'user__card-bookmark--current' : '' ?>">
          <span><?= ($user->last_online < 60*60*24*365) ? Yii::$app->formatter->asRelativeTime($user->last_online) : Yii::$app->formatter->asDate($user->last_online);?></span>
          <?= Html::a('', '/users/bookmark', [
              'onclick'=>"
                    $.ajax({
                      type     : 'GET',
                      cache    : false,
                      url  : '/users/bookmark',
                      data: {id: $user->id},
                      success  : function(response) {
                        $('.user__card-bookmark').toggleClass('user__card-bookmark--current')
                      }
                    });
                    return false;",
          ]);?>
        </div>
      </div>
      <div class="content-view__description">
        <p><?= $user->about?></p>
      </div>
      <div class="user__card-general-information">
        <div class="user__card-info">
          <?php if (!empty($user->category)) { ?>
            <h3 class="content-view__h3">Специализации</h3>
            <div class="link-specialization">
              <?php foreach ($user->category as $key => $category): ?>
                <a href="<?= Url::to(['/tasks', 'category' => $category->id]);?>" class="link-regular"><?=$category->name?></a>
              <?php endforeach; ?>
            </div>
          <?php } ?>
          <h3 class="content-view__h3">Контакты</h3>
          <div class="user__card-link">
            <a class="user__card-link--tel link-regular" href="tel:<?= $user->phone?>"><?= $user->phone?></a>
            <a class="user__card-link--email link-regular" href="mailto:<?= $user->email?>"><?= $user->email?></a>
            <a class="user__card-link--skype link-regular" href="skype:<?= $user->skype?>?chat"><?= $user->skype?></a>
          </div>
        </div>
        <div class="user__card-photo">
          <?php if (!empty($user->gallery)) { ?>
            <h3 class="content-view__h3">Фото работ</h3>
            <?php foreach ($user->gallery as $key => $gallery) { 
              echo '<img data-fancybox src="'.$gallery['link'].'" width="85" height="86" alt="Фото работы">';
            } 
          } ?>
        </div>
      </div>
    </div>
    <div class="content-view__feedback">
      <h2>Отзывы <span>(<?= count($reviews)?>)</span></h2>
      <div class="content-view__feedback-wrapper reviews-wrapper">
        <?php if (!empty($reviews)) {
          foreach ($reviews as $key => $review) { 
            echo $this->render('review', ['review' => $review]);
          } 
        } else {
          echo "<p>Еще никто не оставил отзыв. Будьте первым!</p>";
        }?>
      </div>
    </div>
  </section>
  <section class="connect-desk">
    <div class="connect-desk__chat">

    </div>
  </section>
</div>