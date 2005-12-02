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
* Description: The RegistrationForm control is used to define how registration roles are applied to new members.
*/

class RegistrationForm extends PostBackControl {
	var $RoleManager;
	var $RoleSelect;
	
	function RegistrationForm (&$Context) {
      $this->Name = "RegistrationForm";
		$this->ValidActions = array("ProcessRegistrationChange", "RegistrationChange");
		$this->Constructor($Context);
		if (!$this->Context->Session->User->Permission("PERMISSION_MANAGE_REGISTRATION")) {
			$this->IsPostBack = 0;
		} elseif ($this->IsPostBack) {
			$RoleID = ForceIncomingString("RoleID", "");
			if ($RoleID == "") $RoleID = $this->Context->Configuration["DEFAULT_ROLE"];
			$this->RoleManager = $this->Context->ObjectFactory->NewContextObject($this->Context, "RoleManager");
			$this->RoleSelect = $this->Context->ObjectFactory->NewObject($this->Context, "Select");
			$this->RoleSelect->Name = "RoleID";
			// Add the applicant faux-role
			$this->RoleSelect->AddOption(0, $this->Context->GetDefinition("Applicant"));
			// Add all other roles
			$this->RoleSelect->AddOptionsFromDataSet($this->Context->Database, $this->RoleManager->GetRoles(), "RoleID", "Name");
			$this->RoleSelect->SelectedID = $RoleID;
			
			$ApprovedRoleID = ForceIncomingInt("ApprovedRoleID", $this->Context->Configuration["APPROVAL_ROLE"]);
			$this->ApprovedRoleSelect = $this->Context->ObjectFactory->NewObject($this->Context, "Select");
			$this->ApprovedRoleSelect->Name = "ApprovedRoleID";
			$this->ApprovedRoleSelect->AddOptionsFromDataSet($this->Context->Database, $this->RoleManager->GetRoles(), "RoleID", "Name");
			$this->ApprovedRoleSelect->SelectedID = $ApprovedRoleID;
			
			if ($this->PostBackAction == "ProcessRegistrationChange") {
				// Make the immediate access option default to "0" if the "default" role
            // for new members is "0" (applicant)
				$AllowImmediateAccess = 0;
				if ($RoleID > 0) {
					$Role = $this->RoleManager->GetRoleById($RoleID);
					$AllowImmediateAccess = $Role->PERMISSION_SIGN_IN?"1":"0";
				}
				
				$ConstantsFile = $this->Context->Configuration["APPLICATION_PATH"]."appg/settings.php";
				$ConstantManager = $this->Context->ObjectFactory->NewContextObject($this->Context, "ConstantManager");
				$ConstantManager->DefineConstantsFromFile($ConstantsFile);
				// Set the constants to their new values
				$ConstantManager->SetConstant("agDEFAULT_ROLE", $RoleID);
				$ConstantManager->SetConstant("agALLOW_IMMEDIATE_ACCESS", $AllowImmediateAccess);
				$ConstantManager->SetConstant("agAPPROVAL_ROLE", $ApprovedRoleID);
				// Save the settings file
				$ConstantManager->SaveConstantsToFile($ConstantsFile);
				if ($this->Context->WarningCollector->Iif()) $this->PostBackValidated = 1;
			}
		}
      $this->CallDelegate("Constructor");
	}
	
	function Render_ValidPostBack() {
      $this->CallDelegate("PreValidPostBackRender");
      include($this->Context->Configuration["THEME_PATH"]."templates/settings_registration_form_validpostback.php");
      $this->CallDelegate("PostValidPostBackRender");
	}
	
	function Render_NoPostBack() {
		if ($this->IsPostBack) {
         $this->CallDelegate("PreNoPostBackRender");
         include($this->Context->Configuration["THEME_PATH"]."templates/settings_registration_form_nopostback.php");
         $this->CallDelegate("PostNoPostBackRender");
		}
	}
}
?>