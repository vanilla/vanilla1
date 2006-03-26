<?php
/*
Extension Name: Guest Limiter
Extension Url: http://lussumo.com/docs/
Description: Limits any account where the username is "guest" from changing the account password
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

$Context->Dictionary["ErrGuestPassword"] = "Sorry, the password for the guest user account cannot be changed.";


if ($Context->SelfUrl == "account.php") {
   class GuestPasswordForm extends PostBackControl {
      var $UserManager;
      var $User;
      
      function GuestPasswordForm (&$Context, &$UserManager, $UserID) {
         $this->ValidActions = array("Password");
         $this->Constructor($Context);
      }
      
      function Render() {
         if ($this->IsPostBack) {
            $this->Context->WarningCollector->Add($this->Contex->GetDefinition("ErrGuestPassword"));
            echo '<div class="AccountForm">
               '.$this->Get_Warnings().'
            </div>';				
         }
      }
   }
   if ($Context->SelfUrl == "account.php"
      && ForceIncomingString("PostBackAction", "") == "Password"
      && $Context->Session->UserID > 0) {
         $GuestManager = $Context->ObjectFactory->NewContextObject($Context, "UserManager");
         $UnknownUser = $GuestManager->GetUserById($Context->Session->UserID);
         if (strtolower($UnknownUser->Name) == "guest") {
            $Context->ObjectFactory->SetReference("PasswordForm", "GuestPasswordForm");
         }
   }
}
?>