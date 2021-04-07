<div class="main-container page-container">
  <section class="content-view">
    <div class="user__card-wrapper">
      <div class="user__card">
        <img src="<?= $user->avatar?>" width="120" height="120" alt="Аватар пользователя">
        <div class="content-view__headline">
          <h1><?= $user->name?></h1>
          <p>Россия, <?= $user->city->name?>, 30 лет</p>
          <div class="profile-mini__name five-stars__rate">
            <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
            <b><?= $user->rating?></b>
          </div>
          <b class="done-task">Выполнил 5 заказов</b><b class="done-review">Получил 6 отзывов</b>
        </div>
        <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
          <span>Был на сайте 25 минут назад</span>
          <a href="#"><b></b></a>
        </div>
      </div>
      <div class="content-view__description">
        <p><?= $user->about?></p>
      </div>
      <div class="user__card-general-information">
        <div class="user__card-info">
          <h3 class="content-view__h3">Специализации</h3>
          <div class="link-specialization">
            <?php foreach ($user->category as $key => $category): ?>
              <a href="browse.html" class="link-regular"><?=$category->name?></a>
            <?php endforeach; ?>
          </div>
          <h3 class="content-view__h3">Контакты</h3>
          <div class="user__card-link">
            <a class="user__card-link--tel link-regular" href="#"><?= $user->phone?></a>
            <a class="user__card-link--email link-regular" href="#"><?= $user->email?></a>
            <a class="user__card-link--skype link-regular" href="#"><?= $user->skype?></a>
          </div>
        </div>
        <div class="user__card-photo">
          <h3 class="content-view__h3">Фото работ</h3>
          <a href="#"><img src="/img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>
          <a href="#"><img src="/img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>
          <a href="#"><img src="/img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>
        </div>
      </div>
    </div>
    <div class="content-view__feedback">
      <h2>Отзывы<span>(<?= count($reviews)?>)</span></h2>
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