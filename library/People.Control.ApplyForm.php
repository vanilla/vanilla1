<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of People: The Lussumo User Management System.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: The ApplyForm control allows new users to apply for membership.
*/

class ApplyForm extends PostBackControl {
   var $Applicant;			// A user object for the applying user
   var $FormName;				// The name of this form
	
	function ApplyForm(&$Context, $FormName = "") {
		$this->Name = "ApplyForm";
		$this->ValidActions = array("Apply");
		$this->FormName = $FormName;
		$this->Constructor($Context);
		$this->Applicant = $Context->ObjectFactory->NewContextObject($Context, "User");
		$this->Applicant->GetPropertiesFromForm();
		$this->CallDelegate("Constructor");

		if ($this->IsPostBack) {
			if ($this->PostBackAction == "Apply") {
				$um = $this->Context->ObjectFactory->NewContextObject($this->Context, "UserManager");
				
				$this->CallDelegate("PreCreateUser");
				
				$this->PostBackValidated = $um->CreateUser($this->Applicant);
			} 
		}
		$this->CallDelegate("LoadData");
	}
	
	function Render_ValidPostBack() {
		$this->CallDelegate("PreValidPostBackRender");
		include($this->Context->Configuration["THEME_PATH"]."templates/people_apply_validpostback.php");
		$this->CallDelegate("PostValidPostBackRender");
	}
	
	function Render_NoPostBack() {
		$this->Applicant->FormatPropertiesForDisplay();
		$this->PostBackParams->Add("PostBackAction", "Apply");
		$this->PostBackParams->Add("ReadTerms", $this->Applicant->ReadTerms);
		$this->CallDelegate("PreWarningsRender");
		$this->Render_Warnings();
		$this->CallDelegate("PreRender");
		include($this->Context->Configuration["THEME_PATH"]."templates/people_apply_form.php");
		$this->CallDelegate("PostRender");
	}
}
?>