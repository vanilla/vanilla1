<?php
// Note: This file is included from the library/Vanilla.Control.LanguageForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("LanguageManagement")."</h1>
   <div class=\"Form LanguageChange\">
      <div class=\"InputNote\">".$this->Context->GetDefinition("LanguageChangesSaved")."</div>
      <div class=\"FormLink\"><a href=\"./".$this->Context->SelfUrl."?PostBackAction=LanguageChange\">".$this->Context->GetDefinition("ClickHereToContinue")."</a></div>
   </div>
</div>");
?>