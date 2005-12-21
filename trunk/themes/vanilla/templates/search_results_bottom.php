<?php
// Note: This file is included from the library/Vanilla.Control.SearchForm.php class.

echo($this->PageList
	.$this->PageDetails
	."<a class=\"PageJump Top\" href=\"".GetRequestUri()."#pgtop\">".$this->Context->GetDefinition("TopOfPage")."</a>");
   
?>