<?php
// Note: This file is included from the library/Vanilla.Control.PasswordForm.php class.

echo('<div class="AccountForm">
   <h1>'.$this->Context->GetDefinition('ChangeYourPassword').'</h1>
   <div class="Form AccountPassword">');
      
      $this->CallDelegate('PreWarningsRender');
      
      echo($this->Get_Warnings()
      .$this->Get_PostBackForm('frmAccountPassword'));
      
      $this->CallDelegate('PreInputsRender');
      
      echo('<dl>
         <dt>'.$this->Context->GetDefinition('YourOldPassword').'</dt>
         <dd><input type="password" name="OldPassword" value="'.$this->User->OldPassword.'" maxlength="100" class="SmallInput" id="txtOldPassword" /> '.$Required.'</dd>
      </dl>
      <div class="InputNote">'.$this->Context->GetDefinition('YourOldPasswordNotes').'</div>
      <dl>
         <dt>'.$this->Context->GetDefinition('YourNewPassword').'</dt>
         <dd><input type="password" name="NewPassword" value="'.$this->User->NewPassword.'" maxlength="100" class="SmallInput" id="txtNewPassword" /> '.$Required.'</dd>
      </dl>
      <div class="InputNote">'.$this->Context->GetDefinition('YourNewPasswordNotes').'</div>
      <dl>
         <dt>'.$this->Context->GetDefinition('YourNewPasswordAgain').'</dt>
         <dd><input type="password" name="ConfirmPassword" value="'.$this->User->ConfirmPassword.'" maxlength="100" class="SmallInput" id="txtConfirmPassword" /> '.$Required.'</dd>
      </dl>
      <div class="InputNote">'.$this->Context->GetDefinition('YourNewPasswordAgainNotes').'</div>');
      
      $this->CallDelegate('PreButtonsRender');
      
      echo('<div class="FormButtons">
         <input type="submit" name="btnSave" value="'.$this->Context->GetDefinition('Save').'" class="Button SubmitButton" />
         <a href="'.GetUrl($this->Context->Configuration, "account.php", "", "u", $this->User->UserID).'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
      </div>
      </form>
   </div>
</div>');
?>