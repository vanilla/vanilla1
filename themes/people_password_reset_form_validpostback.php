<?php
// Note: This file is included from the library/People.Control.PasswordResetForm.php control.

echo('<div class="FormComplete">
   <h1>'.$this->Context->GetDefinition('PasswordReset').'</h1>
   <ul>
      <li><a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'">'.$this->Context->GetDefinition('SignInNow').'</a>.</li>
   </ul>
</div>');
?>