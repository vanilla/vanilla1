<?php
// Note: This file is included from the library/Vanilla.Control.GlobalsForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("GlobalApplicationSettings")."</h1>
   <div class=\"Form GlobalsForm\">
      ".$this->Get_Warnings()."
      ".$this->Get_PostBackForm("frmApplicationGlobals")."
      <h2>".$this->Context->GetDefinition("Warning")."</h2>
      <div class=\"InputNote\">
         ".$this->Context->GetDefinition("GlobalApplicationSettingsNotes")."
      </div>


      <h2>".$this->Context->GetDefinition("ApplicationTitles")."</h2>
      <dl>
         <dt>".$this->Context->GetDefinition("ApplicationTitle")."</dt>
         <dd><input type=\"text\" name=\"APPLICATION_TITLE\" value=\"".$this->ConfigurationManager->GetSetting("APPLICATION_TITLE")."\" maxlength=\"50\" class=\"SmallInput\" /></dd>
      </dl>
      <dl>
         <dt>".$this->Context->GetDefinition("BannerTitle")."</dt>
         <dd><input type=\"text\" name=\"BANNER_TITLE\" value=\"".$this->ConfigurationManager->GetSetting("BANNER_TITLE")."\" class=\"SmallInput\" /></dd>
      </dl>
      <div class=\"InputNote\">".$this->Context->GetDefinition("ApplicationTitlesNotes")."</div>

      <h2>".$this->Context->GetDefinition("ForumOptions")."</h2>
      <div class=\"InputBlock\">
         <div class=\"CheckBox\">".GetDynamicCheckBox("ENABLE_WHISPERS", 1, $this->ConfigurationManager->GetSetting("ENABLE_WHISPERS"), "", $this->Context->GetDefinition("EnableWhispers"))."</div>
         <div class=\"CheckBox\">".GetDynamicCheckBox("ALLOW_NAME_CHANGE", 1, $this->ConfigurationManager->GetSetting("ALLOW_NAME_CHANGE"), "", $this->Context->GetDefinition("AllowNameChange"))."</div>
         <div class=\"CheckBox\">".GetDynamicCheckBox("PUBLIC_BROWSING", 1, $this->ConfigurationManager->GetSetting("PUBLIC_BROWSING"), "", $this->Context->GetDefinition("AllowPublicBrowsing"))."</div>
         <div class=\"CheckBox\">".GetDynamicCheckBox("USE_CATEGORIES", 1, $this->ConfigurationManager->GetSetting("USE_CATEGORIES"), "", $this->Context->GetDefinition("UseCategories"))."</div>
      </div>
      
      <h2>".$this->Context->GetDefinition("CountsTitle")."</h2>
      <dl>
         <dt>".$this->Context->GetDefinition("DiscussionsPerPage")."</dt>
         <dd>");
         $Selector = $this->Context->ObjectFactory->NewObject($this->Context, "Select");
         $Selector->Name = "DISCUSSIONS_PER_PAGE";
         $i = 10;
         while ($i < 101) {
            $Selector->AddOption($i, $i);
            $i += 10;
         }
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("DISCUSSIONS_PER_PAGE");
         echo($Selector->Get()."</dd>
         <dt>".$this->Context->GetDefinition("CommentsPerPage")."</dt>
         <dd>");
         $Selector->Name = "COMMENTS_PER_PAGE";
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("COMMENTS_PER_PAGE");
         echo($Selector->Get()."</dd>
         <dt>".$this->Context->GetDefinition("SearchResultsPerPage")."</dt>
         <dd>");
         $Selector->Name = "SEARCH_RESULTS_PER_PAGE";
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("SEARCH_RESULTS_PER_PAGE");
         echo($Selector->Get()."</dd>
         <dt>".$this->Context->GetDefinition("MaxBookmarksInPanel")."</dt>
         <dd>");
         $Selector->Clear();
         $Selector->Name = "PANEL_BOOKMARK_COUNT";
         for ($i = 3; $i < 11; $i++) {
            $Selector->AddOption($i, $i);
         }
         for ($i = 15; $i < 51; $i++) {
            $Selector->AddOption($i, $i);
            $i += 4;
         }
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("PANEL_BOOKMARK_COUNT");
         echo($Selector->Get()."</dd>
         <dt>".$this->Context->GetDefinition("MaxPrivateInPanel")."</dt>
         <dd>");
         $Selector->Name = "PANEL_PRIVATE_COUNT";
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("PANEL_PRIVATE_COUNT");
         echo($Selector->Get()."</dd>
         <dt>".$this->Context->GetDefinition("MaxBrowsingHistoryInPanel")."</dt>
         <dd>");
         $Selector->Name = "PANEL_HISTORY_COUNT";
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("PANEL_HISTORY_COUNT");
         echo($Selector->Get()."</dd>
         <dt>".$this->Context->GetDefinition("MaxDiscussionsInPanel")."</dt>
         <dd>");
         $Selector->Name = "PANEL_USERDISCUSSIONS_COUNT";
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("PANEL_USERDISCUSSIONS_COUNT");
         echo($Selector->Get()."</dd>
         <dt>".$this->Context->GetDefinition("MaxSavedSearchesInPanel")."</dt>
         <dd>");
         $Selector->Name = "PANEL_SEARCH_COUNT";
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("PANEL_SEARCH_COUNT");
         echo($Selector->Get()."</dd>
      </dl>
      <div class=\"InputNote\">".$this->Context->GetDefinition("CountsNotes")."</div>

      <h2>".$this->Context->GetDefinition("SpamProtectionTitle")."</h2>
      <dl>
         <dt>".$this->Context->GetDefinition("MaxCommentLength")."</dt>
         <dd><input type=\"text\" name=\"MAX_COMMENT_LENGTH\" value=\"".$this->ConfigurationManager->GetSetting("MAX_COMMENT_LENGTH")."\" maxlength=\"255\" class=\"SmallInput\" /></dd>
      </dl>
      <div class=\"InputNote\">
         ".$this->Context->GetDefinition("MaxCommentLengthNotes"));
         $Selector->Clear();
         $Selector->CssClass = "InlineSelect";
         for ($i = 1; $i < 31; $i++) {
            $Selector->AddOption($i, $i);
         }
         $Selector->Name = "DISCUSSION_POST_THRESHOLD";
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("DISCUSSION_POST_THRESHOLD");
         
         $SecondsSelector = $this->Context->ObjectFactory->NewObject($this->Context, "Select");
         $SecondsSelector->CssClass = "InlineSelect";
         for ($i = 10; $i < 601; $i++) {
            $SecondsSelector->AddOption($i, $i);
            $i += 9;							
         }
         $SecondsSelector->Name = "DISCUSSION_TIME_THRESHOLD";
         $SecondsSelector->SelectedID = $this->ConfigurationManager->GetSetting("DISCUSSION_TIME_THRESHOLD");
         $SecondsSelector2 = $SecondsSelector;
         $SecondsSelector2->Name = "DISCUSSION_THRESHOLD_PUNISHMENT";
         $SecondsSelector2->SelectedID = $this->ConfigurationManager->GetSetting("DISCUSSION_THRESHOLD_PUNISHMENT");
         
         echo(str_replace(array("//1", "//2", "//3"),
            array($Selector->Get(), $SecondsSelector->Get(), $SecondsSelector2->Get()),
            $this->Context->GetDefinition("XDiscussionsYSecondsZFreeze")));
            
         $Selector->Name = "COMMENT_POST_THRESHOLD";
         $Selector->SelectedID = $this->ConfigurationManager->GetSetting("COMMENT_POST_THRESHOLD");
         
         $SecondsSelector->Name = "COMMENT_TIME_THRESHOLD";
         $SecondsSelector->SelectedID = $this->ConfigurationManager->GetSetting("COMMENT_TIME_THRESHOLD");
         
         $SecondsSelector2->Name = "COMMENT_THRESHOLD_PUNISHMENT";
         $SecondsSelector2->SelectedID = $this->ConfigurationManager->GetSetting("COMMENT_THRESHOLD_PUNISHMENT");
         
         echo(str_replace(array("//1", "//2", "//3"),
            array($Selector->Get(), $SecondsSelector->Get(), $SecondsSelector2->Get()),
            $this->Context->GetDefinition("XDiscussionsYSecondsZFreeze"))
            ."<div class=\"CheckBox\">".GetDynamicCheckBox("LOG_ALL_IPS", 1, $this->ConfigurationManager->GetSetting("LOG_ALL_IPS"), "", $this->Context->GetDefinition("LogAllIps"))."</div>
      </div>
      
      
      <h2>".$this->Context->GetDefinition("SupportContactTitle")."</h2>
      <dl>
         <dt>".$this->Context->GetDefinition("SupportName")."</dt>
         <dd><input type=\"text\" name=\"SUPPORT_NAME\" value=\"".$this->ConfigurationManager->GetSetting("SUPPORT_NAME")."\" maxlength=\"255\" class=\"SmallInput\" /></dd>
      </dl>
      <dl>
         <dt>".$this->Context->GetDefinition("SupportEmail")."</dt>
         <dd><input type=\"text\" name=\"SUPPORT_EMAIL\" value=\"".$this->ConfigurationManager->GetSetting("SUPPORT_EMAIL")."\" maxlength=\"255\" class=\"SmallInput\" /></dd>
      </dl>
      <div class=\"InputNote\">".$this->Context->GetDefinition("SupportContactNotes")."</div>
      
      
      
      <h2>".$this->Context->GetDefinition("DiscussionLabelsTitle")."</h2>
      <dl>
         <dt>".$this->Context->GetDefinition("LabelPrefix")."</dt>
         <dd><input type=\"text\" name=\"TEXT_PREFIX\" value=\"".$this->ConfigurationManager->GetSetting("TEXT_PREFIX")."\" maxlength=\"20\" class=\"SmallInput\" /></dd>
         <dt>".$this->Context->GetDefinition("LabelSuffix")."</dt>
         <dd><input type=\"text\" name=\"TEXT_SUFFIX\" value=\"".$this->ConfigurationManager->GetSetting("TEXT_SUFFIX")."\" maxlength=\"20\" class=\"SmallInput\" /></dd>
         <dt>".$this->Context->GetDefinition("WhisperLabel")."</dt>
         <dd><input type=\"text\" name=\"TEXT_WHISPERED\" value=\"".$this->ConfigurationManager->GetSetting("TEXT_WHISPERED")."\" maxlength=\"30\" class=\"SmallInput\" /></dd>
         <dt>".$this->Context->GetDefinition("StickyLabel")."</dt>
         <dd><input type=\"text\" name=\"TEXT_STICKY\" value=\"".$this->ConfigurationManager->GetSetting("TEXT_STICKY")."\" maxlength=\"30\" class=\"SmallInput\" /></dd>
         <dt>".$this->Context->GetDefinition("ClosedLabel")."</dt>
         <dd><input type=\"text\" name=\"TEXT_CLOSED\" value=\"".$this->ConfigurationManager->GetSetting("TEXT_CLOSED")."\" maxlength=\"30\" class=\"SmallInput\" /></dd>
         <dt>".$this->Context->GetDefinition("HiddenLabel")."</dt>
         <dd><input type=\"text\" name=\"TEXT_HIDDEN\" value=\"".$this->ConfigurationManager->GetSetting("TEXT_HIDDEN")."\" maxlength=\"30\" class=\"SmallInput\" /></dd>
         <dt>".$this->Context->GetDefinition("BookmarkedLabel")."</dt>
         <dd><input type=\"text\" name=\"TEXT_BOOKMARKED\" value=\"".$this->ConfigurationManager->GetSetting("TEXT_BOOKMARKED")."\" maxlength=\"30\" class=\"SmallInput\" /></dd>
      </dl>
      <div class=\"InputNote\">".$this->Context->GetDefinition("DiscussionLabelsNotes")."</div>
      
      
      <h2>".$this->Context->GetDefinition("ApplicationSettings")."</h2>
      <dl>
         <dt>".$this->Context->GetDefinition("DefaultStyleFolder")."</dt>
         <dd><input type=\"text\" name=\"DEFAULT_STYLE\" value=\"".$this->ConfigurationManager->GetSetting("DEFAULT_STYLE")."\" maxlength=\"255\" class=\"SmallInput\" /></dd>
         <dt>".$this->Context->GetDefinition("WebPathToVanilla")."</dt>
         <dd><input type=\"text\" name=\"DOMAIN\" value=\"".$this->ConfigurationManager->GetSetting("DOMAIN")."\" maxlength=\"255\" class=\"SmallInput\" /></dd>
         <dt>".$this->Context->GetDefinition("CookieDomain")."</dt>
         <dd><input type=\"text\" name=\"COOKIE_DOMAIN\" value=\"".$this->ConfigurationManager->GetSetting("COOKIE_DOMAIN")."\" maxlength=\"255\" class=\"SmallInput\" /></dd>
         <dt>".$this->Context->GetDefinition("CookiePath")."</dt>
         <dd><input type=\"text\" name=\"COOKIE_PATH\" value=\"".$this->ConfigurationManager->GetSetting("COOKIE_PATH")."\" maxlength=\"255\" class=\"SmallInput\" /></dd>
      </dl>
      <div class=\"InputNote\">".$this->Context->GetDefinition("ApplicationSettingsNotes")."</div>
      <div class=\"FormButtons\">
         <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Save")."\" class=\"Button SubmitButton\" />
         <a href=\"./".$this->Context->SelfUrl."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
      </div>
      </form>
   </div>
</div>");
?>