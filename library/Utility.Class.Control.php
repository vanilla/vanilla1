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
* Description: A control interface with some basic implementations
* Applications utilizing this file: Vanilla;
*/

// A standard control
class Control extends Delegation {

	// Private
  	var $PostBackAction;		// The action instruction passed by a postback form
   
   function Control(&$Context) {
		$this->Delegation($Context);
		$this->PostBackAction = ForceIncomingString("PostBackAction", "");
   }
  
   function AddItemToCollection(&$Collection, $Item, $Position = "0", $ForcePosition = "0") {
		$Position = ForceInt($Position, 0);
		$this->InsertItemAt($Collection, $Item, $Position, $ForcePosition);
	}
   
	// If forcing into a position, it will push the existing items ahead.
   // If not forcing into a position, it will slide until it finds an open spot.
	function InsertItemAt(&$Collection, $Item, $Position, $ForcePosition = "0") {
		$ForcePosition = ForceBool($ForcePosition, 0);
		if (array_key_exists($Position, $Collection)) {
			if ($ForcePosition) {
				// Move the item currently in that position ahead (forced ahead)
				$this->InsertItemAt($Collection, $Collection[$Position], $Position+1, 1);
				// Place this item at the desired position
            $Collection[$Position] = $Item;
			} else {
				$this->InsertItemAt($Collection, $Item, $Position+1);
			}
		} else {
			$Collection[$Position] = $Item;
		}
	}
	
   function Render() {}
	
	function Render_Warnings() {
		$this->CallDelegate("PreWarningsRender");
		echo($this->Get_Warnings());
		$this->CallDelegate("PostWarningsRender");
	}
	
	function Get_Warnings() {
		if ($this->Context->WarningCollector->Count() > 0) {
			return $this->CallDelegate("PreWarningsReturn")
				."<div class=\"ErrorContainer\">
					<div class=\"ErrorTitle\">".$this->Context->GetDefinition("ErrorTitle")."</div>"
					.$this->Context->WarningCollector->GetMessages()
				."</div>"
				.$this->CallDelegate("PostWarningsReturn");
		} else {
			return "";
		}
	}
}

// An implementation of the Control class for postback controls
class PostBackControl extends Control {
	// Public
   var $IsPostBack;			// Has the form been posted back?
   
	// Private
   var $ValidActions;		// An array of acceptable postback instructions
   var $PostBackValidated;	// Boolean value indicating if the postback data was validated successfully. This property is used when deciding which Render method to use.
   var $PostBackParams;		// A parameters collection used to hold postback parameters

   // This "constructor" will not actually fire when the class is
   // instantiated unless the extended class calls this method
   // from within it's own constructor
	function Constructor(&$Context) {
		$this->Control($Context);
		$this->Delegates = array();
		// Get delegates from the context object that were added before this object was instantiated
      if (array_key_exists($this->Name, $this->Context->DelegateCollection)) {
			$this->Delegates = array_merge($this->Delegates, $this->Context->DelegateCollection[$this->Name]);
		}
		
		// Define the postback action
      $this->PostBackAction = ForceIncomingString("PostBackAction", "");
		// Set the IsPostBack property (If the postback action is in this control's set of valid actions, then it has been posted back).
		$this->IsPostBack = is_array($this->ValidActions)?in_array($this->PostBackAction, $this->ValidActions):0;
		$this->PostBackValidated = 0;
		$this->PostBackParams = $this->Context->ObjectFactory->NewObject($this->Context, "Parameters");
	}
	
	function LoadData() {
	}
	
	function Render() {
		if ($this->IsPostBack) {
			$this->CallDelegate("PreRender");
			// Call different render methods based on the PostBack state.
			if ($this->PostBackValidated) {
				$this->Render_ValidPostBack();
			} else {
				$this->Render_NoPostBack();
			}
			$this->CallDelegate("PostRender");
		}
	}
	
	function Render_NoPostBack() {}
	
	function Render_ValidPostBack() {}
	
	function Render_PostBackForm($FormName = "", $PostBackMethod = "post", $TargetUrl = "") {
		echo($this->Get_PostBackForm($FormName, $PostBackMethod, $TargetUrl));
	}
	
	function Get_PostBackForm($FormName = "", $PostBackMethod = "post", $TargetUrl = "") {
		$TargetUrl = ForceString($TargetUrl, $this->Context->SelfUrl);
		if ($FormName != "") $FormName = " name=\"".$FormName."\"";
		return "<form".$FormName." method=\"".$PostBackMethod."\" action=\"".$TargetUrl."\">"
			.$this->PostBackParams->GetHiddenInputs();
	}
}
?>