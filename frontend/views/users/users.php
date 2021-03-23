<div class="main-container page-container">
  <section class="user__search">
    <?= $this->render('userlist', ['users' => $users]);?>
  </section>
  <section class="search-task">
    <?= $this->render('filter', ['model' => $model, 'filter' => $filter]);?>
  </section>
</div>