<?php
// Note: This file is included from the library/People.Control.SignInForm.php control.

echo('<div class="FormComplete">
   <h1>'.$this->Context->GetDefinition('YouAreSignedIn').'</h1>
   <ul>
      <li><a href="'.GetUrl($this->Context->Configuration, 'index.php').'">'.$this->Context->GetDefinition('ClickHereToContinueToDiscussions').'</a></li>
      <li><a href="'.GetUrl($this->Context->Configuration, 'categories.php').'">'.$this->Context->GetDefinition('ClickHereToContinueToCategories').'</a></li>');
      if ($this->ApplicantCount > 0) echo('<li><a href="'.GetUrl($this->Context->Configuration, 'search.php', '', '', '', '','PostBackAction=Search&amp;Keywords=roles:Applicant;sort:Date;&amp;Type=Users').'">'.$this->Context->GetDefinition('ReviewNewApplicants').'</a> (<strong>'.$this->ApplicantCount.' '.$this->Context->GetDefinition('New').'</strong>)</li>');
   echo('</ul>
</div>');
?>