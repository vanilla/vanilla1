<?php
// Note: This file is included from the library/People.Control.PasswordRequestForm.php control.

echo("<div class=\"FormComplete\">
   <h1>".$this->Context->GetDefinition("RequestProcessed")."</h1>
   <ul>
      <li>".$this->Context->GetDefinition("MessageSentTo")." <strong>".FormatStringForDisplay($this->EmailSentTo, 1)."</strong> ".$this->Context->GetDefinition("ContainingPasswordInstructions")."</li>
   </ul>
</div>");
?>