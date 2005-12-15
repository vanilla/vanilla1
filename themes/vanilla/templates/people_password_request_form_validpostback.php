<?php
// Note: This file is included from the library/People.Control.PasswordRequestForm.php control.

echo("<div class=\"FormComplete\">
   <h1>".$this->Context->GetDefinition("RequestProcessed")."</h1>
   <ul>
      <li>".str_replace("//1",
         FormatStringForDisplay($this->EmailSentTo, 1),
         $this->Context->GetDefinition("MessageSentToXContainingPasswordInstructions"))."</li>
   </ul>
</div>");
?>