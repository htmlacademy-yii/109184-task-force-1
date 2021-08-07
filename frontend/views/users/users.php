<div class="main-container page-container">
  <section class="user__search">
    <?= $this->render('userlist', ['users' => $users]);?>
    <div class="new-task__pagination">
        <?= \yii\widgets\LinkPager::widget([
         'pagination' => $pages,
         'firstPageLabel' => '',
         'lastPageLabel' => '',
         'prevPageLabel' => false,
         'nextPageLabel' => false,
         'prevPageCssClass' => 'pagination__item',
          'nextPageCssClass' => 'pagination__item',
         'pageCssClass' => 'pagination__item',
         'activePageCssClass' => 'pagination__item--current',
         'options' => [
            'class' => 'new-task__pagination-list',
          ],

        ]); ?>
    </div>
  </section>
  <section class="search-task">
    <?= $this->render('filter', ['model' => $model, 'filter' => $filter]);?>
  </section>
</div>