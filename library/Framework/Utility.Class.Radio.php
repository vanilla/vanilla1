<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Lussumo's Software Library.
* Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
* 
* Description: Class that builds and maintains a radio list.
* Applications utilizing this file: Vanilla;
*/
class Radio {
	var $Name;			// Name of the select list
	var $SelectedID;	// ID to be selected in the list
	var $CssClass;		// Stylesheet class name
	var $Attributes;	// Additional attributes for select element
	var $aOptions;		// Array for holding radio options
   
	function AddOption($IdValue, $DisplayValue) {
		$this->aOptions[] = array("IdValue" => $IdValue, "DisplayValue" => $DisplayValue);
	}
	
	function AddOptionsFromAssociativeArray($Array, $KeyPrefix) {
		while (list($key, $val) = each($Array)) {
			$this->AddOption($KeyPrefix.$key, $val);
		}		
	}
	
	function AddOptionsFromDataSet(&$Database, $DataSet, $IdField, $DisplayField) {
		while ($rows = $Database->GetRow($DataSet)) {
			$this->AddOption($rows[$IdField], $rows[$DisplayField]);
		}	
	}	
	
	function Clear() {
		$this->Name = "";
		$this->SelectedID = 0;
		$this->CssClass = "MultilineRadio";
		$this->Attributes = "";
		$this->aOptions = array();
	}
	
	function ClearOptions() {
		$this->aOptions = array();
	}
	
	function Get() {
		$sReturn = "";
		$OptionCount = count($this->aOptions);
		$i = 0;
		for ($i = 0; $i < $OptionCount ; $i++) {
			$sReturn .= "<div class=\"".$this->CssClass."\"><label><input type=\"radio\" name=\"".$this->Name."\" ".$this->Attributes." value=\"".$this->aOptions[$i]["IdValue"]."\"";
			if ($this->aOptions[$i]["IdValue"] == $this->SelectedID) $sReturn .= " checked=\"checked\"";
			$sReturn .= "/> ".$this->aOptions[$i]["DisplayValue"]."</label></div>\r\n";
		}
		return $sReturn;
	}
	
	function Radio() {
		$this->Clear();
	}
	
	function Write() {
		echo($this->Get());
	}	
}
?>