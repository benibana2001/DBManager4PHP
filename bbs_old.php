<?php

// DB接続
$dsn = 'mysql:dbname=bbs; host=127.0.0.1; charset=utf8';
$usr = 'root';
$pwd = '';
$opts = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
); 
$error = '';

// 日本語が文字化けする際はphp.iniのmbstring設定と、my.iniの文字コード設定を見直す
// DBにも文字コードが記憶されているので、上記修正後は一度DROPしてCREATEし直す
// show variables; で定義されているcharecter_set_database辺りを確認

try {
    // 接続オブジェクト
    $db = new PDO($dsn, $usr, $pwd, $opts);
} catch (PDOException $exc) {
    echo $exc->getMessage();
}

// PDOStatement
$stt = $db->prepare('INSERT INTO post(id, name, comment, created_at) VALUES(:id, :name, :comment, :created_at)');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    // Validate
    $name = filter_input(INPUT_POST, 'name');
    $comment = filter_input(INPUT_POST, 'comment');

    // $name, $commentに正しく値が入ったかをkeyとしてValidateする
    if(!$name || !$comment || strlen($name) > 40){
        $error = '入力に誤りがあります。';
    }else{
        // Bind
        $stt->bindValue(':id', null);
        $stt->bindValue(':name', $name);
        $stt->bindValue(':comment', $comment);
        $stt->bindValue(':created_at', date('Y-m-d H:i:s'));

        //Execute
        $stt->execute();

        // REDIRECT時のデータ再送防止のため故意にRELOAD
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        header('Location:http://'. $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);
    }
}
?>
        
<?php
    // 投稿結果を表示
    try {
        // 接続オブジェクト
        $db2 = new PDO($dsn, $usr, $pwd, $opts);
    } catch (PDOException $exc) {
        echo $exc->getMessage();
    }
    // PDOStatement
    $stt2 = $db2->prepare('SELECT * FROM post ORDER BY created_at DESC');
    $stt2->execute();

    // 配列としてFetch
    $array = $stt2->fetchAll(PDO::FETCH_ASSOC);
    $array_length = count($array);
    
    include './views/bbs_view.php';
?>
        