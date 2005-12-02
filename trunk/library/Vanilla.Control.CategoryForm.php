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
* Description: The CategoryForm control is used to create and manipulate categories in Vanilla.
*/

class CategoryForm extends PostBackControl {
	
	var $CategoryManager;
	var $CategoryData;
	var $CategorySelect;
	var $CategoryRoles;
	var $Category;

	function CategoryForm(&$Context) {
      $this->Name = "CategoryForm";
		$this->ValidActions = array("Categories", "ProcessCategories", "Category", "ProcessCategory", "CategoryRemove", "ProcessCategoryRemove");
		$this->Constructor($Context);
		if ($this->IsPostBack) {
			$CategoryID = ForceIncomingInt("CategoryID", 0);
			$ReplacementCategoryID = ForceIncomingInt("ReplacementCategoryID", 0);
			$this->CategoryManager = $this->Context->ObjectFactory->NewContextObject($this->Context, "CategoryManager");
			
			if ($this->PostBackAction == "ProcessCategories") {
				if ($this->Context->Session->User->Permission("PERMISSION_SORT_CATEGORIES")) {
					$this->CategoryManager->SaveCategoryOrder();
					header("location: settings.php");
				} else {
					$this->IsPostBack = 0;
				}
			} elseif ($this->PostBackAction == "ProcessCategory") {
				$this->Category = $this->Context->ObjectFactory->NewObject($this->Context, "Category");
				$this->Category->GetPropertiesFromForm($this->Context);
				if (($this->Category->CategoryID > 0 && $this->Context->Session->User->Permission("PERMISSION_EDIT_CATEGORIES"))
					|| ($this->Category->CategoryID == 0 && $this->Context->Session->User->Permission("PERMISSION_ADD_CATEGORIES"))) {
					if ($this->CategoryManager->SaveCategory($this->Category)) {
						header("location: settings.php?PostBackAction=Categories");
					}
				} else {
					$this->IsPostBack = 0;
				}
			} elseif ($this->PostBackAction == "ProcessCategoryRemove") {
				if ($this->Context->Session->User->Permission("PERMISSION_REMOVE_CATEGORIES")) {
					if ($this->CategoryManager->RemoveCategory($CategoryID, $ReplacementCategoryID)) {
						header("location: settings.php?PostBackAction=Categories");
					}
				} else {
					$this->IsPostBack = 0;
				}
			}
			
			if (in_array($this->PostBackAction, array("CategoryRemove", "Categories", "Category", "ProcessCategory", "ProcessCategoryRemove"))) {
				$this->CategoryData = $this->CategoryManager->GetCategories(1);
			}
			if (in_array($this->PostBackAction, array("CategoryRemove", "Category", "ProcessCategoryRemove"))) {
				$this->CategorySelect = $this->Context->ObjectFactory->NewObject($this->Context, "Select");
				$this->CategorySelect->Name = "CategoryID";
				$this->CategorySelect->CssClass = "SmallInput";
				$this->CategorySelect->AddOption("", $this->Context->GetDefinition("Choose"));
				$this->CategorySelect->AddOptionsFromDataSet($this->Context->Database, $this->CategoryData, "CategoryID", "Name");
			}
			if (in_array($this->PostBackAction, array("Category", "ProcessCategory"))) {
				$this->CategoryRoles = $this->Context->ObjectFactory->NewObject($this->Context, "Checkbox");
				$this->CategoryRoles->Name = "CategoryRoleBlock";
				$CategoryRoleData = $this->CategoryManager->GetCategoryRoleBlocks($CategoryID);
				$this->CategoryRoles->AddOptionsFromDataSet($this->Context->Database, $CategoryRoleData, "RoleID", "Name", "Blocked", 1);
				$this->CategoryRoles->CssClass = "CheckBox";
			}
			if ($this->PostBackAction == "Category") {
				if ($CategoryID > 0) {
					$this->Category = $this->CategoryManager->GetCategoryById($CategoryID);
				} else {
					$this->Category = $this->Context->ObjectFactory->NewObject($this->Context, "Category");
				}
			}
			if (in_array($this->PostBackAction, array("ProcessCategory", "ProcessCategoryRemove"))) {
				// Show the form again with errors
				$this->PostBackAction = str_replace("Process", "", $this->PostBackAction);
			}
		}
      $this->CallDelegate("Constructor");
	}
	
	function Render() {
		if ($this->IsPostBack) {
         $this->CallDelegate("PreRender");
			$this->PostBackParams->Clear();
			$CategoryID = ForceIncomingInt("CategoryID", 0);
			
			if ($this->PostBackAction == "Category") {
				$this->PostBackParams->Set("PostBackAction", "ProcessCategory");
            $this->CallDelegate("PreEditRender");
            include($this->Context->Configuration["THEME_PATH"]."templates/settings_category_edit.php");
            $this->CallDelegate("PostEditRender");
				
			} elseif ($this->PostBackAction == "CategoryRemove") {
				$this->PostBackParams->Set("PostBackAction", "ProcessCategoryRemove");
				$this->CategorySelect->Attributes = "onchange=\"document.location='?PostBackAction=CategoryRemove&CategoryID='+this.options[this.selectedIndex].value;\"";
				$this->CategorySelect->SelectedID = $CategoryID;
            $this->CallDelegate("PreRemoveRender");
            include($this->Context->Configuration["THEME_PATH"]."templates/settings_category_remove.php");            
            $this->CallDelegate("PostRemoveRender");
            
			} else {
				$this->PostBackParams->Set("PostBackAction", "ProcessCategories");
            $this->CallDelegate("PreListRender");
            include($this->Context->Configuration["THEME_PATH"]."templates/settings_category_list.php");            
            $this->CallDelegate("PostListRender");
            
			}
         $this->CallDelegate("PostRender");
		}
	}
}
?>