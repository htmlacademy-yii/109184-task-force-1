<div class="main-container page-container">
  <section class="new-task">
    <div class="new-task__wrapper">
      <h1>Новые задания</h1>
      <?= $this->render('tasklist', ['tasks' => $tasks]);?>
    </div>
    <div class="new-task__pagination">
      <ul class="new-task__pagination-list">
        <li class="pagination__item"><a href="#"></a></li>
        <li class="pagination__item pagination__item--current">
          <a>1</a></li>
        <li class="pagination__item"><a href="#">2</a></li>
        <li class="pagination__item"><a href="#">3</a></li>
        <li class="pagination__item"><a href="#"></a></li>
      </ul>
    </div>
  </section>
  <section class="search-task">
    <?= $this->render('filter', ['model' => $model, 'filter' => $filter]);?>
  </section>
</div>