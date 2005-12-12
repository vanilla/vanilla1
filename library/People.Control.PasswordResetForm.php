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
* Description: The PasswordResetForm control is used by people who have successfully retrieve password reset request emails to reset their password.
*/

class PasswordResetForm extends PostBackControl {
   var $FormName;						// The name of this form
   var $ValidatedCredentials;		// Are the user's password retrieval credentials valid
   
	// Form properties
	var $UserID;
	var $EmailVerificationKey;
	var $NewPassword;
	var $ConfirmPassword;
	
	function FormatPropertiesForDisplay() {
		$this->UserID = ForceInt($this->UserID, 0);
		$this->EmailVerificationKey = ForceString($this->EmailVerificationKey, "");
		$this->CallDelegate("FormatPropertiesForDisplay");
	}
	
	function PasswordResetForm(&$Context, $FormName = "") {
		$this->Name = "PasswordResetForm";
		$this->ValidActions = array("PasswordResetForm", "ResetPassword");
		$this->Constructor($Context);
		
		if ($this->IsPostBack) {
			$this->FormName = $FormName;
			$this->ValidatedCredentials = 0;
			
			// Set up the page
			global $Banner, $Foot;
			$Banner->Properties["CssClass"] = "PasswordReset";
			$Foot->CssClass = "PasswordReset";
			$this->Context->PageTitle = $this->Context->GetDefinition("ResetYourPassword");			
			
			// Form properties
			$this->UserID = ForceIncomingInt("u", 0);
			$this->EmailVerificationKey = ForceIncomingString("k", "");
			$this->NewPassword = ForceIncomingString("NewPassword", "");
			$this->ConfirmPassword = ForceIncomingString("ConfirmPassword", "");
			$this->CallDelegate("Constructor");
	
			$um = $this->Context->ObjectFactory->NewContextObject($this->Context, "UserManager");
			if ($this->IsPostBack) {
				$this->ValidatedCredentials = 1;
			} else {
				$this->ValidatedCredentials = $um->VerifyPasswordResetRequest($this->UserID, $this->EmailVerificationKey);
			}
			
			if ($this->ValidatedCredentials && $this->PostBackAction == "ResetPassword") {
				$this->PostBackValidated = $um->ResetPassword($this->UserID, $this->EmailVerificationKey, $this->NewPassword, $this->ConfirmPassword);
			}
			$this->CallDelegate("LoadData");
		}
	}
	
	function Render_ValidPostBack() {
		$this->CallDelegate("PreValidPostBackRender");
		echo("<div class=\"FormComplete\">
			<h1>".$this->Context->GetDefinition("PasswordReset")."</h1>
			<ul>
				<li><a href=\"people.php\">".$this->Context->GetDefinition("SignInNow")."</a>.</li>
			</ul>
		</div>");
		$this->CallDelegate("PostValidPostBackRender");
	}
	
	function Render_NoPostBack() {
		$this->CallDelegate("PreNoPostBackRender");

		$this->FormatPropertiesForDisplay();
		$this->PostBackParams->Add("PostBackAction", "ResetPassword");
		$this->PostBackParams->Add("u", $this->UserID);
		$this->PostBackParams->Add("k", $this->EmailVerificationKey);
		$this->Render_Warnings();
		
		if ($this->ValidatedCredentials) {
			echo("<div class=\"About\">
				<h1>".$this->Context->GetDefinition("AboutYourPassword")."</h1>
				<p>".$this->Context->GetDefinition("AboutYourPasswordNotes")."</p>
			</div>
			<div class=\"Form\">
				<h1>".$this->Context->GetDefinition("PasswordResetForm")."</h1>
				<p>".$this->Context->GetDefinition("ChooseANewPassword")."</p>");
			$this->Render_PostBackForm($this->FormName);
			echo("<dl class=\"InputBlock NewPasswordInputs\">
					<dt>".$this->Context->GetDefinition("NewPassword")."</dt>
					<dd><input type=\"password\" name=\"NewPassword\" value=\"\" class=\"Input\" maxlength=\"20\" /></dd>
					<dt>".$this->Context->GetDefinition("ConfirmPassword")."</dt>
					<dd><input type=\"password\" name=\"ConfirmPassword\" value=\"\" class=\"Input\" maxlength=\"20\" /></dd>
				</dl>
				<div class=\"FormButtons\"><input type=\"submit\" name=\"btnPassword\" value=\"".$this->Context->GetDefinition("Proceed")."\" class=\"Button\" /></div>
				</form>
			</div>");
		} else {
			echo("&nbsp;");
		}
		$this->CallDelegate("PostNoPostBackRender");
	}
}
?>