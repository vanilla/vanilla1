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
* Description: The RoleForm control is used to create and manage role abilities in Vanilla.
*/

class RoleForm extends PostBackControl {
	
	var $RoleManager;
	var $RoleData;
	var $RoleSelect;
	var $Role;
	var $CategoryBoxes;

	function RoleForm(&$Context) {
      $this->Name = "RoleForm";
		$this->CategoryBoxes = "";
		$this->ValidActions = array("Roles", "Role", "ProcessRole", "RoleRemove", "ProcessRoleRemove");
		$this->Constructor($Context);
		if ($this->IsPostBack) {
			$RoleID = ForceIncomingInt("RoleID", 0);
			$ReplacementRoleID = ForceIncomingInt("ReplacementRoleID", 0);
			$this->RoleManager = $this->Context->ObjectFactory->NewContextObject($this->Context, "RoleManager");
			
			if ($this->PostBackAction == "ProcessRole"
				&& (
					($RoleID == 0 && $this->Context->Session->User->Permission("PERMISSION_ADD_ROLES"))
					|| ($RoleID > 0 && $this->Context->Session->User->Permission("PERMISSION_EDIT_ROLES"))
				)) {
				$this->Role = $this->Context->ObjectFactory->NewContextObject($this->Context, "Role");
				$this->Role->GetPropertiesFromForm($this->Context);
				$NewRole = $this->RoleManager->SaveRole($this->Role);
				if ($NewRole) {
					if ($RoleID == 0) {
						$IncomingCategories = ForceIncomingArray("AllowedCategoryID", array());
						$IncomingCategories[] = 0;
						// Look for incoming category role blocks to assign.
						$s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
						$s->SetMainTable("Category", "c");
						$s->AddSelect("CategoryID", "c");
						$s->AddWhere("CategoryID", "(".implode(",",$IncomingCategories).")", "not in", "and", "", 0);
						$BlockedCategories = $this->Context->Database->Select($s, "RoleForm", "Constructor", "An error occurred while retrieving blocked categories.");
						
						while ($Row = $this->Context->Database->GetRow($BlockedCategories)) {
							$CategoryID = ForceInt($Row["CategoryID"], 0);
							if ($CategoryID > 0) {
								$s->Clear();
								$s->SetMainTable("CategoryRoleBlock", "crb");
								$s->AddFieldNameValue("CategoryID", $CategoryID);
								$s->AddFieldNameValue("RoleID", $NewRole->RoleID);
								$s->AddFieldNameValue("Blocked", 1);
								$this->Context->Database->Insert($s, $this->Name, "SaveCategory", "An error occurred while adding new category block definitions for this role.");
							}
						}
                  
					}
					header("location: ".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Roles"));
				}
			} elseif ($this->PostBackAction == "ProcessRoleRemove" && $this->Context->Session->User->Permission("PERMISSION_REMOVE_ROLES")) {
				if ($this->RoleManager->RemoveRole($RoleID, $ReplacementRoleID)) {
					header("location: ".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Roles"));
				}
			}
			
			if (in_array($this->PostBackAction, array("RoleRemove", "Roles", "Role", "ProcessRole", "ProcessRoleRemove"))) {
				$GetUnauthenticatedRole = 1;
				if (in_array($this->PostBackAction, array("RoleRemove", "ProcessRoleRemove"))) $GetUnauthenticatedRole = 0;
				$this->RoleData = $this->RoleManager->GetRoles("", $GetUnauthenticatedRole);
			}
			if (in_array($this->PostBackAction, array("RoleRemove", "Role", "ProcessRoleRemove"))) {
				$this->RoleSelect = $this->Context->ObjectFactory->NewObject($this->Context, "Select");
				$this->RoleSelect->Name = "RoleID";
				$this->RoleSelect->CssClass = "SmallInput";
				$this->RoleSelect->AddOption("", $this->Context->GetDefinition("Choose"));
				$this->RoleSelect->AddOptionsFromDataSet($this->Context->Database, $this->RoleData, "RoleID", "Name");
			}
			if ($this->PostBackAction == "Role") {
				if ($RoleID > 0) {
					$this->Role = $this->RoleManager->GetRoleById($RoleID);
				} else {
					$this->Role = $this->Context->ObjectFactory->NewContextObject($this->Context, "Role");
				}
			}
			if (in_array($this->PostBackAction, array("ProcessRole", "ProcessRoleRemove"))) {
				// Show the form again with errors
				$this->PostBackAction = str_replace("Process", "", $this->PostBackAction);
			}
			
			if ($this->PostBackAction == "Role" && $RoleID == 0) {
				// Load all Categories
            $cm = $this->Context->ObjectFactory->NewContextObject($this->Context, "CategoryManager");
				$CategoryData = $cm->GetCategories();
				while ($Row = $Context->Database->GetRow($CategoryData)) {
					$this->CategoryBoxes .= "<div class=\"CheckBox\">".GetDynamicCheckBox("AllowedCategoryID[]", $Row["CategoryID"], in_array($Row["CategoryID"], ForceIncomingArray("AllowedCategoryID", array())), "", $Row["Name"])."</div>\r\n";
				}
			}
		}
      $this->CallDelegate("Constructor");
	}
	
	function Render() {
		if ($this->IsPostBack) {
         $this->CallDelegate("PreRender");
         
			$this->PostBackParams->Clear();
			$RoleID = ForceIncomingInt("RoleID", 0);
			
			if ($this->PostBackAction == "Role") {
				$this->PostBackParams->Set("PostBackAction", "ProcessRole");
            $this->CallDelegate("PreEditRender");
            include($this->Context->Configuration["THEME_PATH"]."templates/settings_role_edit.php");
            $this->CallDelegate("PostEditRender");
				
			} elseif ($this->PostBackAction == "RoleRemove") {
				$this->PostBackParams->Set("PostBackAction", "ProcessRoleRemove");
				$this->RoleSelect->Attributes = "onchange=\"document.location='?PostBackAction=RoleRemove&amp;RoleID='+this.options[this.selectedIndex].value;\"";
				$this->RoleSelect->SelectedID = $RoleID;
            $this->CallDelegate("PreRemoveRender");
            include($this->Context->Configuration["THEME_PATH"]."templates/settings_role_remove.php");
            $this->CallDelegate("PostRemoveRender");            
            
			} else {
            $this->CallDelegate("PreListRender");
            include($this->Context->Configuration["THEME_PATH"]."templates/settings_role_list.php");
            $this->CallDelegate("PostListRender");            
            
			}
         $this->CallDelegate("PostRender");
		}
	}
}
?>