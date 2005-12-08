<?php
/*
Extension Name: Role History
Extension Url: http://lussumo.com/docs/
Description: Adds a complete summary of all role changes to each user's account profile.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/
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
            echo("<div class=\"RoleHistory\">
               <h1>".$this->Context->GetDefinition("RoleHistory")."</h1>");
               // Loop through the user's role history
               $UserHistory = $this->Context->ObjectFactory->NewObject($this->Context, "UserRoleHistory");
               if ($this->Context->Database->RowCount($this->History) == 0) {
                  echo("<blockquote>".$this->Context->GetDefinition("NoRoleHistory")."</blockquote>");
               } else {
                  while ($Row = $this->Context->Database->GetRow($this->History)) {
                     $UserHistory->Clear();
                     $UserHistory->GetPropertiesFromDataSet($Row);
                     $UserHistory->FormatPropertiesForDisplay($this->Context);
                     
                     echo("<blockquote>
                        <h2>".$UserHistory->Role."</strong></h2> <small>(".TimeDiff($this->Context, $UserHistory->Date, mktime()).")</small>
                        <h3>".$this->Context->GetDefinition("RoleAssignedBy")." ".($UserHistory->AdminUserID == 0?$this->Context->GetDefinition("Applicant"):"<a href=\"account.php?u=".$UserHistory->AdminUserID."\">".$UserHistory->AdminUsername."</a>")." ".$this->Context->GetDefinition("WithTheFollowingNotes")."</h3>
                        <p>".$UserHistory->Notes."</p>
                     </blockquote>");
                  }
               }
            echo("</div>");
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