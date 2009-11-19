<?php
	if ($Configuration['NOTIFI_AUTO_ALL'] == '0') {
		class NotificationControl extends PostBackControl {
			var $Context;

			function NotificationControl($Context) {
				$this->ValidActions = array("Notification");
				$this->Constructor($Context);
				$this->Context = $Context;
			}

			function Render() {
				if ($this->IsPostBack) {
					if (notifiCheck($this->Context,'SubscribedEntireForum')) {
						$SubscribedEntireForum = ' style="display:none"';
					} else {
						$SubscribedEntireForum = '';
					}
					if (notifiCheck($this->Context,'SubscribeComment') && $SubscribedEntireForum != ' style="display:none"') {
						$SubscribedComment = ' style="display:none"';
					} else {
						$SubscribedComment = '';
					}

					$u = $this->Context->Session->UserID;
					echo '
                  <div id="Form" class="Account Preferences Notifications">
		     <form method="post" action="">
		        <fieldset>
			   <legend>'.$this->Context->GetDefinition("NotificationManagement").'</legend>
			   <p class="Description"><strong>Changes will be made <strong style="color:#c00;">instantly</strong> when you check/uncheck the boxes.<br />There is <strong style="color:#c00;">no submit button</strong></strong></p>
			   <h2>'.$this->Context->GetDefinition("NotificationOptions").'</h2>
			   <ul>
			      <li>';
					$Active = ' ';
					if (notifiCheck($this->Context,'SubscribeOwn') == 1) {
						$Active = 'checked="checked" ';
					}
					echo '
	          <p id="NotifiOwnCont"'.$SubscribedEntireForum.$SubscribedComment.'>
		     <span>
		        <label for="NotifiOwnField">
			   <input type="checkbox" value="1" id="NotifiOwnField" '.$Active.' onclick="NotifiOwn();" /> '.$this->Context->GetDefinition("NotificationOnOwnExplanation").'
			</label>
		     </span>
		  </p>';
					$Active = ' ';
					if (notifiCheck($this->Context,'SubscribeComment') == 1) {
						$Active = 'checked="checked" ';
					}
					echo '
	          <p id="NotifiCommentCont"'.$SubscribedEntireForum.'>
		     <span>
		        <label for="NotifiCommentField">
			   <input type="checkbox" value="1" id="NotifiCommentField" '.$Active.' onclick="NotifiComment();" /> '.$this->Context->GetDefinition("NotificationOnCommentExplanation").'
			</label>
		     </span>
		  </p>';
					$Active = ' ';
					if (notifiCheck($this->Context,'KeepEmailing')== true) {
						$Active = 'checked="checked" ';
					}
					echo '<p id="KeepEmailingCont">
		     <span>
		        <label for="KeepEmailingField">
			   <input type="checkbox" value="1" id="KeepEmailingField" '.$Active.' onclick="KeepEmailing();" /> '.$this->Context->GetDefinition("KeepEmailingExplanation").'
			</label>
		     </span>
		  </p>';
					echo '
	          </li>
	       </ul>';
					echo '
	       <legend>'.$this->Context->GetDefinition("YourNotifications").'</legend>';
					if ($this->Context->Configuration['NOTIFI_ALLOW_ALL'] == 1) {
						echo '
		     <h2>Forum</h2>
		     <ul>';
 						$Active = ' ';
						if (CheckNotifi($this->Context,'ALL',0,$u)== true) {
							$Active = 'checked="checked" ';
						}
						echo '
	             <li>
		        <p>
			   <span id="NotifiAllCont">
			      <label for="NotifiAll">
			         <input type="checkbox" value="1" id="NotifiAllField" '.$Active.' onclick="NotifiAll();" /> '.$this->Context->GetDefinition("NotificationForum").'
			      </label>
			   </span>
			</p>
		     </li>
		  </ul>';
					}
					if ($this->Context->Configuration['NOTIFI_ALLOW_CATEGORY'] == 1) {
						$CategoryManager = $this->Context->ObjectFactory->NewContextObject($this->Context, 'CategoryManager');
						$CategoryData = $CategoryManager->GetCategories(0, 1);
						if ($CategoryData) {
							echo '
		        <span id="categoriesContainer"'.$SubscribedEntireForum.'>
			<h2>Categories</h2>
			<p>Notify me on new comments in the following categories</p>
			<ul> ';
							$cat = $this->Context->ObjectFactory->NewObject($this->Context, 'Category');
							while ($Row = $this->Context->Database->GetRow($CategoryData)) {
								$cat->Clear();
								$cat->GetPropertiesFromDataSet($Row);
								$Active = '';
								if (CheckNotifi($this->Context,'CATEGORY',$cat->CategoryID,$u)== true) {
									$Active = 'checked="checked" ';
								}
								echo '
		           <li>
			      <p>
			         <span id="NotifiCatCont_'.$cat->CategoryID.'">
				    <label for="NotifiCat_'.$cat->CategoryID.'">
				       <input type="checkbox" name="NotifiCat_'.$cat->CategoryID.'" value="1" id="NotifiCat_'.$cat->CategoryID.'" '.$Active.' onclick="NotifiCat('.$cat->CategoryID.');" /> '.$cat->Name.'
				    </label>
				 </span>
			      </p>
			   </li>';
							}
							echo '</ul></span>';
						}
					}
					echo '<span id="discussionsContainer"'.$SubscribedEntireForum.'>';
					if ($this->Context->Configuration['NOTIFI_ALLOW_DISCUSSION'] == 1) {
						$res = mysql_query("SELECT B.DiscussionID,B.Name FROM ".$this->Context->Configuration['DATABASE_TABLE_PREFIX']."Notifi A INNER JOIN ".$this->Context->Configuration['DATABASE_TABLE_PREFIX']."Discussion B ON (A.SelectID = B.DiscussionID) WHERE A.UserID = '".$u."' AND A.Method = 'DISCUSSION' ORDER BY B.DateLastActive",$this->Context->Database->Connection);
						if (mysql_num_rows($res) > 0) {
							echo '
			<h2>Discussions</h2>
			<p>Notify me on new comments in the following discussions. Only selected discussions are listed here. To submit to a discussion use the link provided on the discussions tab</p>
                        <ul> ';
							while ($row = mysql_fetch_array($res)) {
								$Active = '';
								if (CheckNotifi($this->Context,'DISCUSSION',$row[0])) {
									$Active = 'checked="checked" ';
								}
								echo '
		           <li>
			      <p>
			         <span id="NotifiDiscussionCont_'.$row[0].'">
				    <label for="NotifiDiscussion_'.$row[0].'">
				       <input type="checkbox" name="NotifiDiscussion_'.$row[0].'" value="1" id="NotifiDiscussion_'.$row[0].'" '.$Active.' onclick="NotifiDiscussion('.$row[0].');" /> '.$row[1].'
				    </label>
				 </span>
			      </p>
			   </li>';
							}
							echo '</ul>';
						}
					}
					echo '
	          </span>
		  </fieldset>
	       </form>
	    </div>';
				}
			}
		}
	}
?>