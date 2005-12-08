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
* Description: The UpdateCheck control is used to ping the lussumo.com server to check for upgrades to Vanilla.
*/

class UpdateCheck extends PostBackControl {
   
   var $MostRecentVanillaVersion;
	
	function UpdateCheck(&$Context) {
      $this->MostRecentVanillaVersion = "";
      $this->Name = "UpdateCheck";
		$this->ValidActions = array("UpdateCheck", "ProcessUpdateCheck");
		$this->Constructor($Context);
		if (!$this->Context->Session->User->Permission("PERMISSION_CHECK_FOR_UPDATES")) {
			$this->IsPostBack = 0;
		} elseif ($this->IsPostBack && $this->PostBackAction == "ProcessUpdateCheck") {
         // Ping lussumo.com for the latest version of Vanilla
         $Lines = file($this->Context->Configuration["UPDATE_URL"]);
         if (count($Lines) == 1) {
            $this->MostRecentVanillaVersion = trim($Lines[0]);
            $this->PostBackValidated = 1;
         } else {
            $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrUpdateCheckFailure"));
         }
		}
      $this->CallDelegate("Constructor");
	}
	
	function Render_ValidPostBack() {
      if ($this->MostRecentVanillaVersion != VANILLA_VERSION) {
         $Message = $this->Context->GetDefinition("PleaseUpdateYourInstallation");
         $Message = str_replace(array("//1", "//2"), array(VANILLA_VERSION, $this->MostRecentVanillaVersion), $Message);
      } else {
         $Message = $this->Context->GetDefinition("YourInstallationIsUpToDate");
      }      
      $this->CallDelegate("PreValidPostBackRender");
      include($this->Context->Configuration["THEME_PATH"]."templates/settings_update_check_validpostback.php");
      $this->CallDelegate("PostValidPostBackRender");
	}
	
	function Render_NoPostBack() {
		if ($this->IsPostBack) {
         $this->CallDelegate("PreNoPostBackRender");
			$this->PostBackParams->Clear();
			$this->PostBackParams->Set("PostBackAction", "ProcessUpdateCheck");
         include($this->Context->Configuration["THEME_PATH"]."templates/settings_update_check_nopostback.php");
         $this->CallDelegate("PostNoPostBackRender");
		}
	}
}
?>