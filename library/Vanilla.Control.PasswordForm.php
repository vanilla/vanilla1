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
* Description: The PasswordForm control allows users to change their password in Vanilla.
*/

class PasswordForm extends PostBackControl {
	var $UserManager;
	var $User;
	
	function PasswordForm (&$Context, &$UserManager, $UserID) {
		$this->Name = "PasswordForm";
		$this->ValidActions = array("ProcessPassword", "Password");
		$this->Constructor($Context);
		if ($this->IsPostBack) {
			$this->UserManager = &$UserManager;
			$this->User = $this->Context->ObjectFactory->NewContextObject($Context, "User");
			$this->User->GetPropertiesFromForm();
			$this->User->UserID = $UserID;
			if ($this->PostBackAction == "ProcessPassword") {
				if ($this->UserManager->ChangePassword($this->User)) header("location: ".$this->Context->SelfUrl);
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
				$this->PostBackParams->Set("PostBackAction", "ProcessPassword");
				$this->PostBackParams->Set("u", $this->User->UserID);
				$Required = $this->Context->GetDefinition("Required");
				$this->CallDelegate("PreRender");
				include($this->Context->Configuration["THEME_PATH"]."templates/account_password_form.php");
				$this->CallDelegate("PostRender");
			}
		}
	}
}
?>