<?php
// Note: This file is included from the library/People.Control.SignInForm.php control.

echo("<div class=\"FormComplete\">
   <h1>".$this->Context->GetDefinition("YouAreSignedIn")."</h1>
   <ul>
      <li><a href=\"./\">".$this->Context->GetDefinition("ClickHereToContinueToDiscussions")."</a></li>
      <li><a href=\"./categories.php\">".$this->Context->GetDefinition("ClickHereToContinueToCategories")."</a></li>");
if ($this->ApplicantCount > 0) echo("<li><a href=\"search.php?PostBackAction=Search&Keywords=roles:Applicant;sort:Date;&Type=Users\">".$this->Context->GetDefinition("ReviewNewApplicants")."</a> (<strong>".$this->ApplicantCount." ".$this->Context->GetDefinition("New")."</strong>)</li>");
   echo("</ul>
</div>");
?>