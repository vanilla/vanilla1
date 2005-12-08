<?php
// Note: This file is included from the library/Vanilla.Control.GlobalsForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("GlobalApplicationSettings")."</h1>
   <div class=\"Form LanguageChange\">
      <div class=\"InputNote\">".$this->Context->GetDefinition("GlobalApplicationChangesSaved")."</div>
      <div class=\"FormLink\"><a href=\"./".$this->Context->SelfUrl."?PostBackAction=Globals\">".$this->Context->GetDefinition("ClickHereToContinue")."</a></div>
   </div>
</div>");
?>