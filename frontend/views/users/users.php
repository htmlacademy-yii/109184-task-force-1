<div class="main-container page-container">
  <section class="user__search">
    <?= $this->render('userlist', ['users' => $users]);?>
  </section>
  <section class="search-task">
    <div class="search-task__wrapper">
      <form class="search-task__form" name="users" method="post" action="#">
        <fieldset class="search-task__categories">
          <legend>Категории</legend>
          <label class="checkbox__legend">
            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked disabled>
            <span>Курьерские услуги</span>
          </label>
          <label class="checkbox__legend">
            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="" checked>
            <span>Грузоперевозки</span>
          </label>
          <label class="checkbox__legend">
            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
            <span>Переводы</span>
          </label>
          <label class="checkbox__legend">
            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
            <span>Строительство и ремонт</span>
          </label>
          <label class="checkbox__legend">
            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
            <span>Выгул животных</span>
          </label>
        </fieldset>
        <fieldset class="search-task__categories">
          <legend>Дополнительно</legend>
          <label class="checkbox__legend">
            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
            <span>Сейчас свободен</span>
          </label>
          <label class="checkbox__legend">
            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
            <span>Сейчас онлайн</span>
          </label>
          <label class="checkbox__legend">
            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
            <span>Есть отзывы</span>
          </label>
          <label class="checkbox__legend">
            <input class="visually-hidden checkbox__input" type="checkbox" name="" value="">
            <span>В избранном</span>
          </label>
        </fieldset>
        <label class="search-task__name" for="110">Поиск по имени</label>
        <input class="input-middle input" id="110" type="search" name="q" placeholder="">
        <button class="button" type="submit">Искать</button>
      </form>
    </div>
  </section>
</div>