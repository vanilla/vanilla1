<?php
// Note: This file is included from the library/Vanilla.Control.SearchForm.php class.

echo($this->PageList
	.$this->PageDetails
	."<a class=\"PageJump Top\" href=\"".FormatStringForDisplay($_SERVER["REQUEST_URI"])."#pgtop\">".$this->Context->GetDefinition("TopOfPage")."</a>");
   
?>