<?php
// Note: This file is included from the library/Vanilla.Control.PreferencesForm.php class.

echo("<div class=\"AccountForm FunctionalityForm\">
   <h1>".$this->Context->GetDefinition("ForumFunctionality")."</h1>
   <div class=\"Form\">
      <form name=\"frmFunctionality\">
      <div class=\"InputNote\">".$this->Context->GetDefinition("ForumFunctionalityNotes")."</div>");
      $FirstSection = "";
      while (list($SectionLanguageCode, $SectionPreferences) = each($this->Preferences)) {
         echo("<h2>".$this->Context->GetDefinition($SectionLanguageCode)."</h2>
            <div class=\"InputBlock\">");
            $SectionPreferencesCount = count($SectionPreferences);
            for ($i = 0; $i < $SectionPreferencesCount; $i++) {
               $Preference = $SectionPreferences[$i];
               $PreferenceDefault = ForceBool(@$this->Context->Configuration["PREFERENCE_".$Preference["Name"]], 0);
               echo("<div class=\"CheckBox\">"
                  .GetDynamicCheckBox($Preference["Name"], $PreferenceDefault, $this->Context->Session->User->Preference($Preference["Name"]), "PanelSwitch('".$Preference["Name"]."', ".ForceBool($Preference["RefreshPageAfterSetting"], 0).");", $this->Context->GetDefinition($Preference["LanguageCode"]))
               ."</div>");									
            }
            
         echo("</div>");
      }
      echo("</form>
   </div>
</div>");
?>