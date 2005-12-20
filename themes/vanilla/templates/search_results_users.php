<?php
// Note: This file is included from the library/Vanilla.Control.SearchForm.php class.

$ShowIcon = ($u->DisplayIcon != "" && $this->Context->Session->User->Preference("HtmlOn"));
$UserList .= "<dl class=\"User".($Switch == 1?"":"Alternate").($FirstRow?" FirstUser":"")."\">
   <dt class=\"DataItemLabel SearchUserLabel\">".$this->Context->GetDefinition("User")."</dt>
   <dd class=\"DataItem SearchUser".($ShowIcon?" SearchUserWithIcon":"")."\">";
      if ($ShowIcon) $UserList .= "<span class=\"SearchIcon\" style=\"background-image:url('".$u->DisplayIcon."');\">&nbsp;</span>";
      $UserList .= "<a href=\"".GetUrl($this->Context->Configuration, "account.php", "", "u", $u->UserID)."\">".$u->Name."</a> (".$u->Role.")
   </dd>
   <dt class=\"MetaItemLabel SearchUserInformationLabel SearchUserAccountCreatedLabel\">".$this->Context->GetDefinition("AccountCreated")."</dt>
   <dd class=\"MetaItem SearchUserInformation SearchUserAccountCreated\">".TimeDiff($this->Context, $u->DateFirstVisit,mktime())."</dd>
   <dt class=\"MetaItemLabel SearchUserInformationLabel SearchUserLastActiveLabel\">".$this->Context->GetDefinition("LastActive")."</dt>
   <dd class=\"MetaItem SearchUserInformation SearchUserLastActive\">".TimeDiff($this->Context, $u->DateLastActive,mktime())."</dd>
   <dt class=\"MetaItemLabel SearchUserInformationLabel SearchUserVisitCountLabel\">".$this->Context->GetDefinition("VisitCount")."</dt>
   <dd class=\"MetaItem SearchUserInformation SearchUserVisitCount\">".$u->CountVisit."</dd>
   <dt class=\"MetaItemLabel SearchUserInformationLabel SearchUserDiscussionsCreatedLabel\">".$this->Context->GetDefinition("DiscussionsCreated")."</dt>
   <dd class=\"MetaItem SearchUserInformation SearchUserDiscussionsCreated\">".$u->CountDiscussions."</dd>
   <dt class=\"MetaItemLabel SearchUserInformationLabel SearchUserCommentsAddedLabel\">".$this->Context->GetDefinition("CommentsAdded")."</dt>
   <dd class=\"MetaItem SearchUserInformation SearchUserCommentsAdded\">".$u->CountComments."</dd>
</dl>";

?>