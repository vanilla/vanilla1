<?php
// Note: This file is included from the library/Vanilla.Control.RegistrationForm.php control.

echo('<div class="SettingsForm">
   <h1>'.$this->Context->GetDefinition('RegistrationManagement').'</h1>
   <div class="Form RegistrationForm">
      <div class="InputNote">'.$this->Context->GetDefinition('RoleChangesSaved').'</div>
      <div class="FormLink"><a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl, '', '', '', '', 'PostBackAction=RegistrationChange').'">'.$this->Context->GetDefinition('ClickHereToContinue').'</a></div>
   </div>
</div>');
?>