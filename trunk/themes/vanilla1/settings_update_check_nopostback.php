<?php
// Note: This file is included from the library/Vanilla.Control.UpdateCheck.php control.

echo('<div class="SettingsForm">
   <h1>'.$this->Context->GetDefinition('UpdateCheck').'</h1>
   <div class="Form UpdateForm">
      '.$this->Get_Warnings().'
      '.$this->Get_PostBackForm('frmUpdateCheck').'
      <div class="InputNote">'.$this->Context->GetDefinition('UpdateCheckNotes').'</div>
      <div class="FormButtons">
         <input type="submit" name="btnCheck" value="'.$this->Context->GetDefinition('CheckForUpdates').'" class="Button SubmitButton" />
      </div>
      </form>
   </div>
</div>');
?>