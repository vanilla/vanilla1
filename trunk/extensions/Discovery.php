<?php
/*
Extension Name: Applicant Discovery
Extension Url: http://lussumo.com/docs/
Description: Adds a "How did you discover this forum" question to the application form.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://markosullivan.ca/
*/

/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*/

if ($Context->SelfUrl == "account.php") {

	// Displays a user's discovery information (for admins only)
	class Discovery extends Control {
		var $User;		// The user who's history is being reviewed
		
		function Discovery(&$Context, &$User) {
			$this->PostBackAction = ForceIncomingString("PostBackAction", "");
			$this->Name = "Discovery";
			$this->Control($Context);
			$this->User = &$User;
		}
		
		function Render() {
			if ($this->Context->WarningCollector->Count() == 0 && $this->PostBackAction == "") {
				if ($this->User->RoleID == 0 && $this->User->Discovery != "" && $this->Context->Session->User->Permission("PERMISSION_APPROVE_APPLICANTS")) {
					echo("<div class=\"Discovery\">
						<h1>".$this->Context->GetDefinition("Discovery")."</h1>
						<blockquote>".FormatHtmlStringInline($this->User->Discovery)."</blockquote>
					</div>");
				}
			}
		}
	}
	
   if (!@$UserManager) $UserManager = $Context->ObjectFactory->NewContextObject($Context, "UserManager");
   $AccountUserID = ForceIncomingInt("u", $Context->Session->UserID);
   if (!@$AccountUser) $AccountUser = $UserManager->GetUserById($AccountUserID);
	$Page->AddRenderControl($Context->ObjectFactory->NewContextObject($Context, "Discovery", $AccountUser), $Configuration["CONTROL_POSITION_BODY_ITEM"] + 5);   
}

if ($Context->SelfUrl == "people.php" && in_array(ForceIncomingString("PostBackAction", ""), array("ApplyForm", "Apply"))) {
	
	function ApplyForm_DiscoveryQuestion(&$FormControl) {
		echo("<div class=\"InputBlock DiscoveryInput\">
         <div class=\"InputLabel\">".$FormControl->Context->GetDefinition("HowDidYouFindUs")."</div>
         <textarea name=\"Discovery\" class=\"ApplicationTextbox\">".$FormControl->Applicant->Discovery."</textarea>
      </div>");
	}
	
	$Context->AddToDelegate("ApplyForm",
		"PreTermsCheckRender",
		"ApplyForm_DiscoveryQuestion");
	
	// Add the requirements to the membership application processing
   function ApplyForm_AddDiscoveryRequirements(&$ApplyForm) {
		if ($ApplyForm->Applicant->Discovery == "") {
			$ApplyForm->Context->WarningCollector->Add($ApplyForm->Context->GetDefinition("ErrDiscovery"));
		}
   }

   $Context->AddToDelegate("ApplyForm",
      "PreCreateUser",
      "ApplyForm_AddDiscoveryRequirements");
	

	// Manipulate the account creation so that the discovery information is saved
   function UserManager_SaveDiscoveryInfo(&$UserManager) {
		$SqlBuilder = &$UserManager->DelegateParameters["SqlBuilder"];
		$User = &$UserManager->DelegateParameters["User"];
		
		$SqlBuilder->AddFieldNameValue("Discovery", $User->Discovery);
	}
	
	$Context->AddToDelegate("UserManager",
		"PreDataInsert",
		"UserManager_SaveDiscoveryInfo");	

}
?>