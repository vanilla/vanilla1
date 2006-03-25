<?php
// Note: This file is included from the library/Vanilla/Vanilla.Control.SearchForm.php class.

$this->PostBackParams->Add("PostBackAction", "Search");

echo '<div id="Form" class="Account Search">
	<fieldset>
      <legend>'.$this->Context->GetDefinition('Search').'</legend>';
      $this->Render_PostBackForm('SearchSimple', 'get');
      echo '<ul>
         <li id="MainSearchInput">
            <label for="txtKeywords">'.$this->Context->GetDefinition('SearchTerms').'</label>
            <input id="txtKeywords" type="text" name="Keywords" value="'.$this->Search->Keywords.'" />
         </li>
         <li>'
            .$this->Context->GetDefinition('ChooseSearchType')
            .$this->TypeRadio->Get()
         .'</li>
      </ul>
      <div class="Submit">
         <input type="submit" name="btnSubmit" value="'.$this->Context->GetDefinition('Search').'" class="SearchButton" />
         <a href="./" onclick="ShowAdvancedSearch(); return false;" id="AdvancedSearchButton">'.$this->Context->GetDefinition('Advanced').'</a>
      </div>
   </form>';
   
   // Begin Advanced Topic Search Form
   $this->PostBackParams->Add('Type', 'Discussions');
   $this->PostBackParams->Add('Advanced', '1');
   $this->Render_PostBackForm('SearchDiscussions', 'get');
      echo '<h2>'.$this->Context->GetDefinition('DiscussionTopicSearch').'</h2>
      <label for="txtDiscussionKeywords">'.$this->Context->GetDefinition('FindDiscussionsContaining').'</label>';
      if ($this->Context->Configuration['USE_CATEGORIES']) echo '<label for="ddDiscussionCategories">'.$this->Context->GetDefinition('InTheCategory').'</label>';
      echo '<label for="DiscussionAuthUsername">'.$this->Context->GetDefinition('WhereTheAuthorWas').'</label>
      
      <div class="Inputs">
		<input id="txtDiscussionKeywords" type="text" name="Keywords" value="'.($this->Search->Type == 'Topics'?$this->Search->Query:'').'" class="AdvancedSearchInput" />
      ';
      if ($this->Context->Configuration['USE_CATEGORIES']) {
         $this->CategorySelect->Attributes = ' id="ddDiscussionCategories"';
         $this->CategorySelect->SelectedID = ($this->Search->Type == 'Topics' ? $this->Search->Categories : '');
         echo $this->CategorySelect->Get();
      }
      echo '
      <input id="DiscussionAuthUsername" name="AuthUsername" type="text" value="'.($this->Search->Type == 'Topics'?$this->Search->AuthUsername:'').'" class="AdvancedUserInput" />
      <script type="text/javascript">
         var DiscussionAutoComplete = new AutoComplete("DiscussionAuthUsername", false);
         DiscussionAutoComplete.TableID = "DiscussionAutoCompleteResults";
         DiscussionAutoComplete.KeywordSourceUrl = "./ajax/getusers.php?Search=";
      </script>
      <input type="submit" name="btnSubmit" value="'.$this->Context->GetDefinition('Search').'" class="SearchButton" />
		</div>
   </form>';
   
   // Begin Advanced Comment Search Form   
   $this->PostBackParams->Set('Type', 'Comments');
   $this->Render_PostBackForm('SearchComments', 'get');
      echo '<h2>'.$this->Context->GetDefinition('DiscussionCommentSearch').'</h2>
      <label for="txtCommentKeywords">'.$this->Context->GetDefinition('FindCommentsContaining').'</label>';
      if ($this->Context->Configuration['USE_CATEGORIES']) echo '<label for="ddCommentCategories">'.$this->Context->GetDefinition('InTheCategory').'</label>';
      echo '<label for="">'.$this->Context->GetDefinition('WhereTheAuthorWas').'</label>
      
      <div class="Inputs">
		<input id="txtCommentKeywords" type="text" name="Keywords" value="'.($this->Search->Type == 'Comments'?$this->Search->Query:'').'" class="AdvancedSearchInput" />
      ';
      if ($this->Context->Configuration['USE_CATEGORIES']) {
         $this->CategorySelect->Attributes = ' id="ddCommentCategories"';
         $this->CategorySelect->SelectedID = ($this->Search->Type == 'Comments' ? $this->Search->Categories : '');
         echo $this->CategorySelect->Get();
      }
      echo '
      <input id="CommentAuthUsername" name="AuthUsername" type="text" value="'.($this->Search->Type == 'Comments'?$this->Search->AuthUsername:'').'" class="AdvancedUserInput" />
      <script type="text/javascript">
         var CommentAutoComplete = new AutoComplete("CommentAuthUsername", false);
         CommentAutoComplete.TableID = "CommentAutoCompleteResults";
         CommentAutoComplete.KeywordSourceUrl = "./ajax/getusers.php?Search=";
      </script>
      <input type="submit" name="btnSubmit" value="'.$this->Context->GetDefinition('Search').'" class="SearchButton" />
		</div>
   </form>';
   
   // Begin Advanced User Search Form
   
   $this->RoleSelect->Attributes = ' id="ddRoles"';
   $this->OrderSelect->Attributes = ' id="ddOrder"';
   $this->PostBackParams->Set('Type', 'Users');
   $this->Render_PostBackForm('SearchUsers', 'get');
      echo '<h2>'.$this->Context->GetDefinition('UserAccountSearch').'</h2>
      <label for="txtUserKeywords">'.$this->Context->GetDefinition('FindUserAccountsContaining').'</label>
      <label for="ddRoles">'.$this->Context->GetDefinition('InTheRole').'</label>
      <label for="ddOrder">'.$this->Context->GetDefinition('SortResultsBy').'</label>
      
      <div class="Inputs">
      <input id="txtUserKeywords" type="text" name="Keywords" value="'.($this->Search->Type == 'Users'?$this->Search->Query:'').'" class="AdvancedSearchInput" />
      '.$this->RoleSelect->Get().'
      '.$this->OrderSelect->Get().'
      <input type="submit" name="btnSubmit" value="'.$this->Context->GetDefinition('Search').'" class="SearchButton" />
		</div>
   </form>
   </fieldset>
</div>';
?>