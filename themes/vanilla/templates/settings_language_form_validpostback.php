<?php
// Note: This file is included from the library/Vanilla.Control.LanguageForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("LanguageManagement")."</h1>
   <div class=\"Form LanguageForm\">
      <div class=\"InputNote\">".$this->Context->GetDefinition("LanguageChangesSaved")."</div>
      <div class=\"FormLink\"><a href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=LanguageChange")."\">".$this->Context->GetDefinition("ClickHereToContinue")."</a></div>
   </div>
</div>");
?>