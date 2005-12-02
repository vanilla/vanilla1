<?php
/*
Extension Name: Saved Searches
Extension Url: http://lussumo.com/docs/
Description: Allows users to save searches and display them in the control panel.
Version: 2.0
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


// Write the saved searches to the screen
if (
		in_array($Context->SelfUrl, array("index.php", "search.php", "comments.php"))
		&& $Context->Session->UserID > 0
		&& $Context->Session->User->Preference("ShowSavedSearches")
	) {

	$AllowEdit = $Context->SelfUrl == "search.php";
   $SearchManager = $Context->ObjectFactory->NewContextObject($Context, "SearchManager");
   $Data = $SearchManager->GetSearchList($Configuration["PANEL_SEARCH_COUNT"], $Context->Session->UserID);
	$SearchCount = 0;
	$String = "<h2>".$Context->GetDefinition("Searches")."</h2>";
	if ($Data) $SearchCount = $Context->Database->RowCount($Data);
	if ($SearchCount > 0) {
		if ($AllowEdit) $String .= "<input type=\"hidden\" id=\"SavedSearchCount\" value=\"".$SearchCount."\" />";

		if ($SearchCount > 0) {
			$String .= "<ul class=\"LinkedList \">";
				$s = $Context->ObjectFactory->NewObject($Context, "Search");
				while ($Row = $Context->Database->GetRow($Data)) {
					$s->Clear();
					$s->GetPropertiesFromDataSet($Row);
					$s->FormatPropertiesForDisplay();
					$String .= "<li id=\"SavedSearch_".$s->SearchID."\"><a class=\"PanelLink\" href=\"search.php?SearchID=".$s->SearchID."\">".$s->Label."</a>";
					if ($AllowEdit) $String .= " (<a href=\"javascript:RemoveSearch(".$s->SearchID.");\">".$Context->GetDefinition("RemoveLower")."</a>)";
					$String .= "</li>";
				}
			$String .= "</ul>";
		}
	}
	$String .= "<p id=\"SearchSavingHelp\" ".(($SearchCount > 0) ? "style=\"display: none;\"":"").">".$Context->GetDefinition("NoSavedSearches")."</p>";
	$Panel->AddString($String, 20);
}

if ($Context->SelfUrl == "search.php") {
	// Add the savedsearch js to the page
   $Head->AddScript("./extensions/SavedSearches/functions.js");
	
	// Write the "save your search" form to the screen
	function SearchForm_WriteSaveSearchForm(&$SearchForm) {
		// Set up the "save search" form
		$SearchForm->PostBackParams->Clear();
		$SearchForm->PostBackParams->Add("Type", $SearchForm->Search->Type);
		$SearchForm->PostBackParams->Add("Keywords", $SearchForm->Search->Keywords, 0);
		$SearchForm->PostBackParams->Add("SearchID", $SearchForm->Search->SearchID);
		$SearchForm->PostBackParams->Add("PostBackAction", "SaveSearch");
		echo("<div class=\"SearchLabelForm\">");

		if ($SearchForm->Context->Session->UserID > 0) {
			$SearchForm->Render_PostBackForm("frmLabelSearch", "post");
			echo("<input type=\"text\" name=\"Label\" class=\"SearchLabelInput\" value=\"".$SearchForm->Search->Label."\" maxlength=\"30\" />
				<input type=\"submit\" name=\"btnLabel\" value=\"".$SearchForm->Context->GetDefinition("SaveSearch")."\" class=\"SearchLabelButton\" />
				</form>");
		} else {
			echo("&nbsp;");
		}
		echo("</div>");
	}
	$Context->AddToDelegate("SearchForm",
      "PreSearchResultsRender",
      "SearchForm_WriteSaveSearchForm");
		
	// Make sure that the search is saved on postback
   function SearchForm_SaveSearchLabel(&$SearchForm) {
      // Handle saving
      if ($SearchForm->PostBackAction == "SaveSearch") {
			$SearchManager = $SearchForm->Context->ObjectFactory->NewContextObject($SearchForm->Context, "SearchManager");
         $SearchManager->SaveSearch($SearchForm->Search);
         // $SearchForm->PostBackAction = "Search";
			
			// Post back to the page again so that the new search is loaded in the panel
         header("location: search.php?SearchID=".$SearchForm->Search->SearchID);
			die();
      }
	}
	$Context->AddToDelegate("SearchForm",
      "PreSearchQuery",
      "SearchForm_SaveSearchLabel");
	
	
	// Load any clicked saved searches   
	function SearchForm_LoadSavedSearch(&$SearchForm) {
      if ($SearchForm->SearchID > 0 && $SearchForm->PostBackAction != "SaveSearch") {
			$SearchForm->Search->Clear();
			$SearchManager = $SearchForm->Context->ObjectFactory->NewContextObject($SearchForm->Context, "SearchManager");
         $SearchForm->Search = $SearchManager->GetSearchById($SearchForm->SearchID);
         if (!$SearchForm->Search) {
            $SearchForm->Search = $SearchForm->Context->ObjectFactory->NewObject($SearchForm->Context, "Search");
         } else {
            $SearchForm->PostBackAction = "Search";
         }
      }
	}
	$Context->AddToDelegate("SearchForm",
      "PostDefineSearchFromForm",
      "SearchForm_LoadSavedSearch");	
}

// Add the switch to the preferences form
if ($Context->SelfUrl == "account.php" && ForceIncomingString("PostBackAction", "") == "Functionality") {
	function PreferencesForm_AddSavedSearchSwitch(&$PreferencesForm) {
		$PreferencesForm->AddPreference("ControlPanel", "DisplaySavedSearches", "ShowSavedSearches");
	}
	$Context->AddToDelegate("PreferencesForm",
		"Constructor",
		"PreferencesForm_AddSavedSearchSwitch");
}
?>