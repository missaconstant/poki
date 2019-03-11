<?php
    include 'poki.accessor.php';

    $articles = Poki::search('clicks', '10', false, false);
    var_dump($articles); exit();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    
    <form action="test2.php" method="post">
        <label for="">Name: </label>
        <input type="text" name="name"> <br><br>
        <label for="">E-mail:</label>
        <input type="text" name="email"> <br><br>
        <label for="">Phone:</label>
        <input type="text" name="phone"> <br><br>
        <input type="hidden" name="category" value="articles">
        <?= Poki::getCSRF() ?>
        <button type="submit">Envoyer</button>
    </form>

</body>
</html>