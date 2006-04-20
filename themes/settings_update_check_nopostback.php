<?php
// Note: This file is included from the library/Framework/Framework.Control.UpdateCheck.php control.

echo '<div id="Form" class="Account UpdateCheck">
   <fieldset>
      <legend>'.$this->Context->GetDefinition('UpdateCheck').'</legend>
      '.$this->Get_Warnings().'
      '.$this->Get_PostBackForm('frmUpdateCheck').'
      <p>'.$this->Context->GetDefinition('UpdateCheckNotes').'</p>
      <div class="Submit">
         <input type="submit" name="btnCheck" value="'.$this->Context->GetDefinition('CheckForUpdates').'" class="Button SubmitButton Update" />
         <a href="'.GetUrl($this->Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=Styles').'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
      </div>
      </form>
   </fieldset>';
   
   $this->PostBackParams->Set('PostBackAction', 'ProcessUpdateReminder');
   
   if (ForceIncomingBool('Saved', 0)) echo '<div class="Success">'.$this->Context->GetDefinition('ReminderChangesSaved').'</div>';
   
   echo '
   <fieldset>
      <legend>'.$this->Context->GetDefinition('UpdateReminders').'</legend>
      '.$this->Get_PostBackForm('frmUpdateReminders').'
      <p>'.$this->Context->GetDefinition('UpdateReminderNotes').'</p>
      <ul>
         <li>
            <label for="dReminder">'.$this->Context->GetDefinition('ReminderLabel').'</label>
            '.$this->ReminderSelect->Get().'
         </li>
      </ul>
      <div class="Submit">
         <input type="submit" name="btnCheck" value="'.$this->Context->GetDefinition('Save').'" class="Button SubmitButton UpdateReminder" />
         <a href="'.GetUrl($this->Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=Styles').'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
      </div>
      </form>
   </fieldset>
</div>';
?>