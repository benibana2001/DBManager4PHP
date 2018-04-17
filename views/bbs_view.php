<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ひとこと掲示板</title>
    </head>
    <body>
        <h1>ひとこと掲示板</h1>
        <?php if(count($error)){echo $error;} ?>
        <form action="bbs.php" method="post">
            名前：<input type="text" name="name"><br>
            ひとこと：<input type="text" name="comment" size="60"><br>
            <input type="submit" name="submit" value="送信">
        </form>
        <ul>
            <?php for($i = 0; $i < $array_length; $i++): ?>
            <li>
                <?php echo $array[$i]['name']; ?>:
                <?php echo $array[$i]['comment']; ?>:
                <?php echo $array[$i]['created_at']; ?>
            </li>
            <?php endfor; ?>
        </ul>
    </body>
</html>
