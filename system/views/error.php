<!DOCTYPE HTML>
<html>
<head>
    <?php require_once 'system\views\css.php'; ?>
    <title>Oops</title>
</head>
<body><div class="container">
    <div class="headLine">Oops! <?php echo (count(self::$catch) > 1)? count(self::$catch).' errors':'An error'; ?> occurred!</div>
    <div class="mainBox">
        <div class="box leftBox">
        <?php
        foreach(self::$catch as $error){
            echo '<div class="error">';
            echo '<div class="title"><b>'.$error['title'].':</b> '.$error['message'].'</div>';
            echo '<div class="details">on file: <b>'.$error['shortFile'].'</b> in line: <b>'.$error['line'].'</b></div>';
            if($error['fatal']) echo '<div class="fatal">This is a fatal error! the script can\'t proceed!</div>';
            echo '</div>';
        }
        ?>
        </div>
        <div class="box rightBox">
            <div class="boxName title"><b>GET</b></div>
            <div class="more"><?php var_dump($_GET); ?></div>
            <div class="boxName title"><b>POST</b></div>
            <div class="more"><?php var_dump($_POST); ?></div>
            <div class="boxName title"><b>FILES</b></div>
            <div class="more"><?php var_dump($_FILES); ?></div>
            <div class="boxName title"><b>SESSION</b></div>
            <div class="more"><?php var_dump($_SESSION); ?></div>
            <div class="boxName title"><b>COOKIE</b></div>
            <div class="more"><?php var_dump($_COOKIE); ?></div>
        </div>
        <p class="clear"></p>
    </div>
    <p class="clear"></p>
</div></body>
</html>