<?php
// Note: This file is included from the library/People.Control.ApplyForm.php class.

if ($this->Context->Configuration['ALLOW_IMMEDIATE_ACCESS']) {
   echo('<div class="FormComplete">
      <h1>'.$this->Context->GetDefinition('ApplicationComplete').'</h1>
      <ul>
         <li><a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'?ReturnUrl=./">'.$this->Context->GetDefinition('SignInNow').'</a></li>
      </ul>
   </div>');
} else {
   echo('<div class="FormComplete">
      <h1>'.$this->Context->GetDefinition('ThankYouForInterest').'</h1>
      <ul>
         <li>'.$this->Context->GetDefinition('ApplicationWillBeReviewed').'</li>
      </ul>
   </div>');
}

?>