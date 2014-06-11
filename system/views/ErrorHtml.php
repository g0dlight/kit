<?php
if(!isset($errors)) $errors = array();
if(!isset($css)) $css = false;
?>
<!DOCTYPE HTML>
<html>
<head>
    <?php echo $css; ?>
    <title>Oops</title>
</head>
<body><div class="container">
    <div class="headLine">Oops! <?php echo (count($errors) > 1)? count($errors).' errors':'An error'; ?> occurred!</div>
    <div class="mainBox">
        <div class="box leftBox">
            <?php
            foreach($errors as $error){
                echo '<div class="error">';
                echo '<div class="title"><b>'.$error['title'].':</b> '.$error['message'].'</div>';
                echo '<div class="details">on file: <b>'.$error['file'].'</b> in line: <b>'.$error['line'].'</b></div>';
                if($error['fatal']) echo '<div class="fatal">This is a fatal error! the script can\'t proceed!</div>';
                echo '</div>';
            }
            ?>
        </div>
        <div class="box rightBox">
            <?php
            $dumpList = array(
                'GET' => &$_GET,
                'POST' => &$_POST,
                'FILES' => &$_FILES,
                'SESSION' => &$_SESSION,
                'COOKIE' => &$_COOKIE
            );
            foreach($dumpList as $dumpKey => $dumpValue){
                echo '<div class="boxName title"><b>'.$dumpKey.'</b></div>';
                echo '<div class="more">';
                foreach((array)$dumpValue as $key => $value){
                    echo '<div class="item"><b>'.$key.'</b>: '.$value.'</div>';
                }
                if(!count((array)$dumpValue)) echo '<li>Empty</li>';
                else echo '<p class="clear"></p>';
                echo '</div>';
            }
            ?>
        </div>
        <p class="clear"></p>
    </div>
    <p class="clear"></p>
</div></body>
</html>