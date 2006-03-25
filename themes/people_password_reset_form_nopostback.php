<?php
// Note: This file is included from the library/People.Control.PasswordResetForm.php control.

echo('<div class="About">
   <h1>'.$this->Context->GetDefinition('AboutYourPassword').'</h1>
   <p>'.$this->Context->GetDefinition('AboutYourPasswordNotes').'</p>
</div>
<div class="Form PasswordResetForm">
   <h1>'.$this->Context->GetDefinition('PasswordResetForm').'</h1>
   <p>'.$this->Context->GetDefinition('ChooseANewPassword').'</p>');
$this->Render_PostBackForm($this->FormName);
echo('<dl class="InputBlock NewPasswordInputs">
      <dt>'.$this->Context->GetDefinition('NewPassword').'</dt>
      <dd><input type="password" name="NewPassword" value="" class="Input" maxlength="20" /></dd>
      <dt>'.$this->Context->GetDefinition('ConfirmPassword').'</dt>
      <dd><input type="password" name="ConfirmPassword" value="" class="Input" maxlength="20" /></dd>
   </dl>
   <div class="FormButtons"><input type="submit" name="btnPassword" value="'.$this->Context->GetDefinition('Proceed').'" class="Button" /></div>
   </form>
</div>');
?>