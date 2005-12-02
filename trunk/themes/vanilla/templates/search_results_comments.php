<?php
// Note: This file is included from the library/Vanilla.Control.SearchForm.php class.

$CommentList .= "<dl class=\"SearchComment ".$Comment->Status.($FirstRow?" FirstComment":"")."\">
   <dt class=\"DataItemLabel DiscussionTopicLabel SearchCommentTopicLabel\">".$this->Context->GetDefinition("DiscussionTopic")."</dt>
   <dd class=\"DataItem DiscussionTopic SearchCommentTopic\"><a href=\"comments.php?DiscussionID=".$Comment->DiscussionID."\">".$Comment->Discussion."</a></dd>
   <dt class=\"ExtendedMetaItemLabel SearchCommentBodyLabel\">".$this->Context->GetDefinition("Comment")."</dt>
   <dd class=\"ExtendedMetaItem SearchCommentBody\"><a href=\"./comments.php?DiscussionID=".$Comment->DiscussionID."&amp;Focus=".$Comment->CommentID."#Comment_".$Comment->CommentID."\">".HighlightTrimmedString($Comment->Body, $HighlightWords, 300)."</a></dd>
   <dt class=\"MetaItemLabel SearchCommentInformationLabel SearchCommentCategoryLabel\">".$this->Context->GetDefinition("Category")."</dt>
   <dd class=\"MetaItem SearchCommentInformation SearchCommentCategory\"><a href=\"./?CategoryID=".$Comment->CategoryID."\">".$Comment->Category."</a></dd>
   <dt class=\"MetaItemLabel SearchCommentInformationLabel SearchCommentAuthorLabel\">".$this->Context->GetDefinition("WrittenBy")."</dt>
   <dd class=\"MetaItem SearchCommentInformation SearchCommentAuthor\"><a href=\"./account.php?u=".$Comment->AuthUserID."\">".$Comment->AuthUsername."</a></dd>
   <dt class=\"MetaItemLabel SearchCommentInformationLabel SearchCommentTimeLabel\">".$this->Context->GetDefinition("Added")."</dt>
   <dd class=\"MetaItem SearchCommentInformation SearchCommentTime\">".TimeDiff($this->Context, $Comment->DateCreated,mktime())."</dd>
</dl>\n";

?>