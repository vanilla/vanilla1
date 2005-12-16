<?php
// Note: This file is included from the library/Vanilla.Control.RoleForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("RoleManagement")."</h1>");
   if ($RoleID > 0) {
      $this->RoleSelect->Attributes = "onchange=\"document.location='?PostBackAction=Role&RoleID='+this.options[this.selectedIndex].value;\"";
      $this->RoleSelect->SelectedID = $RoleID;
      echo("<div class=\"Form\" id=\"Roles\">
         ".$this->Get_Warnings()."
         ".$this->Get_PostBackForm("frmRole")."
         <h2>".$this->Context->GetDefinition("SelectRoleToEdit")."</h2>
         <dl>
            <dt>".$this->Context->GetDefinition("Roles")."</dt>
            <dd>".$this->RoleSelect->Get()." ".$this->Context->GetDefinition("Required")."</dd>
         </dl>
         <h2>".$this->Context->GetDefinition("ModifyRoleDefinition")."</h2>");
   } else {
      echo("<div class=\"Form\" id=\"Roles\">
         ".$this->Get_Warnings()."
         ".$this->Get_PostBackForm("frmRole")."
         <h2>".$this->Context->GetDefinition("DefineNewRole")."</h2>");
   }
   echo("<dl>
      <dt>".$this->Context->GetDefinition("RoleName")."</dt>
      <dd><input type=\"text\" name=\"RoleName\" value=\"".$this->Role->RoleName."\" maxlength=\"80\" class=\"SmallInput\" id=\"txtRoleName\" /> ".$this->Context->GetDefinition("Required")."</dd>
   </dl>
   <div class=\"InputNote\">".$this->Context->GetDefinition("RoleNameNotes")."</div>
   <dl>
      <dt>".$this->Context->GetDefinition("RoleIcon")."</dt>
      <dd><input type=\"text\" name=\"Icon\" value=\"".$this->Role->Icon."\" maxlength=\"130\" class=\"SmallInput\" id=\"txtRoleIcon\" /></dd>
   </dl>
   <div class=\"InputNote\">
      ".$this->Context->GetDefinition("RoleIconNotes")."
   </div>
   <dl>
      <dt>".$this->Context->GetDefinition("RoleTagline")."</dt>
      <dd><input type=\"text\" name=\"Description\" value=\"".$this->Role->Description."\" maxlength=\"180\" class=\"SmallInput\" id=\"txtRoleDescription\" /></dd>
   </dl>
   <div class=\"InputNote\">".$this->Context->GetDefinition("RoleTaglineNotes")."</div>
   <div class=\"InputBlock\" id=\"RoleAbilities\">
      <div class=\"InputLabel\">".$this->Context->GetDefinition("RoleAbilities")."</div>
      <div class=\"InputNote\">".$this->Context->GetDefinition("RoleAbilitiesNotes")."</div>");
      while (list($PermissionKey, $PermissionValue) = each($this->Role->Permissions)) {
         echo("<div class=\"CheckBox\">".GetDynamicCheckBox($PermissionKey, 1, $PermissionValue, "", $this->Context->GetDefinition($PermissionKey))."</div>");
      }
      
      // Add the option of specifying which categories this role can see if creating a new role
      if ($this->Role->RoleID == 0 && $this->CategoryBoxes != "") {
         echo("<div class=\"InputNote\">".$this->Context->GetDefinition("RoleCategoryNotes")."</div>"
         .$this->CategoryBoxes);
      }
   echo("</div>						
      <div class=\"FormButtons\">
         <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Save")."\" class=\"Button SubmitButton\" />
         <a href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Roles")."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
      </div>
      </form>
   </div>
</div>");			
?>