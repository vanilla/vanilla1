<?php
// Note: This file is included from the library/Vanilla.Control.SearchForm.php class.

$this->PostBackParams->Add("PostBackAction", "Search");

echo("<div class=\"SearchForm\" id=\"SimpleSearch\">");
$this->Render_PostBackForm("frmSearchSimple", "get");
echo("<fieldset>
   <input type=\"text\" name=\"Keywords\" value=\"".$this->Search->Keywords."\" class=\"SearchInput\" />
   <input type=\"submit\" name=\"btnSubmit\" value=\"".$this->Context->GetDefinition("Search")."\" class=\"SearchButton\" />
   <a href=\"Javascript:ShowAdvancedSearch();\" id=\"AdvancedSearchButton\">".$this->Context->GetDefinition("Advanced")."</a>
   <div class=\"SearchTypes\">
      <div class=\"SearchTypeLabel\">".$this->Context->GetDefinition("ChooseSearchType")."</div>
      ".$this->TypeRadio->Get()."
   </div>
   </fieldset>
   </form>
</div>
<div id=\"AdvancedSearch\" style=\"display: none;\">
   <fieldset>");
   $this->PostBackParams->Add("Type", "Discussions");
   $this->PostBackParams->Add("Advanced", "1");
   $this->Render_PostBackForm("frmSearchDiscussions", "get");
   echo("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">
      <tr>
         <td colspan=\"4\" class=\"SearchTitle\">".$this->Context->GetDefinition("DiscussionTopicSearch")."</td>
      </tr>
      <tr class=\"SearchLabels\" id=\"TitleLabels\">
         <td>".$this->Context->GetDefinition("FindDiscussionsContaining")."</td>");
         if ($this->Context->Configuration["USE_CATEGORIES"]) echo("<td>".$this->Context->GetDefinition("InTheCategory")."</td>");
         echo("<td>".$this->Context->GetDefinition("WhereTheAuthorWas")."</td>
         <td>&nbsp;</td>
      </tr>
      <tr class=\"SearchInputs\" id=\"TitleInputs\">
         <td><input type=\"text\" name=\"Keywords\" value=\"".($this->Search->Type == "Topics"?$this->Search->Query:"")."\" class=\"AdvancedSearchInput\" /></td>");
         if ($this->Context->Configuration["USE_CATEGORIES"]) {
            $this->CategorySelect->SelectedID = ($this->Search->Type == "Topics" ? $this->Search->Categories : "");
            echo("<td>"
            .$this->CategorySelect->Get()
            ."</td>");
         }
         echo("<td>
            <input id=\"AuthUsername\" name=\"AuthUsername\" type=\"text\" value=\"".($this->Search->Type == "Topics"?$this->Search->AuthUsername:"")."\" class=\"AdvancedUserInput\" /><div class=\"Autocomplete\" id=\"AuthUsername_Choices\"></div><script type=\"text/javascript\">new Ajax.Autocompleter('AuthUsername', 'AuthUsername_Choices', './ajax/getusers.php', {paramName: \"Search\"})</script>
         </td>
         <td><input type=\"submit\" name=\"btnSubmit\" value=\"".$this->Context->GetDefinition("Search")."\" class=\"SearchButton\" /></td>
      </tr>
   </table>
   </form>");
   $this->PostBackParams->Set("Type", "Comments");
   $this->Render_PostBackForm("frmSearchComments", "get");
   echo("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">   
      <tr>
         <td colspan=\"4\" class=\"SearchTitle\">".$this->Context->GetDefinition("DiscussionCommentSearch")."</td>
      </tr>
      <tr class=\"SearchLabels\" id=\"CommentLabels\">
         <td>".$this->Context->GetDefinition("FindCommentsContaining")."</td>");
         if ($this->Context->Configuration["USE_CATEGORIES"]) echo("<td>".$this->Context->GetDefinition("InTheCategory")."</td>");
         echo("<td>".$this->Context->GetDefinition("WhereTheAuthorWas")."</td>
         <td>&nbsp;</td>
      </tr>
      <tr class=\"SearchInputs\" id=\"CommentInputs\">
         <td><input type=\"text\" name=\"Keywords\" value=\"".($this->Search->Type == "Comments"?$this->Search->Query:"")."\" class=\"AdvancedSearchInput\" /></td>");
         if ($this->Context->Configuration["USE_CATEGORIES"]) {
            $this->CategorySelect->SelectedID = ($this->Search->Type == "Comments" ? $this->Search->Categories : "");
            echo("<td>"
            .$this->CategorySelect->Get()
            ."</td>");
         }
         echo("<td>
            <input id=\"AuthUsername2\" name=\"AuthUsername\" type=\"text\" value=\"".($this->Search->Type == "Comments"?$this->Search->AuthUsername:"")."\" class=\"AdvancedUserInput\" /><div class=\"Autocomplete\" id=\"AuthUsername2_Choices\"></div><script type=\"text/javascript\">new Ajax.Autocompleter('AuthUsername2', 'AuthUsername2_Choices', './ajax/getusers.php', {paramName: \"Search\"})</script>
         </td>
         <td><input type=\"submit\" name=\"btnSubmit\" value=\"".$this->Context->GetDefinition("Search")."\" class=\"SearchButton\" /></td>
      </tr>
   </table>
   </form>");
   $this->PostBackParams->Set("Type", "Users");
   $this->Render_PostBackForm("frmSearchUsers", "get");
   echo("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">   
      <tr>
         <td colspan=\"4\" class=\"SearchTitle\">".$this->Context->GetDefinition("UserAccountSearch")."</td>
      </tr>
      <tr class=\"SearchLabels\" id=\"UserLabels\">
         <td>".$this->Context->GetDefinition("FindUserAccountsContaining")."</td>
         <td>".$this->Context->GetDefinition("InTheRole")."</td>
         <td>".$this->Context->GetDefinition("SortResultsBy")."</td>
         <td>&nbsp;</td>
      </tr>
      <tr class=\"SearchInputs\" id=\"UserInputs\">
         <td><input type=\"text\" name=\"Keywords\" value=\"".($this->Search->Type == "Users"?$this->Search->Query:"")."\" class=\"AdvancedSearchInput\" /></td>
         <td>".$this->RoleSelect->Get()."</td>
         <td>".$this->OrderSelect->Get()."</td>
         <td><input type=\"submit\" name=\"btnSubmit\" value=\"".$this->Context->GetDefinition("Search")."\" class=\"SearchButton\" /></td>
      </tr>
   </table>
   </form>
   </fieldset>
</div>");
?>