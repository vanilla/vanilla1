<?php
// Note: This file is included from the library/Vanilla.Control.CategoryForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("CategoryManagement")."</h1>");
   if ($CategoryID > 0) {
      $this->CategorySelect->Attributes = "onchange=\"document.location='?PostBackAction=Category&CategoryID='+this.options[this.selectedIndex].value;\"";
      $this->CategorySelect->SelectedID = $CategoryID;
      echo("<div class=\"Form\" id=\"Categories\">
         ".$this->Get_Warnings()."
         ".$this->Get_PostBackForm("frmCategory")."
         <h2>".$this->Context->GetDefinition("GetCategoryToEdit")."</h2>
         <dl>
            <dt>".$this->Context->GetDefinition("Categories")."</dt>
            <dd>".$this->CategorySelect->Get()." ".$this->Context->GetDefinition("Required")."</dd>
         </dl>
         <h2>".$this->Context->GetDefinition("ModifyCategoryDefinition")."</h2>");
   } else {
      echo("<div class=\"Form\" id=\"Categories\">
         ".$this->Get_Warnings()."
         ".$this->Get_PostBackForm("frmCategory")."
         <h2>".$this->Context->GetDefinition("DefineNewCategory")."</h2>");
   }
   echo("<dl>
      <dt>".$this->Context->GetDefinition("CategoryName")."</dt>
      <dd><input type=\"text\" name=\"Name\" value=\"".$this->Category->Name."\" maxlength=\"80\" class=\"SmallInput\" id=\"txtCategoryName\" /> ".$this->Context->GetDefinition("Required")."</dd>
   </dl>
   <div class=\"InputNote\">".$this->Context->GetDefinition("CategoryNameNotes")."</div>
   <dl>
      <dt>".$this->Context->GetDefinition("CategoryDescription")."</dt>
      <dd><textarea name=\"Description\" class=\"LargeTextbox\">".$this->Category->Description."</textarea></dd>
   </dl>
   <div class=\"InputNote\">".$this->Context->GetDefinition("CategoryDescriptionNotes")."</div>
   <div class=\"InputBlock\" id=\"CategoryRoles\">
      <div class=\"InputLabel\">".$this->Context->GetDefinition("Roles")."</div>
      <div class=\"InputNote\">".$this->Context->GetDefinition("RolesInCategory")."</div>
      ".$this->CategoryRoles->Get()."
   </div>
   <div class=\"FormButtons\">
      <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Save")."\" class=\"Button SubmitButton\" />
      <a href=\"./".$this->Context->SelfUrl."?PostBackAction=Categories\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
   </div>
   </form>
   </div>
</div>");			
?>