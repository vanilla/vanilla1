<?php
// Note: This file is included from the library/Vanilla.Control.SearchForm.php class.

$this->PostBackParams->Add("PostBackAction", "Search");

echo("<div class=\"SearchForm\" id=\"SimpleSearch\">");
$this->Render_PostBackForm("frmSearch", "get");
echo("<input type=\"text\" name=\"Keywords\" value=\"".$this->Search->Keywords."\" class=\"SearchInput\" id=\"SearchKeywords\" />
   <input type=\"submit\" name=\"btnSubmit\" value=\"".$this->Context->GetDefinition("Search")."\" class=\"SearchButton\" id=\"SearchButton\" />
   <a href=\"Javascript:ShowAdvancedSearch();\" id=\"AdvancedSearchButton\">".$this->Context->GetDefinition("Advanced")."</a>
   <div class=\"SearchTypeLabel\">".$this->Context->GetDefinition("ChooseSearchType")."</div>
   ".$this->TypeRadio->Get()."
   </form>
</div>
<table id=\"AdvancedSearch\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"display: none;\">
   <tr>
      <td colspan=\"4\" class=\"SearchTitle\">".$this->Context->GetDefinition("DiscussionTopicSearch")."</td>
   </tr>
   <tr class=\"SearchLabels\" id=\"TitleLabels\">
      <td>");
$this->PostBackParams->Add("Type", "Discussions");
$this->PostBackParams->Add("Advanced", "1");
$this->Render_PostBackForm("frmSearch", "get");
$Colspan = "";
if ($this->Context->Configuration["USE_CATEGORIES"]) $Colspan = " colspan=\"2\"";
echo($this->Context->GetDefinition("FindDiscussionsContaining")."</td>");
      if ($this->Context->Configuration["USE_CATEGORIES"]) echo("<td>".$this->Context->GetDefinition("InTheCategory")."</td>");
      echo("<td>".$this->Context->GetDefinition("WhereTheAuthorWas")."</td>
      <td".$Colspan.">&nbsp;</td>
   </tr>
   <tr class=\"SearchInputs\" id=\"TitleInputs\">
      <td><input type=\"text\" name=\"Keywords\" value=\"".($this->Search->Type == "Topics"?$this->Search->Query:"")."\" class=\"AdvancedSearchInput\" id=\"SearchKeywords\" /></td>");
      if ($this->Context->Configuration["USE_CATEGORIES"]) {
         $this->CategorySelect->SelectedID = ($this->Search->Type == "Topics" ? $this->Search->Categories : "");
         echo("<td>"
         .$this->CategorySelect->Get()
         ."</td>");
      }
      echo("<td>
         <input autocomplete=\"off\" id=\"AuthUsername\" name=\"AuthUsername\" type=\"text\" value=\"".($this->Search->Type == "Topics"?$this->Search->AuthUsername:"")."\" class=\"AdvancedUserInput\" /><div class=\"Autocomplete\" id=\"AuthUsername_Choices\"></div><script type=\"text/javascript\">new Ajax.Autocompleter('AuthUsername', 'AuthUsername_Choices', './ajax/getusers.php', {paramName: \"Search\"})</script>
      </td>
      <td><input type=\"submit\" name=\"btnSubmit\" value=\"".$this->Context->GetDefinition("Search")."\" class=\"SearchButton\" id=\"SearchButton\" /></form></td>
   </tr>
   <tr>
      <td colspan=\"4\" class=\"SearchTitle\">".$this->Context->GetDefinition("DiscussionCommentSearch")."</td>
   </tr>
   <tr class=\"SearchLabels\" id=\"CommentLabels\">
      <td>");
$this->PostBackParams->Set("Type", "Comments");
$this->Render_PostBackForm("frmSearch", "get");
echo($this->Context->GetDefinition("FindCommentsContaining")."</td>");
      if ($this->Context->Configuration["USE_CATEGORIES"]) echo("<td>".$this->Context->GetDefinition("InTheCategory")."</td>");
      echo("<td>".$this->Context->GetDefinition("WhereTheAuthorWas")."</td>
      <td".$Colspan.">&nbsp;</td>
   </tr>
   <tr class=\"SearchInputs\" id=\"CommentInputs\">
      <td><input type=\"text\" name=\"Keywords\" value=\"".($this->Search->Type == "Comments"?$this->Search->Query:"")."\" class=\"AdvancedSearchInput\" id=\"SearchKeywords\" /></td>");
      if ($this->Context->Configuration["USE_CATEGORIES"]) {
         $this->CategorySelect->SelectedID = ($this->Search->Type == "Comments" ? $this->Search->Categories : "");
         echo("<td>"
         .$this->CategorySelect->Get()
         ."</td>");
      }
      echo("<td>
         <input autocomplete=\"off\" id=\"AuthUsername2\" name=\"AuthUsername\" type=\"text\" value=\"".($this->Search->Type == "Comments"?$this->Search->AuthUsername:"")."\" class=\"AdvancedUserInput\" /><div class=\"Autocomplete\" id=\"AuthUsername2_Choices\"></div><script type=\"text/javascript\">new Ajax.Autocompleter('AuthUsername2', 'AuthUsername2_Choices', './ajax/getusers.php', {paramName: \"Search\"})</script>
      </td>
      <td".$Colspan."><input type=\"submit\" name=\"btnSubmit\" value=\"".$this->Context->GetDefinition("Search")."\" class=\"SearchButton\" id=\"SearchButton\" /></form></td>
   </tr>
   <tr>
      <td colspan=\"4\" class=\"SearchTitle\">".$this->Context->GetDefinition("UserAccountSearch")."</td>
   </tr>
   <tr class=\"SearchLabels\" id=\"UserLabels\">
      <td>");
$this->PostBackParams->Set("Type", "Users");
$this->Render_PostBackForm("frmSearch", "get");
echo($this->Context->GetDefinition("FindUserAccountsContaining")."</td>
      <td>".$this->Context->GetDefinition("InTheRole")."</td>
      <td>".$this->Context->GetDefinition("SortResultsBy")."</td>
      <td>&nbsp;</td>
   </tr>
   <tr class=\"SearchInputs\" id=\"UserInputs\">
      <td><input type=\"text\" name=\"Keywords\" value=\"".($this->Search->Type == "Users"?$this->Search->Query:"")."\" class=\"AdvancedSearchInput\" id=\"SearchKeywords\" /></td>
      <td>".$this->RoleSelect->Get()."</td>
      <td>".$this->OrderSelect->Get()."</td>
      <td><input type=\"submit\" name=\"btnSubmit\" value=\"".$this->Context->GetDefinition("Search")."\" class=\"SearchButton\" id=\"SearchButton\" /></form></td>
   </tr>
</table>");

?>