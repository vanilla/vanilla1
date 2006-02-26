<?php
// Note: This file is included from the library/Vanilla.Control.SearchForm.php class and
// also from the library/Vanilla/Control/DiscussionForm.php's templates/discussions.php
// include template.

$UnreadUrl = GetUnreadQuerystring($Discussion, $this->Context->Configuration, $CurrentUserJumpToLastCommentPref);
$LastUrl = GetLastCommentQuerystring($Discussion, $this->Context->Configuration, $CurrentUserJumpToLastCommentPref);

$DiscussionList .= '
<li id="Discussion_'.$Discussion->DiscussionID.'" class="'.$Discussion->Status.($Discussion->CountComments == 1?' NoReplies':'').($this->Context->Configuration['USE_CATEGORIES'] ? ' Category_'.$Discussion->CategoryID:'').'">
   <ul>
      <li class="ThreadType">
         <span>'.$this->Context->GetDefinition('ThreadType').'</span>'.DiscussionPrefix($this->Context->Configuration, $Discussion).'
      </li>
      <li class="ThreadTitle">
         <span>'.$this->Context->GetDefinition('ThreadTitle').'</span><a href="'.$UnreadUrl.'">'.$Discussion->Name.'</a>
      </li>
      ';
      if ($this->Context->Configuration['USE_CATEGORIES']) {
         $DiscussionList .= '
         <li class="ThreadCategory">
            <span>'.$this->Context->GetDefinition('Category').' </span><a href="'.GetUrl($this->Context->Configuration, 'index.php', '', 'CategoryID', $Discussion->CategoryID).'">'.$Discussion->Category.'</a>
         </li>
         ';
      }
      $DiscussionList .= '<li class="ThreadStarted">
         <span><a href="'.GetUrl($this->Context->Configuration, 'comments.php', '', 'DiscussionID', $Discussion->DiscussionID, '', '#Item_1').'">'.$this->Context->GetDefinition('StartedBy').'</a> </span><a href="'.GetUrl($this->Context->Configuration, 'account.php', '', 'u', $Discussion->AuthUserID).'">'.$Discussion->AuthUsername.'</a>
      </li>
      <li class="ThreadComments">
         <span>'.$this->Context->GetDefinition('Comments').' </span>'.$Discussion->CountComments.'
      </li>
      <li class="ThreadLastComment">
         <span><a href="'.$LastUrl.'">'.$this->Context->GetDefinition('LastCommentBy').'</a> </span><a href="'.GetUrl($this->Context->Configuration, 'account.php', '', 'u', $Discussion->LastUserID).'">'.$Discussion->LastUsername.'</a>
      </li>
      <li class="ThreadActive">
         <span><a href="'.$LastUrl.'">'.$this->Context->GetDefinition('LastActive').'</a> </span>'.TimeDiff($this->Context, $Discussion->DateLastActive,mktime()).'
      </li>';
      if ($this->Context->Session->UserID > 0) {
         $DiscussionList .= '
      <li class="ThreadNew"><!-- all anchored to make for larger hit area -->
         <a href="'.$UnreadUrl.'"><span>New: </span>'.$Discussion->NewComments.'</a>
      </li>
      ';
      }
   $DiscussionList .= '</ul>
</li>';   
?>