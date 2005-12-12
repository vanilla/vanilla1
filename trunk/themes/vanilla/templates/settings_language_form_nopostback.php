<?php
// Note: This file is included from the library/Vanilla.Control.LanguageForm.php control.

if (!$this->Context->Session->User->Permission("PERMISSION_MANAGE_LANGUAGE")) {
   $this->Context->WarningCollector->Add($this->Context->GetDefinition("PermissionError"));
   echo("<div class=\"SettingsForm\">
         ".$this->Get_Warnings()."
   </div>");				
} else {				
   $this->PostBackParams->Set("PostBackAction", "ProcessLanguageChange");
   echo("<div class=\"SettingsForm\">
      <h1>".$this->Context->GetDefinition("LanguageManagement")."</h1>
      <div class=\"Form LanguageForm\">
         ".$this->Get_Warnings()."
         ".$this->Get_PostBackForm("frmLanguageChange")."
         <dl>
            <dt>".$this->Context->GetDefinition("ChangeLanguage")."</dt>
            <dd>".$this->LanguageSelect->Get()."</dd>
         </dl>
         <div class=\"InputNote\">".$this->Context->GetDefinition("ChangeLanguageNotes")."</div>
         <div class=\"FormButtons\">
            <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Save")."\" class=\"Button SubmitButton\" />
            <a href=\"./".$this->Context->SelfUrl."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
         </div>
         </form>
      </div>
   </div>");
}
?>