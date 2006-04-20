<?php
// Note: This file is included from the library/Vanilla/Vanilla.Control.ThemeAndStyle.php control.

if (!$this->Context->Session->User->Permission('PERMISSION_MANAGE_THEMES') && !$this->Context->Session->User->Permission('PERMISSION_MANAGE_STYLES')) {
   $this->Context->WarningCollector->Add($this->Context->GetDefinition('PermissionError'));
   echo '<div class="SettingsForm">
         '.$this->Get_Warnings().'
   </div>';
} else {
   echo '<div id="Form" class="Account Theme">';
   
   if ($this->Context->Session->User->Permission('PERMISSION_MANAGE_THEMES')) {
      $this->PostBackParams->Set('PostBackAction', 'ProcessThemeChange');
      if (ForceIncomingString('Saved', '') == 'Theme') echo '<div class="Success">'.$this->Context->GetDefinition('ThemeChangesSaved').'</div>';
      echo '<fieldset>
         <legend>'.$this->Context->GetDefinition('ThemeManagement').'</legend>
         '.$this->Get_Warnings().'
         '.$this->Get_PostBackForm('frmThemeChange').'
         <ul>
            <li>
               <label for="ddTheme">'.$this->Context->GetDefinition('ChangeTheme').'</label>
               '.$this->ThemeSelect->Get().'
               <p class="Description">'.$this->Context->GetDefinition('ChangeThemeNotes').'</p>
            </li>
         </ul>
         <div class="Submit">
            <input type="submit" name="btnSave" value="'.$this->Context->GetDefinition('Save').'" class="Button SubmitButton" />
            <a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
         </div>
         </form>
      </fieldset>';
   }
   
   if ($this->Context->Session->User->Permission('PERMISSION_MANAGE_STYLES')) {
      $this->PostBackParams->Set('PostBackAction', 'ProcessStyleChange');
      if (ForceIncomingString('Saved', '') == 'Style') echo '<div class="Success">'.$this->Context->GetDefinition('StyleChangesSaved').'</div>';
      echo '<fieldset>
         <legend>'.$this->Context->GetDefinition('StyleManagement').'</legend>
         '.$this->Get_Warnings().'
         '.$this->Get_PostBackForm('frmThemeStyle').'
         <ul>
            <li>
               <label for="ddStyle">'.$this->Context->GetDefinition('ChangeStyle').'</label>
               '.$this->StyleSelect->Get().'
               <p class="Description">'.$this->Context->GetDefinition('ChangeStyleNotes').'</p>
            </li>
         </ul>
         <div class="Submit">
            <input type="submit" name="btnSave" value="'.$this->Context->GetDefinition('Save').'" class="Button SubmitButton" />
            <a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
         </div>
         </form>
      </fieldset>';
   }
   echo '</div>';
}
?>