<?php
/*
Extension Name: Extended Application Form
Extension Url: http://lussumo.com/docs/
Description: Extends the application form so that the user's first and last name are required. It also checks that the user actually clicked to read the Terms of Service.
Version: 1.0
Author: Mark O'Sullivan
Author Url: N/A

Copyright 2003 - 2005 Mark O'Sullivan
This file is part of Lussumo's Software Library.
Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The latest source code is available at www.lussumo.com
Contact Mark O'Sullivan at mark [at] lussumo [dot] com

You should cut & paste these language definitions into your
conf/your_language.php file (replace "your_language" with your chosen language,
of course):
*/
$Context->Dictionary["FirstName"] = "First name";
$Context->Dictionary["LastName"] = "Last name";
$Context->Dictionary["FirstNameLower"] = "first name";
$Context->Dictionary["LastNameLower"] = "last name";
$Context->Dictionary["ErrReadTOS"] = "You must READ the terms of service.";


if ($Context->SelfUrl == 'people.php' && in_array(ForceIncomingString('PostBackAction', ''), array('ApplyForm', 'Apply'))) {
   
	// Add the Real name inputs to the application form
	function ApplicationForm_AddRealNameInputs($ApplyForm) {
		echo '<li>
			<label for="txtFirstName">'.$ApplyForm->Context->GetDefinition('FirstName').'</label>
			<input id="txtFirstName" type="text" name="FirstName" value="'.$ApplyForm->Applicant->FirstName.'" class="Input" maxlength="40" />
		</li>
		<li>
			<label for="LastName">'.$ApplyForm->Context->GetDefinition('LastName').'</label>
			<input id="txtLastName" type="text" name="txtLastName" value="'.$ApplyForm->Applicant->LastName.'" class="Input" maxlength="40" />
		</li>';
	}
   
   $Context->AddToDelegate('ApplyForm',
      'PreInputsRender',
      'ApplicationForm_AddRealNameInputs');
      
      
   // Add the requirements to the membership application processing
   function ApplicationForm_AddRequirements(&$ApplyForm) {
      $SafeUser = $ApplyForm->Applicant;
      $SafeUser->FormatPropertiesForDatabaseInput();
		Validate($ApplyForm->Context->GetDefinition('FirstNameLower'), 1, $SafeUser->FirstName, 50, "", $ApplyForm->Context);
		Validate($ApplyForm->Context->GetDefinition('LastNameLower'), 1, $SafeUser->LastName, 50, "", $ApplyForm->Context);
      // Make sure that they actually read the terms of service
		if (!$SafeUser->ReadTerms) $ApplyForm->Context->WarningCollector->Add($ApplyForm->Context->GetDefinition("ErrReadTOS"));
   }

   $Context->AddToDelegate('ApplyForm',
      'PreCreateUser',
      'ApplicationForm_AddRequirements');
      
   // Add the requirement to the identity form on the account page as well
   function IdentityForm_AddRequirements(&$IdentityForm) {
      $SafeUser = $IdentityForm->User;
      $SafeUser->FormatPropertiesForDatabaseInput();
		Validate($IdentityForm->Context->GetDefinition('FirstNameLower'), 1, $SafeUser->FirstName, 50, '', $IdentityForm->Context);
		Validate($IdentityForm->Context->GetDefinition('LastNameLower'), 1, $SafeUser->LastName, 50, '', $IdentityForm->Context);
   }

   $Context->AddToDelegate('IdentityForm',
      'PreSaveIdentity',
      'IdentityForm_AddRequirements');   
   
}
?>