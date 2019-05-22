<?php namespace Poki; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <form action="http://localhost:9000/api/v1/default/delete/5" method="post">
        <label>Api key:</label> <input type="text" name="apikey" placeholder="Your api key"> <br>
        <!-- <input type="text" name="id" value="1"> -->
        <input type="text" name="title" value="Je suis hereux">
        <input type="text" name="content" value="La joie qui m'anime est indescriptible je vous le dis !">
        <button type="submit">Go !</button>
    </form>
</body>
</html>