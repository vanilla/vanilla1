<?php
// Note: This file is included from the library/Vanilla.Control.SearchForm.php class.

$this->PageDetails = "<div class=\"PageDetails\">".($this->PageList != "" ? $this->PageDetails : $this->Context->GetDefinition("NoResultsFound"))."</div>";

echo("<div class=\"Title SearchTitle\">".$this->Context->GetDefinition($this->Search->Type)."</div>"
.$this->PageList
.$this->PageDetails);

?>