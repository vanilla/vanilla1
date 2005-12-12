<?php
// Note: This file is included from the library/People.Control.SignInForm.php control.

$this->Render_Warnings();
echo("<div class=\"Form SignInForm\">");
$this->Render_PostBackForm($this->FormName);
echo("<dl class=\"InputBlock SignInInputs\">
      <dt>".$this->Context->GetDefinition("Username")."</dt>
      <dd><input type=\"text\" name=\"Username\" value=\"".$this->Username."\" class=\"Input\" maxlength=\"20\" /></dd>
      <dt>".$this->Context->GetDefinition("Password")."</dt>
      <dd><input type=\"password\" name=\"Password\" value=\"\" class=\"Input\" /></dd>
   </dl>
   <div class=\"InputBlock RememberMe\">".GetDynamicCheckBox("RememberMe", 1, ForceIncomingBool("RememberMe", 0), "", $this->Context->GetDefinition("RememberMe"))."</div>
   <div class=\"FormButtons\"><input type=\"submit\" name=\"btnSignIn\" value=\"".$this->Context->GetDefinition("Proceed")."\" class=\"Button\" /></div>
   <a class=\"ForgotPasswordLink\" href=\"".$this->Context->SelfUrl."?PostBackAction=PasswordRequestForm\">".$this->Context->GetDefinition("ForgotYourPassword")."</a>
   <a class=\"ApplyForMembershipLink\" href=\"".$this->Context->SelfUrl."?PostBackAction=ApplyForm\">".$this->Context->GetDefinition("ApplyForMembership")."</a>
   </form>
</div>");
?>