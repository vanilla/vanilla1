<?php
// Note: This file is included from the library/Vanilla.Control.RegistrationForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("RegistrationManagement")."</h1>
   <div class=\"Form RegistrationChange\">
      <div class=\"InputNote\">".$this->Context->GetDefinition("RoleChangesSaved")."</div>
      <div class=\"FormLink\"><a href=\"./settings.php?PostBackAction=RegistrationChange\">".$this->Context->GetDefinition("ClickHereToContinue")."</a></div>
   </div>
</div>");
?>