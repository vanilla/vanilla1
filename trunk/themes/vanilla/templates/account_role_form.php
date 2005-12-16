<?php
// Note: This file is included from the library/Vanilla.Control.AccountRoleForm.php class.

if (!$this->Context->Session->User->Permission("PERMISSION_CHANGE_USER_ROLE")) {
   $this->Context->WarningCollector->Add($this->Context->GetDefinition("PermissionError"));
   echo("<div class=\"AccountForm\">
      ".$this->Get_Warnings()."
   </div>");				
} else {				
   $this->PostBackParams->Set("PostBackAction", "ProcessRole");
   $this->PostBackParams->Set("u", $this->User->UserID);
   $Required = $this->Context->GetDefinition("Required");
   echo("<div class=\"AccountForm\">
      <h1>".$this->Context->GetDefinition("ChangeRole")."</h1>
      <div class=\"Form AccountRole\">
         ".$this->Get_Warnings()."
         ".$this->Get_PostBackForm("frmRole")."
         <dl>
            <dt>".$this->Context->GetDefinition("AssignToRole")."</dt>
            <dd>".$this->RoleSelect->Get()." ".$Required."</dd>
         </dl>
         <div class=\"InputNote\">".$this->Context->GetDefinition("AssignToRoleNotes")."</div>
         <dl>
            <dt>".$this->Context->GetDefinition("RoleChangeInfo")."</dt>
            <dd><input type=\"text\" name=\"Notes\" value=\"\" class=\"PanelInput\" /> ".$Required."</dd>
         </dl>
         <div class=\"InputNote\">".$this->Context->GetDefinition("RoleChangeInfoNotes")."</div>
         <div class=\"FormButtons\">
            <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("ChangeRole")."\" class=\"Button SubmitButton\" />
            <a href=\"".GetUrl($this->Context->Configuration, "account.php", "", "u", $this->User->UserID)."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
         </div>
         </form>
      </div>
   </div>");
}
?>