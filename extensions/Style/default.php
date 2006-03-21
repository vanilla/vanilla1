<?php
/*
Extension Name: Custom Styles
Extension Url: http://lussumo.com/docs/
Description: Allows administrators to define and create multiple styles for Vanilla. Users can then change their style. This version of the style manager is only compatible with Vanilla 0.9.3 or greater.
Version: 2.0
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
$Context->Dictionary["ForumAppearance"] = "Forum Appearance";
$Context->Dictionary["SelectStyleToEdit"] = "1. Select the style you would like to edit";
$Context->Dictionary["ModifyStyleDefinition"] = "2. Modify the style definition";
$Context->Dictionary["DefineTheNewStyle"] = "Define the new style";
$Context->Dictionary["StyleName"] = "Style name";
$Context->Dictionary["StyleNameNotes"] = "The style name will be visible on the user's account modification page. Html is not allowed.";
$Context->Dictionary["StyleAuthor"] = "Style author";
$Context->Dictionary["StyleAuthorNotes"] = "The name of the author of this style. Enter the name exactly as it appears on the user's account.";
$Context->Dictionary["StyleUrl"] = "Style url";
$Context->Dictionary["StyleUrlNotes"] = "You can enter any valid URL to a web-based directory here, such as: <strong>http://www.mywebsite.com/mynewstyle/</strong>
	<br />The folder must contain all of the files relevant to styling the forum, such as: vanilla.css";
$Context->Dictionary["PreviewImageFilename"] = "Preview image filename";
$Context->Dictionary["PreviewImageFilenameNotes"] = "If there is a preview image in the style folder, enter the image name here. Preview images are automatically sized to 200 pixels high by 370 pixels wide.";
$Context->Dictionary["StyleManagement"] = "Style Management";
$Context->Dictionary["SelectStyleToRemove"] = "1. Select the style you would like to remove";
$Context->Dictionary["SelectAReplacementStyle"] = "2. Select a replacement style";
$Context->Dictionary["ReplacementStyleNotes"] = "When you remove a style from the system, any users using that style will not be able to view the site properly. The replacement style will be assigned to all users who are currently assigned to the style you are removing.";
$Context->Dictionary["CreateANewStyle"] = "Create a new style";
$Context->Dictionary["ChangeYourStylesheet"] = "Change Stylesheet";
$Context->Dictionary["ForumAppearanceNotes"] = "Change the way the forum appears by changing your style. Listed below are available styles. Alternately, you can specify your own style using the input at the bottom of the page.";
$Context->Dictionary["NoPreview"] = "No preview available";
$Context->Dictionary["CustomStyle"] = "Use your own, custom style";
$Context->Dictionary["CustomStyleUrl"] = "Custom style url";
$Context->Dictionary["CustomStyleNotes"] = "Any web-accessable folder will work, such as: http://www.mysite.com/mystyle/
	<br />Your custom style folder should contain all files relevant to your style, including a vanilla.css file.
	<br />For more information about how to style the forum, <a href=\"http://lussumo.com/docs\">read the documentation</a>.";
$Context->Dictionary["UseCustomStyle"] = "Click here to use your custom style";
$Context->Dictionary["By"] = "by";
$Context->Dictionary["XByY"] = "//1 by //2";
$Context->Dictionary["Styles"] = "Styles";
$Context->Dictionary["StyleNameLower"] = "style name";
$Context->Dictionary["StyleUrlLower"] = "style url";
$Context->Dictionary["ErrStyleNotFound"] = "The requested style could not be found.";
$Context->Dictionary["ErrStyleAuthor"] = "A user with the username you provided for \"Style author\" could not be found.";
$Context->Dictionary["System"] = "System";




// Load the javascript if we're on a page that should allow changing of the style
if (in_array($Context->SelfUrl, array("comments.php", "account.php"))) {
	$Head->AddScript("./extensions/Style/functions.js");
}

// Let it skip these classes if it doesn't need them
if (in_array($Context->SelfUrl, array("settings.php", "account.php"))) {
	class Style {
		var $StyleID;
		var $AuthUserID;
		var $AuthUsername;
		var $AuthFullName;
		var $Name;				// The name of the style itself
		var $Url;
		var $PreviewImage;
		var $Context;
		
		function Style(&$Context) {
			$this->Context = &$Context;
		}
		function Clear() {
			$this->StyleID = 0;
			$this->AuthUserID = 0;
			$this->AuthUsername = "";
			$this->AuthFullName = "";
			$this->Name = "";
			$this->Url = "";
			$this->PreviewImage = "";
		}
		
		function FormatPropertiesForDatabaseInput() {
			$this->Name = FormatStringForDatabaseInput($this->Name, 1);
			$this->Url = FormatStringForDatabaseInput($this->Url, 1);
			$this->PreviewImage = FormatStringForDatabaseInput($this->PreviewImage, 1);
		}
		
		function FormatPropertiesForDisplay() {
			$this->AuthUsername = FormatStringForDisplay($this->AuthUsername);
			$this->AuthFullName = FormatStringForDisplay($this->AuthFullName);
			$this->Name = FormatStringForDisplay($this->Name);
			$this->Url = FormatStringForDisplay($this->Url);
			$this->PreviewImage = FormatStringForDisplay($this->PreviewImage);
		}
		
		function GetPropertiesFromDataSet($DataSet) {
			$this->StyleID = ForceInt(@$DataSet["StyleID"],0);
			$this->AuthUserID = ForceInt(@$DataSet["AuthUserID"],0);
			if ($this->AuthUserID == 0) {
				$this->AuthUsername = $this->Context->GetDefinition("System");
				$this->AuthFullName = $this->Context->GetDefinition("System");
			} else {
				$this->AuthUsername = ForceString(@$DataSet["AuthUsername"],"");
				$this->AuthFullName = ForceString(@$DataSet["AuthFullName"],"");
			}
			$this->Name = ForceString(@$DataSet["Name"],"");
			$this->Url = ForceString(@$DataSet["Url"],"");
			$this->PreviewImage = ForceString(@$DataSet["PreviewImage"], "");
		}
		
		function GetPropertiesFromForm() {
			$this->StyleID = ForceIncomingInt("StyleID", 0);
			$this->AuthUserID = ForceIncomingInt("AuthUserID", 0);
			$this->AuthUsername = ForceIncomingString("AuthUsername", "");
			$this->Name = ForceIncomingString("Name", "");
			$this->Url = ForceIncomingString("Url", "");
			$this->PreviewImage = ForceIncomingString("PreviewImage", "");
		}
	}
	
	class StyleManager {
		var $Name;				// The name of this class
		var $Context;			// The context object that contains all global objects (database, error manager, warning collector, session, etc)
		
		// Returns a SqlBuilder object with all of the user properties already defined in the select
		function GetStyleBuilder() {
			$s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
			$s->SetMainTable("Style", "s");
			$s->AddJoin("User", "u", "UserID", "s", "AuthUserID", "left join");
			$s->AddSelect(array("StyleID", "AuthUserID", "Name", "Url", "PreviewImage"), "s");
			$s->AddSelect("Name", "u", "AuthUsername");
			$s->AddSelect("FirstName", "u", "AuthFullName", "concat", "' ',u.LastName");
			return $s;
		}
		
		function GetStyleById($StyleID) {
			$s = $this->GetStyleBuilder();
			$s->AddWhere('s', 'StyleID', '', $StyleID, "=");
	
			$Style = $this->Context->ObjectFactory->NewContextObject($this->Context, "Style");
			$result = $this->Context->Database->Select($s, $this->Name, "GetStyleById", "An error occurred while attempting to retrieve the requested style.");
			if ($this->Context->Database->RowCount($result) == 0) $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrStyleNotFound"));
			while ($rows = $this->Context->Database->GetRow($result)) {
				$Style->GetPropertiesFromDataSet($rows);
			}
			
			return $this->Context->WarningCollector->Iif($Style, false);
		}
		
		function GetStyleCount() {
			$TotalNumberOfRecords = 0;
			$s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
			$s->SetMainTable("Style", "s");
			$s->AddSelect("StyleID", "s", "Count", "count");
			
			$result = $this->Context->Database->Select($s, $this->Name, "GetStyleCount", "An error occurred while retrieving the style count.");
			while ($rows = $this->Context->Database->GetRow($result)) {
				$TotalNumberOfRecords = $rows['Count'];
			}
			return $TotalNumberOfRecords;
		}
		
		function GetStyleList($CurrentPage = "1", $RowsPerPage = "0") {
			$s = $this->GetStyleBuilder();
			$CurrentPage = ForceInt($CurrentPage, 1);
			if ($CurrentPage < 1) $CurrentPage == 1;
			$RowsPerPage = ForceInt($RowsPerPage, 0);
			$FirstRecord = ($CurrentPage * $RowsPerPage) - $RowsPerPage;
			$s->AddOrderBy("StyleID", "s", "asc");
			if ($RowsPerPage > 0) $s->Limit($FirstRecord, $RowsPerPage);
				
			return $this->Context->Database->Select($s, $this->Name, "GetDataList", "An error occurred while attempting to retrieve styles.");
		}
		
		// Returns the styles in a format more suitable for the select list
		function GetStylesForSelectList() {
			$s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
			$s->SetMainTable("Style", "s");
			$s->AddJoin("User", "u", "UserID", "s", "AuthUserID", "left join");
			$s->AddSelect("StyleID", "s");
			$s->AddSelect("Name", "s", "Name", "concat", "' ".$this->Context->GetDefinition("By")." ',coalesce(u.Name,'".$this->Context->GetDefinition("System")."')");
			$s->AddOrderBy("Name", "s", "asc");
			return $this->Context->Database->Select($s, $this->Name, "GetStylesForSelectList", "An error occurred while attempting to retrieve styles.");
		}
	
		function RemoveStyle($RemoveStyleID, $ReplacementStyleID) {
			// Reassign the user-chosen styles
			$s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
			$s->SetMainTable("User", "u");
			$s->AddFieldNameValue("StyleID", $ReplacementStyleID);
			$s->AddWhere('u', 'StyleID', '', $RemoveStyleID, "=");
			$this->Context->Database->Update($s, $this->Name, "RemoveStyle", "An error occurred while attempting to re-assign user styles.");
			// Now remove the style itself
			$s->Clear();
			$s->SetMainTable("Style", "s");
			$s->AddWhere('u', 'StyleID', '', $RemoveStyleID, "=");
			$this->Context->Database->Delete($s, $this->Name, "RemoveStyle", "An error occurred while attempting to remove the style.");
		}
		
		function SaveStyle(&$Style) {
			// Ensure that the person performing this action has access to do so
			if (!$this->Context->Session->User->Permission("PERMISSION_MANAGE_STYLES")) $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrPermissionInsufficient"));
			
			if ($this->Context->WarningCollector->Count() == 0) {
				// Retrieve the AuthUserID based on the supplied AuthUsername
				$um = $this->Context->ObjectFactory->NewContextObject($this->Context, "UserManager");
				$Style->AuthUserID = $um->GetUserIdByName($Style->AuthUsername);
				// Validate the properties
				if($this->ValidateStyle($Style)) {
					$s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
					$s->SetMainTable("Style", "s");
					$s->AddFieldNameValue("Name", $Style->Name);
					$s->AddFieldNameValue("AuthUserID", $Style->AuthUserID);
					$s->AddFieldNameValue("Url", $Style->Url);
					$s->AddFieldNameValue("PreviewImage", $Style->PreviewImage);
					if ($Style->StyleID > 0) {
						$s->AddWhere('s', 'StyleID', '', $Style->StyleID, "=");
						$this->Context->Database->Update($s, $this->Name, "SaveStyle", "An error occurred while attempting to update the style.");
					} else {
						$Style->StyleID = $this->Context->Database->Insert($s, $this->Name, "SaveStyle", "An error occurred while creating a new style.");
					}
				}
			}
			return $this->Context->WarningCollector->Iif($Style, false);
		}
		
		function StyleManager(&$Context) {
			$this->Name = "StyleManager";
			$this->Context = &$Context;
		}
		
		// Validates and formats properties ensuring they're safe for database input
		// Returns: boolean value indicating success
		function ValidateStyle(&$Style) {
			$ValidatedStyle = $Style;
			$ValidatedStyle->FormatPropertiesForDatabaseInput();
					
			Validate($this->Context->GetDefinition("StyleNameLower"), 1, $ValidatedStyle->Name, 50, "", $this->Context);
			Validate($this->Context->GetDefinition("StyleUrlLower"), 1, $ValidatedStyle->Url, 255, "", $this->Context);
			
			// If validation was successful, then reset the properties to db safe values for saving
			if ($this->Context->WarningCollector->Count() == 0) $Style = $ValidatedStyle;
			return $this->Context->WarningCollector->Iif();
		}
	}
}

// If looking at the settings page, include the styleform control and instantiate it
if (($Context->SelfUrl == "settings.php") && $Context->Session->User->Permission("PERMISSION_MANAGE_STYLES")) {
	class StyleForm extends PostBackControl {
		
		var $StyleManager;
		var $StyleData;
		var $StyleSelect;
		var $Style;
	
		function StyleForm(&$Context) {
			$this->ValidActions = array("Styles", "Style", "ProcessStyle", "StyleRemove", "ProcessStyleRemove");
			$this->Constructor($Context);
			if (!$this->Context->Session->User->Permission("PERMISSION_MANAGE_STYLES")) {
				$this->IsPostBack = 0;
			} elseif ($this->IsPostBack) {
				$StyleID = ForceIncomingInt("StyleID", 0);
				$ReplacementStyleID = ForceIncomingInt("ReplacementStyleID", 0);
				$this->StyleManager = $this->Context->ObjectFactory->NewContextObject($this->Context, "StyleManager");
				
				if ($this->PostBackAction == "ProcessStyle") {
					$this->Style = $this->Context->ObjectFactory->NewContextObject($this->Context, "Style");
					$this->Style->GetPropertiesFromForm($this->Context);
					if ($this->StyleManager->SaveStyle($this->Style)) {
						header("location: ".GetUrl($this->Context->Configuration, "settings.php", "", "", "", "", "PostBackAction=Styles"));
					}
				} elseif ($this->PostBackAction == "ProcessStyleRemove") {
					if ($this->StyleManager->RemoveStyle($StyleID, $ReplacementStyleID)) {
						header("location: ".GetUrl($this->Context->Configuration, "settings.php", "", "", "", "", "PostBackAction=Styles"));
					}
				}
				
				if (in_array($this->PostBackAction, array("StyleRemove", "Styles", "Style", "ProcessStyle", "ProcessStyleRemove"))) {
					$this->StyleData = $this->StyleManager->GetStylesForSelectList();
				}
				if (in_array($this->PostBackAction, array("StyleRemove", "Style", "ProcessStyleRemove"))) {
					$this->StyleSelect = $this->Context->ObjectFactory->NewObject($this->Context, "Select");
					$this->StyleSelect->Name = "StyleID";
					$this->StyleSelect->CssClass = "SmallInput";
					$this->StyleSelect->Attributes = ' id="ddStyleID"';
					if ($this->PostBackAction != "Style") $this->StyleSelect->AddOption("", "Choose...");
					$this->StyleSelect->AddOptionsFromDataSet($this->Context->Database, $this->StyleData, "StyleID", "Name");
				}
				if ($this->PostBackAction == "Style") {
					if ($StyleID > 0) {
						$this->Style = $this->StyleManager->GetStyleById($StyleID);
					} else {
						$this->Style = $this->Context->ObjectFactory->NewContextObject($this->Context, "Style");
					}
				}
				if (in_array($this->PostBackAction, array("ProcessStyle", "ProcessStyleRemove"))) {
					// Show the form again with errors
					$this->PostBackAction = str_replace("Process", "", $this->PostBackAction);
				}
			}
		}
		
		function Render() {
			if ($this->IsPostBack) {
				$this->PostBackParams->Clear();
				$StyleID = ForceIncomingInt("StyleID", 0);
				
				if ($this->PostBackAction == "Style") {
					$this->PostBackParams->Set("PostBackAction", "ProcessStyle");
					echo('<div id="Form" class="Account Style">
						<fieldset>
							<legend>'.$this->Context->GetDefinition("StyleManagement").'</legend>');
							if ($StyleID > 0) {
								$this->StyleSelect->Attributes = "onchange=\"document.location='?PostBackAction=Style&amp;StyleID='+this.options[this.selectedIndex].value;\"";
								$this->StyleSelect->SelectedID = $StyleID;
								echo $this->Get_Warnings().'
									'.$this->Get_PostBackForm("frmStyle").'
									<h2>'.$this->Context->GetDefinition("SelectStyleToEdit").'</h2>
									<ul>
										<li>
											<label for="ddStyleID">'.$this->Context->GetDefinition("Styles").' <small>'.$this->Context->GetDefinition("Required").'</small></label>
											'.$this->StyleSelect->Get().'
											<p class="Description">'.$this->Context->GetDefinition("ModifyStyleDefinition").'</p>
										</li>';
							} else {
								echo $this->Get_Warnings().'
									'.$this->Get_PostBackForm('frmStyle').'
									<h2>'.$this->Context->GetDefinition('DefineTheNewStyle').'</h2>
									<ul>';
							}
								echo '<li>
									<label for="txtStyleName">'.$this->Context->GetDefinition('StyleName').' <small>'.$this->Context->GetDefinition("Required").'</small></label>
									<input type="text" name="Name" value="'.$this->Style->Name.'" maxlength="40" class="SmallInput" id="txtStyleName" />
									<p class="Description">'.$this->Context->GetDefinition("StyleNameNotes").'</p>
								</li>
								<li>
									<label for="txtAuthUsername">'.$this->Context->GetDefinition("StyleAuthor").'</label>
									<input id="txtAuthUsername" name="AuthUsername" type="text" value="'.FormatStringForDisplay(($this->Style->AuthUserID == 0 ? '' : $this->Style->AuthUsername), 0).'" class="WhisperBox" maxlength="20" />
									<script type="text/javascript">
										var WhisperAutoComplete = AutoComplete("txtAuthUsername", false);
										WhisperAutoComplete.KeywordSourceUrl = "../ajax/getusers.php";
									</script>
									<p class="Description">'.$this->Context->GetDefinition('StyleAuthorNotes').'</p>
								</li>
								<li>
									<label for="txtStyleUrl">'.$this->Context->GetDefinition('StyleUrl').' <small>'.$this->Context->GetDefinition("Required").'</small></label>
									<input id="txtUrl" type="text" name="Url" value="'.$this->Style->Url.'" maxlength="200" class="SmallInput" />
									<p class="Description">'.$this->Context->GetDefinition('StyleUrlNotes').'</p>
								</li>
								<li>
									<label for="txtStylePreviewImage">'.$this->Context->GetDefinition('PreviewImageFilename').'</label>
									<input type="text" name="PreviewImage" value="'.$this->Style->PreviewImage.'" maxlength="20" class="SmallInput" id="txtStylePreviewImage" />
									<p class="Description">'.$this->Context->GetDefinition('PreviewImageFilenameNotes').'</p>
								</li>
							</ul>
							<div class="Submit">
								<input type="submit" name="btnSave" value="'.$this->Context->GetDefinition('Save').'" class="Button SubmitButton" />
								<a href="'.GetUrl($this->Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=Styles').'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
							</div>
							</form>
						</fieldset>
					</div>';			
					
				} elseif ($this->PostBackAction == "StyleRemove") {
					$this->PostBackParams->Set("PostBackAction", "ProcessStyleRemove");
					$this->StyleSelect->Attributes = "onchange=\"document.location='".GetUrl($this->Context->Configuration, "index.php", "", "", "", "", "PostBackAction=StyleRemove&amp;StyleID='+this.options[this.selectedIndex].value").";\"";
					$this->StyleSelect->SelectedID = $StyleID;
					echo '<div id="Form" class="Account Identity">
						<fieldset>
							<legend>'.$this->Context->GetDefinition('StyleManagement').'</legend>
							'.$this->Get_Warnings().'
							'.$this->Get_PostBackForm('frmStyleRemove').'
							<h2>'.$this->Context->GetDefinition('SelectStyleToRemove').'</h2>
							<ul>
								<li>
									<label for="ddStyleID">'.$this->Context->GetDefinition('Styles').' <small>'.$this->Context->GetDefinition('Required').'</small></label>
									'.$this->StyleSelect->Get().'
								</li>
							</ul>';
								
							if ($StyleID > 0) {
								$this->StyleSelect->Attributes = "";
								$this->StyleSelect->RemoveOption($this->StyleSelect->SelectedID);
								$this->StyleSelect->Name = "ReplacementStyleID";
								$this->StyleSelect->SelectedID = ForceIncomingInt("ReplacementStyleID", 0);
								$this->StyleSelect->Attributes = ' id="ddReplacementStyleID"';
								echo '<h2>'.$this->Context->GetDefinition('SelectAReplacementStyle').'</h2>
								<ul>
									<li>
										<label for="ddReplacementStyleID">'.$this->Context->GetDefinition('ReplacementStyle').' <small>'.$this->Context->GetDefinition('Required').'</small></label>
										'.$this->StyleSelect->Get().'
										<p class="Description">'.$this->Context->GetDefinition("ReplacementStyleNotes").'</p>
									</li>
								</ul>
								<div class="Submit">
									<input type="submit" name="btnSave" value="'.$this->Context->GetDefinition('Remove').'" class="Button SubmitButton RemoveStyleButton" />
									<a href="'.GetUrl($this->Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=Styles').'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
								</div>';
							}
							echo '</form>
						</fieldset>
					</div>';				
				} else {
					echo '<div id="Form" class="Account SettingsForm">
						<fieldset>
							<legend>'.$this->Context->GetDefinition("StyleManagement").'</legend>'
							.$this->Get_Warnings().
							'<form method="get" action="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'">
							<input type="hidden" name="PostBackAction" value="Style" />

							<ul class="SortList" id="SortStyles">';
								if ($this->StyleData) {
									$s = $this->Context->ObjectFactory->NewContextObject($this->Context, "Style");
									
									while ($Row = $this->Context->Database->GetRow($this->StyleData)) {
										$s->Clear();
										$s->GetPropertiesFromDataSet($Row);
										$s->FormatPropertiesForDisplay();
										echo '<li class="SortListItem">
											<a class="SortRemove" href="'.GetUrl($this->Context->Configuration, "settings.php", "", "", "", "", "PostBackAction=StyleRemove&amp;StyleID=".$s->StyleID).'">&nbsp;</a>
											<a class="SortEdit" href="'.GetUrl($this->Context->Configuration, "settings.php", "", "", "", "", "PostBackAction=Style&amp;StyleID=".$s->StyleID).'">'.$this->Context->GetDefinition("Edit").'</a>
											'.$s->Name.'
										</li>';
									}
								}
							echo '</ul>
							<div class="Submit">
								<input type="submit" name="btnSave" value="'.$this->Context->GetDefinition('CreateANewStyle').'" class="Button SubmitButton NewStyleButton" />
								<a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
							</div>
							</form>
						</fieldset>
					</div>';
				}
			}
		}
	}
	
	$StyleForm = $Context->ObjectFactory->NewContextObject($Context, "StyleForm");
	
	$Page->AddRenderControl($StyleForm, $Configuration["CONTROL_POSITION_BODY_ITEM"] + 1);
	
	if ($Context->Session->User->Permission("PERMISSION_MANAGE_STYLES")) {
		$AdministrativeOptions = $Context->GetDefinition("AdministrativeOptions");
		$Panel->AddList($AdministrativeOptions, 10);
		$Panel->AddListItem($AdministrativeOptions, $Context->GetDefinition("StyleManagement"), GetUrl($Configuration, "settings.php", "", "", "", "", "PostBackAction=Styles"), "", "", 70);
	}
	// Add the stylesheet to the account screen
   $PostBackAction = ForceIncomingString("PostBackAction", "");
   if (in_array($PostBackAction, array("Styles", "Style", "ProcessStyle", "StyleRemove", "ProcessStyleRemove"))) $Head->AddStyleSheet("extensions/Style/style.css");
	
} elseif ($Context->SelfUrl == "account.php" && $Context->Session->UserID > 0) {
	$AccountUserID = ForceIncomingInt("u", $Context->Session->UserID);
   if ($AccountUserID == $Context->Session->UserID) {
		// If looking at the account page, include the styleuserform control and instantiate it
		class UserStyleForm extends PostBackControl {
			
			var $StyleManager;
			var $StyleData;
		
			function UserStyleForm(&$Context) {
				$this->ValidActions = array("Style");
				$this->Constructor($Context);
				if ($this->IsPostBack) {
					$this->StyleManager = $this->Context->ObjectFactory->NewContextObject($this->Context, "StyleManager");
					$this->StyleData = $this->StyleManager->GetStyleList();
				}
			}
			
			function Render() {
				if ($this->IsPostBack) {
					echo '<div id="Form" class="Account ChangeStyle">
						<fieldset>
							<legend>'.$this->Context->GetDefinition('ChangeYourStylesheet').'</legend>'
							.$this->Get_Warnings()
							.'<form id="frmCustomStyle" method="post" action="">
							<h2>'.$this->Context->GetDefinition('ForumAppearance').'</h2>
							<p>'.$this->Context->GetDefinition('ForumAppearanceNotes').'</p>
							<ul id="Previews">';
							
							$Style = $this->Context->ObjectFactory->NewContextObject($this->Context, "Style");
							while ($Row = $this->Context->Database->GetRow($this->StyleData)) {
								$Style->Clear();
								$Style->GetPropertiesFromDataSet($Row);
								$Style->FormatPropertiesForDisplay();
								echo '<li>';
									if ($Style->AuthUserID > 0) {
										echo(str_replace(array("//1", "//2"),
											array(
												"<a href=\"./\" onclick=\"SetStyle('".$Style->StyleID."', ''); return false;\"".($Style->PreviewImage == "" ? " class=\"NoStylePreview\"" : " class=\"StylePreview\" style=\"background: url('".$Style->Url.$Style->PreviewImage."') center center no-repeat;\"")."><span>".$Style->Name."</span></a>
												",
												"<a href=\"".GetUrl($this->Context->Configuration, "account.php", "", "u", $Style->AuthUserID)."\">".$Style->AuthUsername."</a>
												"
											),
											$this->Context->GetDefinition("XByY")));
									} else {
										echo "<a href=\"./\" onclick=\"SetStyle('".$Style->StyleID."', ''); return false;\"".($Style->PreviewImage == "" ? " class=\"NoStylePreview\"" : " class=\"StylePreview\" style=\"background: url('".$Style->Url.$Style->PreviewImage."') center center no-repeat;\"")."><span>".$Style->Name."</span></a>
										";
									}
								echo('</li>');
							}					
							echo '</ul>
							<h2>'.$this->Context->GetDefinition('CustomStyle').'</h2>
							<ul>
								<li>
									<label for="txtCustomStyle">'.$this->Context->GetDefinition('CustomStyleUrl').'</label>
									<input type="text" name="CustomStyle" value="'.$this->Context->Session->User->CustomStyle.'" maxlength="200" class="CustomStyleInput" id="txtCustomStyle" />
									<p class="Description">
										'.$this->Context->GetDefinition('CustomStyleNotes').'
									</p>
								</li>
							</ul>
							<div class="Submit">
								<input type="button" name="btnCheck" value="'.$this->Context->GetDefinition('UseCustomStyle').'" class="Button SubmitButton CustomStyleButton" onclick="'."SetStyle('0', document.getElementById('txtCustomStyle').value); return false;".'" />
								<a href="'.GetUrl($this->Context->Configuration, 'account.php').'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
							</div>
							</form>
						</fieldset>
					</div>';
				}
			}
		}
			
		$UserStyleForm = $Context->ObjectFactory->NewContextObject($Context, "UserStyleForm");
		$AccountOptions = $Context->GetDefinition("AccountOptions");
		$Panel->AddList($AccountOptions, 10);
		$Page->AddRenderControl($UserStyleForm, $Configuration["CONTROL_POSITION_BODY_ITEM"] + 1);
		$Panel->AddListItem($AccountOptions, $Context->GetDefinition("ChangeYourStylesheet"), GetUrl($this->Context->Configuration, "account.php", "", "", "", "", "PostBackAction=Style"), "", "", 50);
	}
	// Include the style definition on the user's profile & the account profile is being display
	$PostBackAction = ForceIncomingString("PostBackAction", "");
	if ($PostBackAction == "") {
		function AddStylePropertyToAccount(&$AccountControl, &$FunctionParameters) {
			echo("<dt>".$AccountControl->Context->GetDefinition("Style")."</dt>
			<dd>");
			if ($AccountControl->Context->Session->UserID > 0 && $AccountControl->Context->Session->User->StyleID != $AccountControl->User->StyleID && $AccountControl->Context->Session->UserID != $AccountControl->User->UserID) {
				echo("<a onclick=\"SetStyle('".$AccountControl->User->StyleID."', '".($AccountControl->User->StyleID == 0?urlencode($AccountControl->User->CustomStyle):"")."');\">".$AccountControl->User->Style."</a>");
			} else {
				echo($AccountControl->User->Style);
			}
			echo("</dd>");
		}
		$Context->AddToDelegate("Account", "AccountProperties", "AddStylePropertyToAccount");
	}
	// Add the stylesheet to the account screen
   if (in_array($PostBackAction, array("Styles", "Style", "ProcessStyle", "StyleRemove", "ProcessStyleRemove"))) $Head->AddStyleSheet("extensions/Style/style.css");
}
?>