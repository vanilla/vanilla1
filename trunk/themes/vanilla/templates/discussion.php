<?php
// Note: This file is included from the library/Vanilla.Control.SearchForm.php class and
// also from the library/Vanilla/Control/DiscussionForm.php's templates/discussions.php
// include template.

$UnreadQS = "";
if ($this->Context->Session->UserID > 0) $UnreadQS = GetUnreadQuerystring($Discussion, $this->Context->Configuration);
$LastQS = GetLastCommentQuerystring($Discussion, $this->Context->Configuration);

$DiscussionList .= "<dl class=\"Discussion".$Discussion->Status.($FirstRow?" FirstDiscussion":"").($Discussion->CountComments == 1?" NoReplies":"").($this->Context->Configuration["USE_CATEGORIES"] ? " Category_".$Discussion->CategoryID:"")."\">
   <dt class=\"DataItemLabel DiscussionTopicLabel\">".$this->Context->GetDefinition("DiscussionTopic")."</dt>
   <dd class=\"DataItem DiscussionTopic\">".DiscussionPrefix($this->Context->Configuration, $Discussion)."<a href=\"comments.php?DiscussionID=".$Discussion->DiscussionID.($CurrentUserJumpToLastCommentPref ? $UnreadQS : "")."\">".$Discussion->Name."</a></dd>";
   if ($this->Context->Configuration["USE_CATEGORIES"]) {
      $DiscussionList .= "
      <dt class=\"MetaItemLabel DiscussionInformationLabel DiscussionCategoryLabel\">".$this->Context->GetDefinition("Category")."</dt>
      <dd class=\"MetaItem DiscussionInformation DiscussionCategory\"><a href=\"./?CategoryID=".$Discussion->CategoryID."\">".$Discussion->Category."</a></dd>
      ";
   }
   $DiscussionList .= "<dt class=\"MetaItemLabel DiscussionInformationLabel StarterLabel\"><a href=\"./comments.php?DiscussionID=".$Discussion->DiscussionID."#Item_1\">".$this->Context->GetDefinition("StartedBy")."</a></dt>
   <dd class=\"MetaItem DiscussionInformation Starter\"><a href=\"./account.php?u=".$Discussion->AuthUserID."\">".$Discussion->AuthUsername."</a></dd>
   <dt class=\"MetaItemLabel DiscussionInformationLabel CommentCountLabel\">".$this->Context->GetDefinition("Comments")."</dt>
   <dd class=\"MetaItem DiscussionInformation CommentCount\">".$Discussion->CountComments."</dd>
   <dt class=\"MetaItemLabel DiscussionInformationLabel LastReplierLabel\"><a href=\"./comments.php?DiscussionID=".$Discussion->DiscussionID.$LastQS."\">".$this->Context->GetDefinition("LastCommentBy")."</a></dt>
   <dd class=\"MetaItem DiscussionInformation LastReplier\"><a href=\"./account.php?u=".$Discussion->LastUserID."\">".$Discussion->LastUsername."</a></dd>
   <dt class=\"MetaItemLabel DiscussionInformationLabel LastActiveLabel\"><a href=\"./comments.php?DiscussionID=".$Discussion->DiscussionID.$LastQS."\">".$this->Context->GetDefinition("LastActive")."</a></dt>
   <dd class=\"MetaItem DiscussionInformation LastActive\">".TimeDiff($this->Context, $Discussion->DateLastActive,mktime())."</dd>";
   if ($this->Context->Session->UserID > 0) {
      $DiscussionList .= "<dt class=\"MetaItemLabel DiscussionInformationLabel NewCommentCountLabel".($Discussion->NewComments>0?" NewCommentsPresentLabel":"")."\"><a href=\"comments.php?DiscussionID=".$Discussion->DiscussionID.$UnreadQS."\">".$this->Context->GetDefinition("New")."</a></dt>
      <dd class=\"MetaItem DiscussionInformation NewCommentCount".($Discussion->NewComments>0?" NewCommentsPresent":"")."\"><a href=\"comments.php?DiscussionID=".$Discussion->DiscussionID.$UnreadQS."\">".$Discussion->NewComments."</a></dd>";
   }
$DiscussionList .= "</dl>\n";
   
?>