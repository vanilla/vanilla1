<?php
// Note: This file is included from the library/Vanilla.Control.IdentityForm.php class.

echo("<div class=\"AccountForm\">
   <h1>".$this->Context->GetDefinition("ChangePersonalInfo")."</h1>
   <div class=\"Form AccountPersonal\">");
   
      $this->CallDelegate("PreWarningsRender");
      
      echo($this->Get_Warnings()."
      ".$this->Get_PostBackForm("frmAccountPersonal"));
      
      $this->CallDelegate("PreInputsRender");
      
      echo("<h2>".$this->Context->GetDefinition("DefineYourAccountProfile")."</h2>");
      if ($this->Context->Configuration["ALLOW_NAME_CHANGE"] == "1") {
         echo("<dl>
            <dt>".$this->Context->GetDefinition("YourUsername")."</dt>
            <dd><input type=\"text\" name=\"Name\" value=\"".$this->User->Name."\" maxlength=\"20\" class=\"SmallInput\" id=\"txtUsername\" /> ".$Required."</dd>
         </dl>
         <div class=\"InputNote\">".$this->Context->GetDefinition("YourUsernameNotes")."</div>");
      }
      if ($this->Context->Configuration["USE_REAL_NAMES"] == "1") {
         echo("<dl>
            <dt>".$this->Context->GetDefinition("YourFirstName")."</dt>
            <dd><input type=\"text\" name=\"FirstName\" value=\"".$this->User->FirstName."\" maxlength=\"50\" class=\"SmallInput\" id=\"txtFirstName\" /> ".$Required."</dd>
         </dl>
         <div class=\"InputNote\">".$this->Context->GetDefinition("YourFirstNameNotes")."</div>
         <dl>
            <dt>".$this->Context->GetDefinition("YourLastName")."</dt>
            <dd><input type=\"text\" name=\"LastName\" value=\"".$this->User->LastName."\" maxlength=\"50\" class=\"SmallInput\" id=\"txtLastName\" /> ".$Required."</dd>
         </dl>
         <div class=\"InputNote\">
            ".$this->Context->GetDefinition("YourLastNameNotes")."
            <div class=\"CheckBox\">".GetDynamicCheckBox("ShowName", 1, $this->User->ShowName, "", $this->Context->GetDefinition("MakeRealNameVisible"))."</div>
         </div>");
      }
      if ($this->Context->Configuration["ALLOW_EMAIL_CHANGE"] == "1") {
         echo("<dl>
            <dt>".$this->Context->GetDefinition("YourEmailAddress")."</dt>
            <dd><input type=\"text\" name=\"Email\" value=\"".$this->User->Email."\" maxlength=\"200\" class=\"SmallInput\" id=\"txtEmail\" /> ".$Required."</dd>
         </dl>
         <div class=\"InputNote\">
            ".$this->Context->GetDefinition("YourEmailAddressNotes")."
            <div class=\"CheckBox\">".GetDynamicCheckBox("UtilizeEmail", 1, $this->User->UtilizeEmail, "", $this->Context->GetDefinition("CheckForVisibleEmail"))."</div>
         </div>");
      } else {
         echo("<div class=\"InputNote\">
            <div class=\"CheckBox\">".GetDynamicCheckBox("UtilizeEmail", 1, $this->User->UtilizeEmail, "", $this->Context->GetDefinition("CheckForVisibleEmail"))."</div>
         </div>");
      }
      echo("<dl>
         <dt>".$this->Context->GetDefinition("AccountPicture")."</dt>
         <dd><input type=\"text\" name=\"Picture\" value=\"".$this->User->Picture."\" maxlength=\"255\" class=\"SmallInput\" id=\"txtPicture\" /></dd>
      </dl>
      <div class=\"InputNote\">
         ".$this->Context->GetDefinition("AccountPictureNotes")."
      </div>
      <dl>
         <dt>".$this->Context->GetDefinition("Icon")."</dt>
         <dd><input type=\"text\" name=\"Icon\" value=\"".$this->User->Icon."\" maxlength=\"255\" class=\"SmallInput\" id=\"txtIcon\" /></dd>
      </dl>
      <div class=\"InputNote\">
         ".$this->Context->GetDefinition("IconNotes")."
      </div>");
      
      $this->CallDelegate("PreCustomInputsRender");      
      
      echo("<h2>".$this->Context->GetDefinition("AddCustomInformation")."</h2>
      <div class=\"InputNote\">".$this->Context->GetDefinition("AddCustomInformationNotes")."</div>
      <dl class=\"InputCustom\" id=\"LabelValuePairContainer\">");
         $CurrentItem = 1;
         if (count($this->User->Attributes) > 0) {
            $AttributeCount = count($this->User->Attributes);
            for ($i = 0; $i < $AttributeCount; $i++) {
               if ($i == 0) {
                  echo("<dt class=\"DefinitionHeading\">".$this->Context->GetDefinition("Label")."</dt>
                  <dd class=\"DefinitionHeading\">".$this->Context->GetDefinition("Value")."</dd>");
               }
               echo("<dt><input type=\"text\" name=\"Label".$CurrentItem."\" value=\"".$this->User->Attributes[$i]["Label"]."\" maxlength=\"20\" class=\"LVLabelInput\" /></dt>
               <dd><input type=\"text\" name=\"Value".$CurrentItem."\" value=\"".$this->User->Attributes[$i]["Value"]."\" maxlength=\"200\" class=\"LVValueInput\" /></dd>");
               $CurrentItem++;
            }
         } else {
            echo("<dt class=\"DefinitionHeading\">".$this->Context->GetDefinition("Label")."</dt>
            <dd class=\"DefinitionHeading\">".$this->Context->GetDefinition("Value")."</dd>
            <dt class=\"DefinitionItem\"><input type=\"text\" name=\"Label".$CurrentItem."\" value=\"\" maxlength=\"20\" class=\"LVLabelInput\" /></dt>
            <dd class=\"DefinitionItem\"><input type=\"text\" name=\"Value".$CurrentItem."\" value=\"\" maxlength=\"200\" class=\"LVValueInput\" /></dd>");
         }
      echo("</dl>");
      
      $this->CallDelegate("PreButtonsRender");
      
      echo("<div class=\"FormLink\"><a href=\"javascript:AddLabelValuePair();\">".$this->Context->GetDefinition("AddLabelValuePair")."</a></div>
      <div class=\"FormButtons\">
         <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Save")."\" class=\"Button SubmitButton\" />
         <a href=\"".GetUrl($this->Context->Configuration, "account.php", "", "u", $this->User->UserID)."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
      </div>
      </form>
   </div>
</div>");