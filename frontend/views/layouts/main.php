<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="<?= (Yii::$app->user->isGuest) ? 'landing' : '' ?>">
<?php $this->beginBody() ?>
<div class="table-layout">
    <?= $this->render('header'); ?>
    <main class="<?= (Yii::$app->user->isGuest) ? '' : 'page-main' ?>">
        <?= $content ?>
    </main>
    <footer class="page-footer">
        <div class="main-container page-footer__container">
            <div class="page-footer__info">
                <p class="page-footer__info-copyright">
                    &copy; <?= date('Y') ?>, ООО «ТаскФорс»
                    Все права защищены
                </p>
                <p class="page-footer__info-use">
                    «TaskForce» — это сервис для поиска исполнителей на разовые задачи.
                    mail@taskforce.com
                </p>
            </div>
            <div class="page-footer__links">
                <ul class="links__list">
                    <li class="links__item">
                        <a href="<?= Url::to(['/tasks']);?>">Задания</a>
                    </li>
                    <li class="links__item">
                        <a href="<?= Url::to(['/account']);?>">Мой профиль</a>
                    </li>
                    <li class="links__item">
                        <a href="<?= Url::to(['/users']);?>">Исполнители</a>
                    </li>
                    <li class="links__item">
                        <a href="<?= Url::to(['/signup']);?>">Регистрация</a>
                    </li>
                    <li class="links__item">
                        <a href="<?= Url::to(['/tasks/create']);?>">Создать задание</a>
                    </li>
                    <li class="links__item">
                        <a href="">Справка</a>
                    </li>
                </ul>
            </div>
            <div class="page-footer__copyright">
                <a>
                    <img class="copyright-logo"
                         src="/img/academy-logo.png"
                         width="185" height="63"
                         alt="Логотип HTML Academy">
                </a>
            </div>
            <?php if (Yii::$app->request->url == '/signup') { ?>
              <div class="clipart-woman">
                <img src="./img/clipart-woman.png" width="238" height="450">
              </div>
              <div class="clipart-message">
                <div class="clipart-message-text">
                  <h2>Знаете ли вы, что?</h2>
                  <p>После регистрации вам будет доступно более
                    двух тысяч заданий из двадцати разных категорий.</p>
                  <p>В среднем, наши исполнители зарабатывают
                    от 500 рублей в час.</p>
                </div>
              </div>
            <?php } ?>
        </div>
    </footer>
</div>
<div class="overlay"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
