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
* Description: Display and manipulate user account information
*/

include("appg/settings.php");
include("appg/init_vanilla.php");

// 1. DEFINE VARIABLES AND PROPERTIES SPECIFIC TO THIS PAGE

	// Ensure the user is allowed to view this page
	$Context->Session->Check($Configuration);
	
	if (!@$UserManager) $UserManager = $Context->ObjectFactory->NewContextObject($Context, "UserManager");
	$AccountUserID = ForceIncomingInt("u", $Context->Session->UserID);
	if (!@$AccountUser) $AccountUser = $UserManager->GetUserById($AccountUserID);
	if ($Context->Session->User && $Context->Session->User->Permission("PERMISSION_EDIT_USERS")) {
		// Allow anything
	} else {
		if ($AccountUser && $AccountUser->RoleID == 1) {
			$Context->WarningCollector->Add($Context->GetDefinition("ErrUserNotFound"));
			$AccountUser = false;
		}
	}
	
	// If a user id was not supplied, assume that this user doesn't have an active account and kick them back to the index
	if ($AccountUserID == 0) {
		header("location: index.php");
		die();
	}
	
	// Define properties of the page controls that are specific to this page
	$Menu->CurrentTab = "account";
	$Panel->CssClass = "AccountPanel";
	$Panel->BodyCssClass = "AccountPageBody";
	if ($AccountUser->UserID == $Context->Session->UserID) {
		$Context->PageTitle = $Context->GetDefinition("MyAccount");
	} else {
		$Context->PageTitle = $AccountUser->Name;
	}

// 2. BUILD PAGE CONTROLS

	// Build the control panel
	if ($Context->Session->UserID > 0) {
		$ApplicantOptions = $Context->GetDefinition("ApplicantOptions");
		$AccountOptions = $Context->GetDefinition("AccountOptions");
		$Panel->AddList($AccountOptions, 10);
		$Panel->AddList($ApplicantOptions, 11);
		if (($AccountUser->UserID == $Context->Session->UserID || $Context->Session->User->Permission("PERMISSION_EDIT_USERS")) && $AccountUser) {
			if ($AccountUser->UserID == $Context->Session->UserID) {
				$Panel->AddListItem($AccountOptions, $Context->GetDefinition("ChangeYourPersonalInformation"), $Context->SelfUrl."?PostBackAction=Identity", "", "", 10);
				$Panel->AddListItem($AccountOptions, $Context->GetDefinition("ChangeYourPassword"), $Context->SelfUrl."?PostBackAction=Password", "", "", 20);				
			} elseif ($AccountUser->UserID != $Context->Session->UserID && $Context->Session->User->Permission("PERMISSION_EDIT_USERS") && $AccountUser) {
				$Panel->AddListItem($AccountOptions, $Context->GetDefinition("ChangePersonalInformation"), $Context->SelfUrl."?PostBackAction=Identity&u=".$AccountUser->UserID, "", "", 10);
				if ($AccountUser->RoleID == 0) {
					$Panel->AddListItem($ApplicantOptions, $Context->GetDefinition("ApproveForMembership"), $Context->SelfUrl."?u=".$AccountUser->UserID."&PostBackAction=ApproveUser", "", "", 10);
					$Panel->AddListItem($ApplicantOptions, $Context->GetDefinition("DeclineForMembership"), $Context->SelfUrl."?u=".$AccountUser->UserID."&PostBackAction=DeclineUser", "", "", 20);
				} else {
					$Panel->AddListItem($AccountOptions, $Context->GetDefinition("ChangeRole"), $Context->SelfUrl."?PostBackAction=Role&u=".$AccountUser->UserID, "", "", 30);
					$Panel->AddListItem($ApplicantOptions, $Context->GetDefinition("NewApplicantSearch"), "search.php?PostBackAction=Search&Keywords=roles:Applicant;sort:Date;&Type=Users", "", "", 10);
				}
			}
		}
		if ($AccountUser->UserID == $Context->Session->UserID) {
			$Panel->AddListItem($AccountOptions, $Context->GetDefinition("ChangeForumFunctionality"), $Context->SelfUrl."?PostBackAction=Functionality", "", 30);
		}		
	}
	
	// Create the account profile
	$AccountProfile = $Context->ObjectFactory->CreateControl($Context, "Account", $AccountUser);
	
	// Forms
	$IdentityForm = $Context->ObjectFactory->CreateControl($Context, "IdentityForm", $UserManager, $AccountUser);
	$PasswordForm = $Context->ObjectFactory->CreateControl($Context, "PasswordForm", $UserManager, $AccountUserID);
	$PreferencesForm = $Context->ObjectFactory->CreateControl($Context, "PreferencesForm", $UserManager, $AccountUser);
	$AccountRoleForm = $Context->ObjectFactory->CreateControl($Context, "AccountRoleForm", $UserManager, $AccountUser);

// 3. ADD CONTROLS TO THE PAGE

	$Page->AddRenderControl($Head, $Configuration["CONTROL_POSITION_HEAD"]);
	$Page->AddRenderControl($Menu, $Configuration["CONTROL_POSITION_MENU"]);
	$Page->AddRenderControl($Panel, $Configuration["CONTROL_POSITION_PANEL"]);
	$Page->AddRenderControl($AccountProfile, $Configuration["CONTROL_POSITION_BODY_ITEM"]);
	$Page->AddRenderControl($IdentityForm, $Configuration["CONTROL_POSITION_BODY_ITEM"]);
	$Page->AddRenderControl($PasswordForm, $Configuration["CONTROL_POSITION_BODY_ITEM"]);
	$Page->AddRenderControl($PreferencesForm, $Configuration["CONTROL_POSITION_BODY_ITEM"]);
	$Page->AddRenderControl($AccountRoleForm, $Configuration["CONTROL_POSITION_BODY_ITEM"]);
	$Page->AddRenderControl($Foot, $Configuration["CONTROL_POSITION_FOOT"]);
	$Page->AddRenderControl($PageEnd, $Configuration["CONTROL_POSITION_PAGE_END"]);

// 4. FIRE PAGE EVENTS

	$Page->FireEvents();

?>