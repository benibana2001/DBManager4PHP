<?php

require_once('./DataBase.php');

// DB接続
$DataBase1 = new DataBase(
        'mysql:dbname=bbs; host=127.0.0.1; charset=utf8',
        'root',
        ''
);

$DataBase1->connect();
$error = '';

// 日本語が文字化けする際はphp.iniのmbstring設定と、my.iniの文字コード設定を見直す
// DBにも文字コードが記憶されているので、上記修正後は一度DROPしてCREATEし直す
// show variables; で定義されているcharecter_set_database辺りを確認

// PDOStatement
$DataBase1->send_query('INSERT INTO post(id, name, comment, created_at) VALUES(:id, :name, :comment, :created_at)');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    // Validate
    $name = filter_input(INPUT_POST, 'name');
    $comment = filter_input(INPUT_POST, 'comment');

    // $name, $commentに正しく値が入ったかをkeyとしてValidateする
    if(!$name || !$comment || strlen($name) > 40){
        $error = '入力に誤りがあります。';
    }else{
        // Bind
        $DataBase1->bind(':id', null);
        $DataBase1->bind(':name', $name);
        $DataBase1->bind(':comment', $comment);
        $DataBase1->bind(':created_at', date('Y-m-d H:i:s'));
        
        //Execute     
        $DataBase1->execute();

        // REDIRECT時のデータ再送防止のため故意にRELOAD
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        header('Location:http://'. $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);
    }
}
?>
        
<?php
    // 投稿結果を表示
    $DataBase1->send_query('SELECT * FROM post ORDER BY created_at DESC');
    $DataBase1->execute();

    // 配列としてFetch
    $array = $DataBase1->stt->fetchAll(PDO::FETCH_ASSOC);
    $array_length = count($array);
    
    include './views/bbs_view.php';
?>
        