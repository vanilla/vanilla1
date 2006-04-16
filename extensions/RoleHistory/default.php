<?php
/*
Extension Name: Role History
Extension Url: http://lussumo.com/docs/
Description: Adds a complete summary of all role changes to each user's account profile.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/

Copyright 2003 - 2005 Mark O'Sullivan
This file is part of Vanilla.
Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The latest source code for Vanilla is available at www.lussumo.com
Contact Mark O'Sullivan at mark [at] lussumo [dot] com

You should cut & paste these language definitions into your
conf/your_language.php file (replace "your_language" with your chosen language,
of course):
*/
$Context->Dictionary["RoleHistory"] = "Role history";
$Context->Dictionary["NoRoleHistory"] = "This user does not appear to have been assigned to any roles.";
$Context->Dictionary["RoleAssignedByX"] = "Role assigned by //1 with the following notes:";


if ($Context->SelfUrl == "account.php") {

   class RoleHistory extends Control {
      var $History;	// The history data for the specified user
      
      function RoleHistory(&$Context, &$UserManager, $UserID) {
         $this->PostBackAction = ForceIncomingString("PostBackAction", "");
         $this->Name = "RoleHistory";
         $this->Control($Context);
         if ($this->PostBackAction == "") $this->History = $UserManager->GetUserRoleHistoryByUserId($UserID);
      }
      
      function Render() {
         $this->CallDelegate("PreRender");
         if ($this->Context->WarningCollector->Count() == 0 && $this->PostBackAction == "") {
               echo '<h2>'.$this->Context->GetDefinition("RoleHistory").'</h2>
					<ul>';
               // Loop through the user's role history
               $UserHistory = $this->Context->ObjectFactory->NewObject($this->Context, "UserRoleHistory");
					while ($Row = $this->Context->Database->GetRow($this->History)) {
						$UserHistory->Clear();
						$UserHistory->GetPropertiesFromDataSet($Row);
						$UserHistory->FormatPropertiesForDisplay($this->Context);
						
						echo '<li>
							<h3>
								'.$UserHistory->Role.' <small>('.TimeDiff($this->Context, $UserHistory->Date, mktime()).')</small>
							</h3>
							<p class="Info">
								'.str_replace("//1",
								($UserHistory->AdminUserID == 0?$this->Context->GetDefinition("Applicant"):"<a href=\"".GetUrl($this->Context->Configuration, "account.php", "", "u", $UserHistory->AdminUserID)."\">".$UserHistory->AdminUsername."</a>")
								, 
								$this->Context->GetDefinition("RoleAssignedByX")).'
							</p>
							<p class="Note">
								'.$UserHistory->Notes.'
							</p>
						</li>';						
					}
            echo "</ul>";
         }
         $this->CallDelegate("PostRender");
      }
   }
   // Don't reload objects if you don't need to (ie. If another extension has already loaded it)
   if (!@$UserManager) $UserManager = $Context->ObjectFactory->NewContextObject($Context, "UserManager");
   $AccountUserID = ForceIncomingInt("u", $Context->Session->UserID);
   if (!@$AccountUser) $AccountUser = $UserManager->GetUserById($AccountUserID);
  	$Page->AddRenderControl($Context->ObjectFactory->NewContextObject($Context, "RoleHistory", $UserManager, $AccountUserID), $Configuration["CONTROL_POSITION_BODY_ITEM"]);
}
?>