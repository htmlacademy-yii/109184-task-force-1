<div class="content-view__feedback-card user__search-wrapper">
  <div class="feedback-card__top">
    <div class="user__search-icon">
      <a href="user.html"><img src="<?=$user->avatar?>" width="65" height="65"></a>
      <span>17 заданий</span>
      <span>6 отзывов</span>
    </div>
    <div class="feedback-card__top--name user__search-card">
      <p class="link-name"><a href="user.html" class="link-regular"><?=$user->name?></a></p>
      <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
      <b><?=$user->rating?></b>
      <p class="user__search-content">
        <?=$user->about?>
      </p>
    </div>
    <span class="new-task__time">Был на сайте 25 минут назад</span>
  </div>
  <div class="link-specialization user__search-link--bottom">
    <?php foreach ($user->category as $key => $category): ?>
      <a href="browse.html" class="link-regular"><?=$category->name?></a>
    <?php endforeach; ?>
  </div>
</div>