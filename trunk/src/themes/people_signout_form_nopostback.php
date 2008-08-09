<?php
// Note: This file is included from the library/People/People.Control.Leave.php class.

	echo '<div>';
	$this->Render_Warnings();
	$this->Render_PostBackForm();

	echo '<fieldset>
			<legend>'.$this->Context->GetDefinition('SignOut').'</legend>
			<p>'.$this->Context->GetDefinition('TrySigningOutAgain').'</p>
			<div class="Submit">
				<input type="submit" name="" value="'.$this->Context->GetDefinition('SignOut').'" class="Button" />
			</div>
		</form>
		</fieldset>
	</div>';
?>