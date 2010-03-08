<?php
/*
Extension Name: Hide Success
Extension Url: http://lussumo.com/addons/
Description: Makes "Your changes were saved" messages disappear with a shrink effect after a moment.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/
*/


if (in_array($Context->SelfUrl, array('settings.php', 'account.php'))) {
   $Head->AddScript('extensions/HideSuccess/functions.js');
   $Head->AddString("
      <script type=\"text/javascript\">
         // Initialize hide effects
         var EffectTimer;
         var Height = -1;
         setTimeout(\"ExecuteEffect('Success', 'HideEffect', 9);\", 2000);
      </script>
      ");
}
?>