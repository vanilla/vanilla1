<?php
// Note: This file is included from the library/Vanilla.Control.RegistrationForm.php control.

if (!$this->Context->Session->User->Permission("PERMISSION_MANAGE_REGISTRATION")) {
   $this->Context->WarningCollector->Add($this->Context->GetDefinition("PermissionError"));
   echo("<div class=\"SettingsForm\">
         ".$this->Get_Warnings()."
   </div>");				
} else {				
   $this->PostBackParams->Set("PostBackAction", "ProcessRegistrationChange");
   echo("<div class=\"SettingsForm\">
      <h1>".$this->Context->GetDefinition("RegistrationManagement")."</h1>
      <div class=\"Form RegistrationForm\">
         ".$this->Get_Warnings()."
         ".$this->Get_PostBackForm("frmRegistrationChange")."
         <dl>
            <dt>".$this->Context->GetDefinition("NewMemberRole")."</dt>
            <dd>".$this->RoleSelect->Get()."</dd>
         </dl>
         <div class=\"InputNote\">".$this->Context->GetDefinition("NewMemberRoleNotes")."</div>
         <dl>
            <dt>".$this->Context->GetDefinition("ApprovedMemberRole")."</dt>
            <dd>".$this->ApprovedRoleSelect->Get()."</dd>
         </dl>
         <div class=\"InputNote\">".$this->Context->GetDefinition("ApprovedMemberRoleNotes")."</div>
         <div class=\"FormButtons\">
            <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Save")."\" class=\"Button SubmitButton\" />
            <a href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl)."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
         </div>
         </form>
      </div>
   </div>");
}
?>