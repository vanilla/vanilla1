<?php
// Note: This file is included from the library/People.Control.Leave.php class.

echo("<div class=\"FormComplete\">
   <h1>".$this->Context->GetDefinition("SignOutSuccessful")."</h1>
   <ul>
      <li><a href=\"".$this->Context->SelfUrl."\">".$this->Context->GetDefinition("SignInAgain")."</a></li>
   </ul>
</div>");
?>