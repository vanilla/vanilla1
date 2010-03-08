<?php
/*
Extension Name: Private Accounts
Extension Url: http://lussumo.com/docs/
Description: Blocks unauthenticated users from browsing user accounts on a public forum.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/
*/

if ($Context->SelfUrl == 'account.php' && $Context->Session->UserID == 0) {
   header('location:'.GetUrl($Configuration, 'index.php'));
   die();
}
?>