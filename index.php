<?php

require __DIR__ . '/vendor/autoload.php';

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

// Создание обработчика
function my_assert_handler($file, $line, $code)
{
    echo "<hr>Неудачная проверка утверждения:
        Файл '$file'<br />
        Строка '$line'<br />
        Код '$code'<br /><hr />";
}

// Подключение callback-функции
assert_options(ASSERT_CALLBACK, 'my_assert_handler');

$strategy = new TaskForce\Task(1, 1);
assert($strategy->GetStatus('cancel') == TaskForce\Task::STATUS_CANCELED, 'cancel action');

$strategy->getAvailableActionsByStatus('inwork', 'client1');
$strategy->getStatus('publish1');