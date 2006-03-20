<?php
// Note: This file is included from the library/Vanilla.Control.UpdateCheck.php control.

echo '<div id="Form" class="Account Identity">
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
   </fieldset>
</div>';
?>