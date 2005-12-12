<?php
// Note: This file is included from the library/People.Control.ApplyForm.php class.

echo("<div class=\"About\">
   ".$this->Context->GetDefinition("AboutMembership")."
         <p><a href=\"people.php\">".$this->Context->GetDefinition("BackToSignInForm")."</a></p>
      </div>
      <div class=\"Form\">
         <h1>".$this->Context->GetDefinition("MembershipApplicationForm")."</h1>
         <p>".$this->Context->GetDefinition("AllFieldsRequired")."</p>");
$this->Render_PostBackForm($this->FormName);
echo("<dl class=\"InputBlock ApplyInputs\">");

         $this->CallDelegate("PreInputsRender");

         echo("<dt>".$this->Context->GetDefinition("EmailAddress")."</dt>
         <dd><input type=\"text\" name=\"Email\" value=\"".$this->Applicant->Email."\" class=\"Input\" maxlength=\"160\" /></dd>
         <dt>".$this->Context->GetDefinition("Username")."</dt>
         <dd><input type=\"text\" name=\"Name\" value=\"".$this->Applicant->Name."\" class=\"Input\" maxlength=\"20\" /></dd>
         <dt>".$this->Context->GetDefinition("Password")."</dt>
         <dd><input type=\"password\" name=\"NewPassword\" value=\"".$this->Applicant->NewPassword."\" class=\"Input\" /></dd>
         <dt>".$this->Context->GetDefinition("PasswordAgain")."</dt>
         <dd><input type=\"password\" name=\"ConfirmPassword\" value=\"".$this->Applicant->ConfirmPassword."\" class=\"Input\" /></dd>");

         $this->CallDelegate("PostInputsRender");

      echo("</dl>");
      
      $this->CallDelegate("PreTermsCheckRender");
      
      echo("
      <div class=\"InputBlock TermsOfServiceCheckbox\">
         <div class=\"CheckboxLabel\">".GetBasicCheckBox("AgreeToTerms", 1, $this->Applicant->AgreeToTerms,"")." ".$this->Context->GetDefinition("IHaveReadAndAgreeTo")." <a href=\"javascript:PopTermsOfService('../');\">".$this->Context->GetDefinition("TermsOfService")."</a>.</div>
      </div>
      <div class=\"FormButtons\"><input type=\"submit\" name=\"btnApply\" value=\"".$this->Context->GetDefinition("Proceed")."\" class=\"Button\" /></div>
      </form>
   </div>");

?>