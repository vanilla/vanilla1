<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Lussumo's Software Library.
* Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
* 
* Description:  Handle creating and sending emails
* Applications utilizing this file: Vanilla;
*/
class Email {
	var $Recipients;		// Array of recipients
	var $CCRecipients;	// Array of cc'd recipients
	var $BCCRecipients;	// Array of bcc'd recipients
	var $FromName;			// String
	var $FromEmail;		// String
	var $Subject;			// Subject line of the email
	var $BodyText;			// Text version of the email
	var $BodyHtml;			// HTML version of the email
	var $HtmlOn;			// Boolean value indicating if this email should be sent as html
	// Standard properties
	var $Name;				// The name of this class
	var $Context;

	function AddBCCRecipient($Email, $Name = "") {
		$this->AddTo("BCCRecipients", $Email, $Name);
	}
	
	function AddCCRecipient($Email, $Name = "") {
		$this->AddTo("CCRecipients", $Email, $Name);
	}
	
	function AddFrom($Email, $Name = "") {
		$this->FromEmail = $Email;
		$this->FromName = $Name;
	}
	
	function AddRecipient($Email, $Name = "") {
		$this->AddTo("Recipients", $Email, $Name);
	}
	
	function AddTo($ToType, $Email, $Name = "") {
		$Found = 0;
		if ($this->$ToType) {
			foreach($this->$ToType as $key => $value) {
				if ($value["Email"] == $Email) $Found = 1;
			}
		}
		if (!$Found) {
			if ($ToType == "Recipients") {
				$this->Recipients[] = array("Email" => $Email, "Name" => $Name);
			} elseif ($ToType == "CCRecipients") {
				$this->CCRecipients[] = array("Email" => $Email, "Name" => $Name);
			} elseif ($ToType == "BCCRecipients") {
				$this->BCCRecipients[] = array("Email" => $Email, "Name" => $Name);
			}
		}
	}
	
	function AddToHeader($ToType) {
		$Header = "";
		$ToTypeCount = count($this->$ToType);
		for ($i = 0; $i < $ToTypeCount; $i++) {
			if ($i > 0) $Header .= ", ";
			if ($ToType == "Recipients") {
				$Header .= $this->Recipients[$i]["Name"]." <".$this->Recipients[$i]["Email"].">"; 
			} elseif ($ToType == "CCRecipients") {
				$Header .= $this->CCRecipients[$i]["Name"]." <".$this->CCRecipients[$i]["Email"].">"; 
			} elseif ($ToType == "BCCRecipients") {
				$Header .= $this->BCCRecipients[$i]["Name"]." <".$this->BCCRecipients[$i]["Email"].">"; 
			}			
		}
		if ($Header != "") $Header .= "\n";
		return $Header;
	}
	
	function Clear() {
		$this->Name = "Email";
		$this->Recipients = array();
		$this->CCRecipients = array();
		$this->BCCRecipients = array();
		$this->FromName = "";
		$this->FromEmail = "";
		$this->Subject = "";
		$this->BodyText = "";
		$this->BodyHtml = "";
		$this->HtmlOn = 1;
	}
	
	function Email(&$Context) {
		$this->Clear();
		$this->Context = &$Context;
	}

	function FormatTextAsHtml($Text) {
		return "<div style=\"font-family: Trebuchet MS, Arial, Tahoma, Sans-Serif, Verdana; color: #000; font-size: 12px;\">"
		.str_replace("\r\n", "<br />", $Text)
		."</div>";
	}
	
	function Send() {
		// Check that requied properties are supplied
		if ($this->ValidateEmail()) {
			$Header = "";
			if ($this->HtmlOn) {
				// If there is no html body, use the text body
				if ($this->BodyHtml == "") $this->BodyHtml = $this->FormatTextAsHtml($this->BodyText);			
				$OB="----=_OuterBoundary_000";
				$IB="----=_InnerBoundery_001";
				$Header .= "Content-Type: multipart/mixed;\n\tboundary=\"".$OB."\"\n";
			
				//Messages start with text/html alternatives in OB
				$Message = "This is a multi-part message in MIME format.\n";
				$Message .= "\n--".$OB."\n";
				$Message .= "Content-Type: multipart/alternative;\n\tboundary=\"".$IB."\"\n\n";
			
				//plaintext section 
				$Message .= "\n--".$IB."\n";
				$Message .= "Content-Type: text/plain;\n\tcharset=\"iso-8859-1\"\n";
				$Message .= "Content-Transfer-Encoding: quoted-printable\n\n";
				// plaintext goes here
				$Message .= $this->BodyText."\n\n";
			
				// html section 
				$Message .= "\n--".$IB."\n";
				$Message .= "Content-Type: text/html;\n\tcharset=\"iso-8859-1\"\n";
				$Message .= "Content-Transfer-Encoding: base64\n\n";
				// html goes here 
				$Message .= chunk_split(base64_encode($this->BodyHtml))."\n\n";
			
				// end of IB
				$Message .= "\n--".$IB."--\n";
			
				//message ends
				$Message.="\n--".$OB."--\n";
				$Header .= "MIME-Version: 1.0\n";
			} else {
				$Message = $this->BodyText;
			}
			// Build the headers
			$Header .= "From: ".$this->FromName." <".$this->FromEmail.">\n"; 
			$Header .= "To: ".$this->AddToHeader("Recipients");
			if (count($this->CCRecipients) > 0) $Header .= "Cc: ".$this->AddToHeader("CCRecipients");
			if (count($this->BCCRecipients) > 0) $Header .= "Bcc: ".$this->AddToHeader("BCCRecipients");
			$Header .= "Reply-To: ".$this->FromName." <".$this->FromEmail.">\n"; 
			
			if (!mail($this->Recipients[0]["Email"], $this->Subject, $Message, $Header)) $this->Context->ErrorManager->AddError($this->Context, $this->Name, "Send", "An error occurred while sending the email.");
		}
	}
	
	function ValidateEmail() {
		$this->Subject = str_replace(array("\r","\n"),"",$this->Subject);
		if ($this->Subject == "") $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrEmailSubject"));
		if (count($this->Recipients) == 0) $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrEmailRecipient"));
		if ($this->FromEmail == "") $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrEmailFrom"));
		if ($this->BodyHtml == "" && $this->BodyText == "") $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrEmailBody"));
		return $this->Context->WarningCollector->Iif();
	}
}
?>