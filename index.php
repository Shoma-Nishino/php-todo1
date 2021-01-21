<?php

require_once(__DIR__ . '/functions.php');

createToken();

$pdo = getPdoInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    validateToken();
    $action = filter_input(INPUT_GET, 'action');

switch ($action) {
    case 'add':
      addTodo($pdo);
      break;
    case 'toggle':
      toggleTodo($pdo);
      break;
    case 'delete':
        deleteTodo($pdo);
        break;
    default:
        exit;
}
    // リロードしたときに前に追加したタスクが再度追加されないための対策
    header('Location: ' . SITE_URL);
    exit;
}

$todos = getTodos($pdo);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<body>
  <h1>Todos</h1>

  <form action="?action=add" method="post" class="add-form">
    <input type="text" name="title" placeholder="新しいタスクを追加" class="task-input">
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    <button>追加</button>
  </form>

  <ul>
    <?php foreach ($todos as $todo): ?>
        <li>
            <form action="?action=toggle" method="post">
                <input type="checkbox" id="<?= 'task-checkbox' . $todo->id ?>" class="checkbox" <?= $todo->is_done? 'checked': ''; ?>>
                <input type="hidden" name="id" value="<?= h($todo->id) ?>">
                <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
            </form>
            <span class="<?= $todo->is_done ? 'done' : ''; ?>">
                <label for="<?= 'task-checkbox' . $todo->id ?>">
                    <?= h($todo->title); ?>
                </label>
            </span>

            <?php if ($todo->is_done) :?>
                <form action="?action=delete" method="post"  class="delete-form">
                    <input type="hidden" name="id" value="<?= h($todo->id) ?>">
                    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
                    <button class="delete">削除する</button>
                </form>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
  </ul>
</body>
<script src="./js/main.js"></script>
</html>
