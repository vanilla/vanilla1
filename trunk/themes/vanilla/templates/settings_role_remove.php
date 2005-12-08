<?php
// Note: This file is included from the library/Vanilla.Control.RoleForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("RoleManagement")."</h1>
   <div class=\"Form\" id=\"RoleRemove\">
      ".$this->Get_Warnings()."
      ".$this->Get_PostBackForm("frmRoleRemove")."
      <h2>".$this->Context->GetDefinition("SelectRoleToRemove")."</h2>
      <dl>
         <dt>".$this->Context->GetDefinition("Roles")."</dt>
         <dd>".$this->RoleSelect->Get()." ".$this->Context->GetDefinition("Required")."</dd>
      </dl>");
      if ($RoleID > 0) {
         $this->RoleSelect->Attributes = "";
         $this->RoleSelect->RemoveOption($this->RoleSelect->SelectedID);
         $this->RoleSelect->Name = "ReplacementRoleID";
         $this->RoleSelect->SelectedID = ForceIncomingInt("ReplacementRoleID", 0);
         echo("<h2>".$this->Context->GetDefinition("SelectReplacementRole")."</h2>
         <dl>
            <dt>".$this->Context->GetDefinition("ReplacementRole")."</dt>
            <dd>".$this->RoleSelect->Get()." ".$this->Context->GetDefinition("Required")."</dd>
         </dl>
         <div class=\"InputNote\">".$this->Context->GetDefinition("ReplacementRoleNotes")."</div>
         <div class=\"FormButtons\">
            <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Remove")."\" class=\"Button SubmitButton\" />
            <a href=\"./".$this->Context->SelfUrl."?PostBackAction=Roles\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
         </div>");
      }
      echo("</form>
   </div>
</div>");				
?>