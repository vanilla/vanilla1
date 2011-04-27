<?php
// Note: This file is included from the
//  extensions/SetList/SetList.Control.SetListForm.php control.

echo '
			<div id="Form" class="Account Preferences">';
if ( $this->PostBackValidated ) {
	echo '
				<div id="Success">' . $this->Context->GetDefinition('ChangesSaved')
					. '</div>';
}
echo '
				<fieldset>
					<legend>' . $this->formName . '</legend>
					' . $this->Get_Warnings() . '
					' . $this->Get_PostBackForm('frm'.$this->formKey) . '
					<ul>';
$elementKeys = array_keys($this->formData['elements']);
foreach ($elementKeys as $element) {
	if ( $this->shouldRender($element) ) {
		echo '
						<li>
							' . $this->renderLabel($element) . '
							' . $this->renderElementHtml($element) . '
							' . $this->renderDescription($element) . '
						</li>';
	}
}
echo '
					</ul>
					<div class="Submit">
						<input type="submit" name="btnSave"'
							. ' value="'.$this->Context->GetDefinition('Save').'"'
							. ' class="Button SubmitButton" />
						<a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl).'"'
							. ' class="CancelButton">'
							. $this->Context->GetDefinition('Cancel') . '</a>
					</div>
					</form>
				</fieldset>
			</div>';
?>
