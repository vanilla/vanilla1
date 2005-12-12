<?php

if ($Context->SelfUrl == "people.php" && in_array(ForceIncomingString("PostBackAction", ""), array("ApplyForm", "Apply"))) {
   
   // Add the Real name inputs to the application form
   function ApplicationForm_AddRealNameInputs($ApplyForm) {
      echo("<dt>".$ApplyForm->Context->GetDefinition("FirstName")."</dt>
      <dd><input type=\"text\" name=\"FirstName\" value=\"".$ApplyForm->Applicant->FirstName."\" class=\"Input\" maxlength=\"40\" /></dd>
      <dt>".$ApplyForm->Context->GetDefinition("LastName")."</dt>
      <dd><input type=\"text\" name=\"LastName\" value=\"".$ApplyForm->Applicant->LastName."\" class=\"Input\" maxlength=\"40\" /></dd>");
   }
   
   $Context->AddToDelegate("ApplyForm",
      "PreInputsRender",
      "ApplicationForm_AddRealNameInputs");
      
      
   // Add the requirements to the membership application processing
   function ApplicationForm_AddRequirements(&$ApplyForm) {
      $SafeUser = $ApplyForm->Applicant;
      $SafeUser->FormatPropertiesForDatabaseInput();
		Validate($ApplyForm->Context->GetDefinition("FirstNameLower"), 1, $SafeUser->FirstName, 50, "", $ApplyForm->Context);
		Validate($ApplyForm->Context->GetDefinition("LastNameLower"), 1, $SafeUser->LastName, 50, "", $ApplyForm->Context);
      // Make sure that they actually read the terms of service
		if (!$SafeUser->ReadTerms) $ApplyForm->Context->WarningCollector->Add($ApplyForm->Context->GetDefinition("ErrReadTOS"));
   }

   $Context->AddToDelegate("ApplyForm",
      "PreCreateUser",
      "ApplicationForm_AddRequirements");
      
   // Add the requirement to the identity form on the account page as well
   function IdentityForm_AddRequirements(&$IdentityForm) {
      $SafeUser = $IdentityForm->User;
      $SafeUser->FormatPropertiesForDatabaseInput();
		Validate($IdentityForm->Context->GetDefinition("FirstNameLower"), 1, $SafeUser->FirstName, 50, "", $IdentityForm->Context);
		Validate($IdentityForm->Context->GetDefinition("LastNameLower"), 1, $SafeUser->LastName, 50, "", $IdentityForm->Context);
   }

   $Context->AddToDelegate("IdentityForm",
      "PreSaveIdentity",
      "IdentityForm_AddRequirements");   
   
}
?>