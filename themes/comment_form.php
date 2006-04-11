<?php
// Note: This file is included from the library/Vanilla/Vanilla.Control.DiscussionForm.php class.

echo '<div id="Form" class="AddComments">
   <fieldset>
		<legend>'.$this->Title.'</legend>'
		.$this->Get_PostBackForm('frmPostComment', 'post', 'post.php')
      .$this->Get_Warnings()
   .'<ul>';
   
      $this->CallDelegate('CommentForm_PreWhisperInputRender');
   
      if ($this->Context->Configuration['ENABLE_WHISPERS']) {
			if ($this->Discussion->WhisperUserID > 0) {
				echo '<li>'.str_replace('//1', (($this->Discussion->WhisperUserID == $this->Context->Session->UserID) ? $this->Discussion->AuthUsername : $this->Discussion->WhisperUsername), $this->Context->GetDefinition('YourCommentsWillBeWhisperedToX')).'</li>';
			} else {
				echo '<li>
					<label for="WhisperUsername">'.$this->Context->GetDefinition('WhisperYourCommentsTo').'</label>
					<input id="WhisperUsername" name="WhisperUsername" type="text" value="'.FormatStringForDisplay($Comment->WhisperUsername, 0).'" class="Whisper AutoCompleteInput" maxlength="20" />
					<script type="text/javascript">
						var WhisperAutoComplete = new AutoComplete("WhisperUsername", false);
						WhisperAutoComplete.TableID = "WhisperAutoCompleteResults";
						WhisperAutoComplete.KeywordSourceUrl = "./ajax/getusers.php?Search=";
					</script>
				</li>
				';
			}
      }
   
      $this->CallDelegate('CommentForm_PreCommentsInputRender');
      
      echo '<li>
         <label for="CommentBox">
            <a href="./" id="CommentBoxController" onclick="'
               ."ToggleCommentBox('".$this->Context->GetDefinition('SmallInput')."', '".$this->Context->GetDefinition('BigInput')."'); return false;".'">'.$this->Context->GetDefinition($this->Context->Session->User->Preference('ShowLargeCommentBox')?'SmallInput':'BigInput').'</a>
				'.$this->Context->GetDefinition('EnterYourComments').'
         </label>
         <textarea name="Body" class="'
         .($this->Context->Session->User->Preference('ShowLargeCommentBox') ? 'LargeCommentBox' : 'SmallCommentBox')
         .'" id="CommentBox" rows="10" cols="85">'
         .$this->Comment->Body
         .'</textarea>
      </li>
		'.$this->GetPostFormatting($Comment->FormatType)
	.'</ul>';
   
   $this->CallDelegate('CommentForm_PreButtonsRender');
   
	echo '<div class="Submit">
		<input type="submit" name="btnSave" value="'.($Comment->CommentID > 0 ? $this->Context->GetDefinition('SaveYourChanges') : $this->Context->GetDefinition('AddYourComments'))
			.'" class="Button SubmitButton AddCommentsButton" onclick="'
			."Wait(this, '".$this->Context->GetDefinition('Wait')."');"
			.'" />';
		$this->CallDelegate('CommentForm_PostSubmitRender');
		
		echo '&nbsp;';
		
		if ($this->PostBackAction == 'SaveComment' || ($this->PostBackAction == '' && $Comment->CommentID > 0)) {
			if ($this->Comment->DiscussionID > 0) {
				echo '<a href="'.GetUrl($this->Context->Configuration, 'comments.php', '', 'DiscussionID', $this->Comment->DiscussionID).'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>';
			} else {
				echo '<a href="'.GetUrl($this->Context->Configuration, 'index.php').'" class="CancelButton">'.$this->Context->GetDefinition('Cancel').'</a>';
			}
		}
	echo '</div>';
      
   $this->CallDelegate('CommentForm_PostButtonsRender');
   
   echo '
   </form>
   </fieldset>
</div>';
?>