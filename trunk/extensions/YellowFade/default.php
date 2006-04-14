<?php
/*
Extension Name: YellowFade Effect
Extension Url: http://michaelraichelson.com/hacks/vanilla/
Description: Adds the YellowFade effect on some stuff.
Version: 0.1
Author: Michael Raichelson
Author Url: http://michaelraichelson.com/
*/

if (in_array($Context->SelfUrl, array("index.php", "categories.php", "comments.php", "search.php", "post.php", "account.php", "settings.php"))){
    $Head->AddScript($Configuration['WEB_ROOT'].'extensions/YellowFade/functions.js');
}
?>
