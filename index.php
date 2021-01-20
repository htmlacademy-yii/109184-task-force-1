<?php

require_once("classes/Task/Task.php");

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

$strategy = new Task(1, 1, 'new');
assert($strategy->GetStatus('action_cancel') == Task::STATUS_CANCELED, 'cancel action');

var_dump($strategy->AddTask());