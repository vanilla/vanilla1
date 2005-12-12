<?php
// Note: This file is included from the library/People.Control.PasswordRequestForm.php control.

$this->Render_Warnings();
echo("<div class=\"About\">
   <h1>".$this->Context->GetDefinition("AboutYourPassword")."</h1>
   <p>".$this->Context->GetDefinition("AboutYourPasswordRequestNotes")."</p>
   <p><a href=\"people.php\">".$this->Context->GetDefinition("BackToSignInForm")."</a></p>
</div>
<div class=\"Form PasswordRequestForm\">
   <h1>".$this->Context->GetDefinition("PasswordResetRequestForm")."</h1>
   <p>".$this->Context->GetDefinition("PasswordResetRequestFormNotes")."</p>");
$this->Render_PostBackForm($this->FormName);
echo("<dl class=\"InputBlock PasswordRequestInputs\">
      <dt>".$this->Context->GetDefinition("Username")."</dt>
      <dd><input type=\"text\" name=\"Username\" value=\"".FormatStringForDisplay($this->Username, 1)."\" class=\"Input\" maxlength=\"20\" /></dd>
   </dl>
   <div class=\"FormButtons\"><input type=\"submit\" name=\"btnPassword\" value=\"".$this->Context->GetDefinition("SendRequest")."\" class=\"Button\" /></div>
   </form>
</div>");
?>