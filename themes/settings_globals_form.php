<?php
// Note: This file is included from the library/Vanilla/Vanilla.Control.GlobalsForm.php control.
echo '<div id="Form" class="Account GlobalsForm">';
   if (ForceIncomingBool('Success',0)) echo '<div id="Success">'.$this->Context->GetDefinition('GlobalApplicationChangesSaved').'</div>';
   echo '<fieldset>
      <legend>'.$this->Context->GetDefinition('GlobalApplicationSettings').'</legend>
      '.$this->Get_Warnings().'
      '.$this->Get_PostBackForm('frmApplicationGlobals').'
      <h2>'.$this->Context->GetDefinition('Warning').'</h2>
      <p>
         '.$this->Context->GetDefinition('GlobalApplicationSettingsNotes').'
      </p>
      
      <h2>'.$this->Context->GetDefinition('ApplicationTitles').'</h2>
      <ul>
         <li>
            <label for="txtApplicationTitle">'.$this->Context->GetDefinition('ApplicationTitle').'</label>
            <input type="text" name="APPLICATION_TITLE" value="'.$this->ConfigurationManager->GetSetting('APPLICATION_TITLE').'" maxlength="50" class="SmallInput" id="txtApplicationTitle" />
         </li>
         <li>
            <label for="txtBannerTitle">'.$this->Context->GetDefinition('BannerTitle').'</label>
            <input type="text" name="BANNER_TITLE" value="'.$this->ConfigurationManager->GetSetting('BANNER_TITLE').'" class="SmallInput" id="txtBannerTitle" />
            <p class="Description">'.$this->Context->GetDefinition('ApplicationTitlesNotes').'</p>
         </li>
      </ul>

      <h2>'.$this->Context->GetDefinition('ForumOptions').'</h2>
      <ul>
         <li id="ForumOptions">
            <p><span>'.GetDynamicCheckBox('ENABLE_WHISPERS', 1, $this->ConfigurationManager->GetSetting('ENABLE_WHISPERS'), '', $this->Context->GetDefinition('EnableWhispers')).'</span></p>
            <p><span>'.GetDynamicCheckBox('ALLOW_NAME_CHANGE', 1, $this->ConfigurationManager->GetSetting('ALLOW_NAME_CHANGE'), '', $this->Context->GetDefinition('AllowNameChange')).'</span></p>
            <p><span>'.GetDynamicCheckBox('PUBLIC_BROWSING', 1, $this->ConfigurationManager->GetSetting('PUBLIC_BROWSING'), '', $this->Context->GetDefinition('AllowPublicBrowsing')).'</span></p>
            <p><span>'.GetDynamicCheckBox('USE_CATEGORIES', 1, $this->ConfigurationManager->GetSetting('USE_CATEGORIES'), '', $this->Context->GetDefinition('UseCategories')).'</span></p>
            <p><span>'.GetDynamicCheckBox('LOG_ALL_IPS', 1, $this->ConfigurationManager->GetSetting('LOG_ALL_IPS'), '', $this->Context->GetDefinition('LogAllIps')).'</span></p>
         </li>
      </ul>
      
      <h2>'.$this->Context->GetDefinition('CountsTitle').'</h2>
      <ul>
         <li>
            <label for="ddDiscussionsPerPage">'.$this->Context->GetDefinition('DiscussionsPerPage').'</label>
            ';
            $Selector = $this->Context->ObjectFactory->NewObject($this->Context, 'Select');
            $Selector->CssClass = 'SmallSelect';
            $Selector->Name = 'DISCUSSIONS_PER_PAGE';
            $Selector->Attributes = ' id="ddDiscussionsPerPage"';
            $i = 10;
            while ($i < 101) {
               $Selector->AddOption($i, $i);
               $i += 10;
            }
            $Selector->SelectedValue = $this->ConfigurationManager->GetSetting('DISCUSSIONS_PER_PAGE');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddCommentsPerPage">'.$this->Context->GetDefinition('CommentsPerPage').'</label>
            ';
            $Selector->Name = 'COMMENTS_PER_PAGE';
            $Selector->Attributes = ' id="ddCommentsPerPage"';
            $Selector->SelectedValue = $this->ConfigurationManager->GetSetting('COMMENTS_PER_PAGE');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddSearchResultsPerPage">'.$this->Context->GetDefinition('SearchResultsPerPage').'</label>
            ';
            $Selector->Name = 'SEARCH_RESULTS_PER_PAGE';
            $Selector->Attributes = ' id="ddSearchResultsPerPage"';
            $Selector->SelectedValue = $this->ConfigurationManager->GetSetting('SEARCH_RESULTS_PER_PAGE');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddMaxBookmarksInPanel">'.$this->Context->GetDefinition('MaxBookmarksInPanel').'</label>
            ';
            $Selector->Clear();
            $Selector->CssClass = 'SmallSelect';
            $Selector->Attributes = ' id="ddMaxBookmarksInPanel"';
            $Selector->Name = 'PANEL_BOOKMARK_COUNT';
            for ($i = 3; $i < 11; $i++) {
               $Selector->AddOption($i, $i);
            }
            for ($i = 15; $i < 51; $i++) {
               $Selector->AddOption($i, $i);
               $i += 4;
            }
            $Selector->SelectedValue = $this->ConfigurationManager->GetSetting('PANEL_BOOKMARK_COUNT');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddMaxPrivateInPanel">'.$this->Context->GetDefinition('MaxPrivateInPanel').'</label>
            ';
            $Selector->Name = 'PANEL_PRIVATE_COUNT';
            $Selector->Attributes = ' id="ddMaxPrivateInPanel"';
            $Selector->SelectedValue = $this->ConfigurationManager->GetSetting('PANEL_PRIVATE_COUNT');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddMaxBrowsingHistoryInPanel">'.$this->Context->GetDefinition('MaxBrowsingHistoryInPanel').'</label>
            ';
            $Selector->Name = 'PANEL_HISTORY_COUNT';
            $Selector->Attributes = ' id="ddMaxBrowsingHistoryInPanel"';
            $Selector->SelectedValue = $this->ConfigurationManager->GetSetting('PANEL_HISTORY_COUNT');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddMaxDiscussionsInPanel">'.$this->Context->GetDefinition('MaxDiscussionsInPanel').'</label>
            ';
            $Selector->Name = 'PANEL_USER_DISCUSSIONS_COUNT';
            $Selector->Attributes = ' id="ddMaxDiscussionsInPanel"';
            $Selector->SelectedValue = $this->ConfigurationManager->GetSetting('PANEL_USER_DISCUSSIONS_COUNT');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddMaxSavedSearchesInPanel">'.$this->Context->GetDefinition('MaxSavedSearchesInPanel').'</label>
            ';
            $Selector->Name = 'PANEL_SEARCH_COUNT';
            $Selector->Attributes = ' id="ddMaxSavedSearchesInPanel"';
            $Selector->SelectedValue = $this->ConfigurationManager->GetSetting('PANEL_SEARCH_COUNT');
            echo $Selector->Get().'
            <p class="Description">'.$this->Context->GetDefinition('CountsNotes').'</p>
         </li>
      </ul>

      <h2>'.$this->Context->GetDefinition('SpamProtectionTitle').'</h2>
      <ul>
         <li>
            <label for="txtMaxCommentLength">'.$this->Context->GetDefinition('MaxCommentLength').'</label>
            <input type="text" name="MAX_COMMENT_LENGTH" value="'.$this->ConfigurationManager->GetSetting('MAX_COMMENT_LENGTH').'" maxlength="255" class="SmallInput" id="txtMaxCommentLength" />
            <p class="Description">
               '.$this->Context->GetDefinition('MaxCommentLengthNotes');
               $Selector->Clear();
               $Selector->CssClass = 'SmallSelect';
               for ($i = 1; $i < 31; $i++) {
                  $Selector->AddOption($i, $i);
               }
               $Selector->Name = 'DISCUSSION_POST_THRESHOLD';
               $Selector->SelectedValue = $this->Context->Configuration['DISCUSSION_POST_THRESHOLD'];
               
               $SecondsSelector = $this->Context->ObjectFactory->NewObject($this->Context, 'Select');
               $SecondsSelector2 = $this->Context->ObjectFactory->NewObject($this->Context, 'Select');
               $SecondsSelector->CssClass = 'SmallSelect';
               $SecondsSelector2->CssClass = 'SmallSelect';
               for ($i = 10; $i < 601; $i++) {
                  $SecondsSelector->AddOption($i, $i);
                  $SecondsSelector2->AddOption($i, $i);
                  $i += 9;							
               }
               $SecondsSelector->Name = 'DISCUSSION_TIME_THRESHOLD';
               $SecondsSelector->SelectedValue = $this->Context->Configuration['DISCUSSION_TIME_THRESHOLD'];
               $SecondsSelector2->Name = 'DISCUSSION_THRESHOLD_PUNISHMENT';
               $SecondsSelector2->SelectedValue = $this->Context->Configuration['DISCUSSION_THRESHOLD_PUNISHMENT'];
               
               echo '<br /><br />'.str_replace(array('//1', '//2', '//3'),
                  array($Selector->Get(), $SecondsSelector->Get(), $SecondsSelector2->Get()),
                  $this->Context->GetDefinition('XDiscussionsYSecondsZFreeze'));
                  
               $Selector->Name = 'COMMENT_POST_THRESHOLD';
               $Selector->SelectedValue = $this->Context->Configuration['COMMENT_POST_THRESHOLD'];
               
               $SecondsSelector->Name = 'COMMENT_TIME_THRESHOLD';
               $SecondsSelector->SelectedValue = $this->Context->Configuration['COMMENT_TIME_THRESHOLD'];
               
               $SecondsSelector2->Name = 'COMMENT_THRESHOLD_PUNISHMENT';
               $SecondsSelector2->SelectedValue = $this->Context->Configuration['COMMENT_THRESHOLD_PUNISHMENT'];
               
               echo '<br /><br />'
                  .str_replace(array('//1', '//2', '//3'),
                     array($Selector->Get(), $SecondsSelector->Get(), $SecondsSelector2->Get()),
                     $this->Context->GetDefinition('XCommentsYSecondsZFreeze'))
               .'</p>
         </li>
      </ul>
      <h2>'.$this->Context->GetDefinition('EmailSettings').'</h2>
      <ul>
         <li>
            <label for="txtSupportName">'.$this->Context->GetDefinition('SupportName').'</label>
            <input type="text" name="SUPPORT_NAME" value="'.$this->ConfigurationManager->GetSetting('SUPPORT_NAME').'" maxlength="255" class="SmallInput" id="txtSupportName" />
         </li>
         <li>
            <label for="txtSupportEmail">'.$this->Context->GetDefinition('SupportEmail').'</label>
            <input type="text" name="SUPPORT_EMAIL" value="'.$this->ConfigurationManager->GetSetting('SUPPORT_EMAIL').'" maxlength="255" class="SmallInput" id="txtSupportEmail" />
            <p class="Description">'.$this->Context->GetDefinition('SupportContactNotes').'</p>
         </li>
         <li>
            <label for="txtSMTPHost">'.$this->Context->GetDefinition('SMTPHost').'</label>
            <input type="text" name="SMTP_HOST" value="'.$this->ConfigurationManager->GetSetting('SMTP_HOST').'" maxlength="255" class="SmallInput" id="txtSMTPHost" />
         </li>
         <li>
            <label for="txtSMTPUser">'.$this->Context->GetDefinition('SMTPUser').'</label>
            <input type="text" name="SMTP_USER" value="'.$this->ConfigurationManager->GetSetting('SMTP_USER').'" maxlength="255" class="SmallInput" id="txtSMTPUser" />
         </li>
         <li>
            <label for="txtSMTPPassword">'.$this->Context->GetDefinition('SMTPPassword').'</label>
            <input type="password" name="SMTP_PASSWORD" value="'.$this->ConfigurationManager->GetSetting('SMTP_PASSWORD').'" maxlength="255" class="SmallInput" id="txtSMTPPassword" />
            <p class="Description">'.$this->Context->GetDefinition('SMTPSettingsNotes').'</p>
         </li>
      </ul>
      
      <h2>'.$this->Context->GetDefinition('DiscussionLabelsTitle').'</h2>
      <ul>
         <li>
            <label for="txtLabelPrefix">'.$this->Context->GetDefinition('LabelPrefix').'</label>
            <input type="text" name="TEXT_PREFIX" value="'.$this->ConfigurationManager->GetSetting('TEXT_PREFIX').'" maxlength="20" class="SmallInput" id="txtLabelPrefix" />
         </li>
         <li>
            <label for="txtLabelSuffix">'.$this->Context->GetDefinition('LabelSuffix').'</label>
            <input type="text" name="TEXT_SUFFIX" value="'.$this->ConfigurationManager->GetSetting('TEXT_SUFFIX').'" maxlength="20" class="SmallInput" id="txtLabelSuffix" />
         </li>
         <li>
            <label for="txtWhisperLabel">'.$this->Context->GetDefinition('WhisperLabel').'</label>
            <input type="text" name="TEXT_WHISPERED" value="'.$this->ConfigurationManager->GetSetting('TEXT_WHISPERED').'" maxlength="30" class="SmallInput" id="txtWhisperLabel" />
         </li>
         <li>
            <label for="txtStickyLabel">'.$this->Context->GetDefinition('StickyLabel').'</label>
            <input type="text" name="TEXT_STICKY" value="'.$this->ConfigurationManager->GetSetting('TEXT_STICKY').'" maxlength="30" class="SmallInput" id="txtStickyLabel" />
         </li>
         <li>
            <label for="txtSinkLabel">'.$this->Context->GetDefinition('SinkLabel').'</label>
            <input type="text" name="TEXT_SINK" value="'.$this->ConfigurationManager->GetSetting('TEXT_SINK').'" maxlength="30" class="SmallInput" id="txtSinkLabel" />
         </li>
         <li>
            <label for="txtClosedLabel">'.$this->Context->GetDefinition('ClosedLabel').'</label>
            <input type="text" name="TEXT_CLOSED" value="'.$this->ConfigurationManager->GetSetting('TEXT_CLOSED').'" maxlength="30" class="SmallInput" id="txtClosedLabel" />
         </li>
         <li>
            <label for="txtHiddenLabel">'.$this->Context->GetDefinition('HiddenLabel').'</label>
            <input type="text" name="TEXT_HIDDEN" value="'.$this->ConfigurationManager->GetSetting('TEXT_HIDDEN').'" maxlength="30" class="SmallInput" id="txtHiddenLabel" />
         </li>
         <li>
            <label for="txtBookmarkedLabel">'.$this->Context->GetDefinition('BookmarkedLabel').'</label>
            <input type="text" name="TEXT_BOOKMARKED" value="'.$this->ConfigurationManager->GetSetting('TEXT_BOOKMARKED').'" maxlength="30" class="SmallInput" id="txtBookmarkedLabel" />
            <p class="Description">
               '.$this->Context->GetDefinition('DiscussionLabelsNotes').'
            </p>
         </li>
      </ul>      
      
      <h2>'.$this->Context->GetDefinition('ApplicationSettings').'</h2>
      <ul>
         <li>
            <label for="txtWebPathtoVanilla">'.$this->Context->GetDefinition('WebPathToVanilla').'</label>
            <input type="text" name="BASE_URL" value="'.$this->ConfigurationManager->GetSetting('BASE_URL').'" maxlength="255" class="SmallInput" id="txtWebPathToVanilla" />
            <p class="Description">'.$this->Context->GetDefinition('WebPathNotes').'</p>
         </li>
         <li>
            <label for="txtCookieDomain">'.$this->Context->GetDefinition('CookieDomain').'</label>
            <input type="text" name="COOKIE_DOMAIN" value="'.$this->ConfigurationManager->GetSetting('COOKIE_DOMAIN').'" maxlength="255" class="SmallInput" />
         </li>
         <li>
            <label for="txtCookiePath">'.$this->Context->GetDefinition('CookiePath').'</label>
            <input type="text" name="COOKIE_PATH" value="'.$this->ConfigurationManager->GetSetting('COOKIE_PATH').'" maxlength="255" class="SmallInput" id="txtCookiePath" />
            <p class="Description">'.$this->Context->GetDefinition('CookieSettingsNotes').'</p>
         </li>
      </ul>
      
      <div class="Submit">
         <input type="submit" name="btnSave" value="'.$this->Context->GetDefinition('Save').'" class="Button SubmitButton" />
         <a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>
      </div>
      </form>
   </fieldset>
</div>';
?>