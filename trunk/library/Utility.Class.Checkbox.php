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
* Description: Class that builds and maintains a checkbox list.
* Applications utilizing this file: Vanilla;
*/
class Checkbox {
	var $Name;		      	// Name of the select list
	var $CssClass;	   	   // Stylesheet class name
	var $Attributes;     	// Additional attributes for each checkbox element
   var $LabelAttributes;	// Specifically target the label attributes
   var $LabelOnClick;      // Specifically target the label onclick event
	var $aOptions;	      	// Array for holding checkbox options
   
	function AddOption($IdValue, $DisplayValue, $Checked, $FlipCheckedValue, $ElementID = "") {
		$this->aOptions[] = array("IdValue" => $IdValue,
         "DisplayValue" => $DisplayValue,
         "Checked" => $Checked,
         "FlipCheckedValue" => $FlipCheckedValue,
         "ElementID" => $ElementID);
	}
	
	function AddOptionsFromDataSet(&$Database, $DataSet, $IdField, $DisplayField, $CheckedField, $FlipCheckedValue = "0") {
      $FlipCheckedValue = ForceBool($FlipCheckedValue, 0);
		while ($rows = $Database->GetRow($DataSet)) {
			$this->AddOption($rows[$IdField], $rows[$DisplayField], $rows[$CheckedField], $FlipCheckedValue);
		}	
	}	
	
	function Clear() {
		$this->Name = "";
		$this->CssClass = "MultilineRadio";
		$this->Attributes = "";
		$this->LabelAttributes = "";
		$this->LabelOnClick = "";
		$this->aOptions = array();
	}
	
	function ClearOptions() {
		$this->aOptions = array();
	}
	
	function Get() {
		$sReturn = "";
		$OptionCount = count($this->aOptions);
		for ($i = 0; $i < $OptionCount ; $i++) {
			$ElementID = str_replace(" ", "_",$this->Name)."_".$this->aOptions[$i]["IdValue"];
         $Checked = $this->aOptions[$i]["Checked"];
         if ($this->aOptions[$i]["FlipCheckedValue"]) $Checked = FlipBool($Checked);
			$sReturn .= "<div class=\"".$this->CssClass."\"";
   			if ($this->aOptions[$i]["ElementID"] != "") $sReturn .= " id=\"".$this->aOptions[$i]["ElementID"]."\"";
			$sReturn .= ">"
            .GetDynamicCheckBox($ElementID, $this->aOptions[$i]["IdValue"], $Checked, "", $this->aOptions[$i]["DisplayValue"], $this->Attributes)
				."</div>\r\n";
		}
		return $sReturn;
	}
	
	function Checkbox() {
		$this->Clear();
	}
	
	function Write() {
		echo($this->Get());
	}	
}
?>