<?php
// Note: This file is included from the library/Vanilla.Control.CategoryForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("CategoryManagement")."</h1>
   <div class=\"Form\" id=\"CategoryRemove\">
      ".$this->Get_Warnings()."
      ".$this->Get_PostBackForm("frmCategoryRemove")."
      <h2>".$this->Context->GetDefinition("SelectCategoryToRemove")."</h2>
      <dl>
         <dt>".$this->Context->GetDefinition("Categories")."</dt>
         <dd>".$this->CategorySelect->Get()." ".$this->Context->GetDefinition("Required")."</dd>
      </dl>");
      if ($CategoryID > 0) {
         $this->CategorySelect->Attributes = "";
         $this->CategorySelect->RemoveOption($this->CategorySelect->SelectedID);
         $this->CategorySelect->Name = "ReplacementCategoryID";
         $this->CategorySelect->SelectedID = ForceIncomingInt("ReplacementCategoryID", 0);
         echo("<h2>".$this->Context->GetDefinition("SelectReplacementCategory")."</h2>
         <dl>
            <dt>".$this->Context->GetDefinition("ReplacementCategory")."</dt>
            <dd>".$this->CategorySelect->Get()." ".$this->Context->GetDefinition("Required")."</dd>
         </dl>
         <div class=\"InputNote\">".$this->Context->GetDefinition("ReplacementCategoryNotes")."</div>
         <div class=\"FormButtons\">
            <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Remove")."\" class=\"Button SubmitButton\" />
            <a href=\"./".$this->Context->SelfUrl."?PostBackAction=Categories\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
         </div>");
      }
      echo("</form>
   </div>
</div>");				
?>