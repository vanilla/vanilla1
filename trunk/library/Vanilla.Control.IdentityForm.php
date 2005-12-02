<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: The IdentityForm control allows users to alter their account (identity) information in Vanilla.
*/

// A postback control that allows a user/admin to change user account info
class IdentityForm extends PostBackControl {
	var $UserManager;
	var $User;
	
	function IdentityForm (&$Context, &$UserManager, &$User) {
		$this->Name = "IdentityForm";
		$this->ValidActions = array("ProcessIdentity", "Identity");
		$this->Constructor($Context);
		if ($this->IsPostBack) {
			$this->UserManager = &$UserManager;
			$this->User = &$User;
			if ($this->PostBackAction == "ProcessIdentity") {
				$this->User->Clear();
				$this->User->GetPropertiesFromForm();
				$this->CallDelegate("PreSaveIdentity");
				if ($this->UserManager->SaveIdentity($this->User)) header("location: ".$this->Context->SelfUrl.($this->User->UserID != $this->Context->Session->UserID ? "?u=".$this->User->UserID:""));
			}
		}
		$this->CallDelegate("Constructor");
	}
	
	function Render() {
		if ($this->IsPostBack) {
			if ($this->Context->Session->UserID != $this->User->UserID && !$this->Context->Session->User->Permission("PERMISSION_EDIT_USERS")) {
				$this->Context->WarningCollector->Add($this->Context->GetDefinition("PermissionError"));
				echo("<div class=\"AccountForm\">
					".$this->Get_Warnings()."
				</div>");				
			} else {				
				$this->PostBackParams->Set("PostBackAction", "ProcessIdentity");
				$this->PostBackParams->Set("u", $this->User->UserID);
				$this->PostBackParams->Set("LabelValuePairCount", (count($this->User->Attributes) > 0? count($this->User->Attributes):1));
				$Required = $this->Context->GetDefinition("Required");
				$this->CallDelegate("PreRender");
				include($this->Context->Configuration["THEME_PATH"]."templates/account_identity_form.php");
				$this->CallDelegate("PostRender");
			}
		}
	}
}
?>