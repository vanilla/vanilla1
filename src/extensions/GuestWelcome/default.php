<?php
/*
Extension Name: Guest Welcome Message
Extension Url: http://lussumo.com/docs/
Description: Adds a welcome message to the panel if the person viewing the forum doesn't have an active session.
Version: 3.0
Author: Mark O'Sullivan
Author Url: http://markosullivan.ca/
*/

$Context->Dictionary["GuestWelcome"] = "<strong>Welcome Guest!</strong>
   <br />Want to take part in these discussions? If you have an account, <a href=\"".GetUrl($Configuration, "people.php")."\">sign in now</a>.
   <br />If you don't have an account, <a href=\"".GetUrl($Configuration, "people.php", "", "", "", "", "PostBackAction=ApplyForm")."\">apply for one now</a>.";

if (in_array($Context->SelfUrl, array("account.php", "categories.php", "comments.php", "index.php", "search.php")) && $Context->Session->UserID == 0) {
   $NoticeCollector->AddNotice($Context->GetDefinition('GuestWelcome'));
}
?>