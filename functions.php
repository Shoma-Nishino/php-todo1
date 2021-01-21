<?php

require_once(__DIR__ . '/config.php');

function getPdoInstance()
{
    try {
    $pdo = new PDO(
            DSN,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
      return $pdo;
    } catch(PDOException $e) {
        echo 'DB接続エラー: ' . $e->getMessage();
        exit;
    }
}


/**
* todo全件取得
* @param obj
* @return obj todo全件
*/
function getTodos($pdo)
{
    $stmt = $pdo->query("SELECT * FROM todos ORDER BY id DESC");
    $todos = $stmt->fetchAll();
    return $todos;
}

/**
* エスケープ処理
* @param string
* @return string
*/
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
* タスク追加
* @param obj
* @return boolean|null
*/
function addTodo($pdo)
{
    $title = trim(filter_input(INPUT_POST, 'title'));

    // 空文字はtodoに追加しない
    if ($title === '') {
        return false;
    }

    $stmt = $pdo->prepare('INSERT INTO todos (title) VALUES (:title)');
    $stmt->bindValue('title', $title, PDO::PARAM_STR);
    $stmt->execute();
}

/**
* タスクのis_doneの値変更
* @param obj
* @return null
*/
function toggleTodo($pdo)
{
    $id = filter_input(INPUT_POST, 'id');

    if (empty($id)) {
        return;
    }

    $stmt = $pdo->prepare('UPDATE todos SET is_done = NOT is_done WHERE id = :id');
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

/**
* タスクの削除
* @param obj
* @return null
*/
function deleteTodo($pdo)
{
    $id = filter_input(INPUT_POST, 'id');

    if (empty($id)) {
        return;
    }

    $stmt = $pdo->prepare('DELETE FROM todos WHERE id = :id');
    $stmt->bindValue('id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

/**
* トークンを生成
*/
function createToken()
{
 // トークンがなければトークンを生成してセッションに追加
  if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
  }
}

/**
* トークンを確認
*/
function validateToken()
{
  // 「トークンがない」もしくは「トークンが間違っている」場合は処理を抜ける
  if (empty($_SESSION['token']) || $_SESSION['token'] !== filter_input(INPUT_POST, 'token'))
  {
    exit('問題が発生しました');
  }
}
