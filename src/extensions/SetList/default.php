<?php
/*
Extension Name: Set List
Extension Url: http://vanillaforums.org/addon/386/set-list
Description: Quickly and easily create a form for any extension's settings.
Version: 1.1.0
Author: squirrel
Author Url: http://digitalsquirrel.com/
*/


if ( 'settings.php' != $Context->SelfUrl ) {
	return;
}

/**
 * Displays an extension's settings form based on an array of form data.
 *
 * @author squirrel <squirrel@digitalsquirrel.com>
 */
class SetListForm extends PostBackControl
{
	/**
	 * @var string
	 */
	var $formKey  = '';

	/**
	 * @var string
	 */
	var $formName = '';

	/**
	 * @var array
	 */
	var $formData = null;

	/**
	 * @var array
	 */
	var $_special = array();

	/**
	 * Initialize form data and fetch user-submitted form values.
	 * 
	 * Note that $formData doesn't contain the actual data, only a key to the
	 * data in the configuration array. This is necessary because SetListForm
	 * and SetListManager expect to be sharing references to the same data,
	 * but Vanilla's CreateControl() function doesn't pass arguments by
	 * reference.
	 * 
	 * @param Context $Context
	 * @param string  $formKey  - Key created by FormatExtensionKey()
	 * @param string  $formName - Display name of the extension
	 * @param string  $formData - The config key where form data is waiting
	 */
	function SetListForm(&$Context, $formKey, $formName, $formData)
	{
		$this->formKey  = $formKey;
		$this->formName = $formName;

		// Get the form data from configuration.
		// Necessary because CreateControl doesn't pass arguments by reference.
		if ( isset($Context->Configuration[$formData]) ) {
			$this->formData = &$Context->Configuration[$formData];
		}
		else {
			$this->formData = array();
		}

		$this->ValidActions = array($formKey, 'Process'.$formKey);

		// Call the base class constructor.
		$this->Constructor($Context);
		$this->PostBackParams->Set('PostBackAction', 'Process'.$formKey);

		// Validate the form.
		if ( ('Process'.$formKey == $this->PostBackAction) && $this->IsValidFormPostBack() ) {
			// Grab the submitted values this form expects.
			foreach ($this->formData['elements'] as $name => $elem) {
				// File elements don't have a value.
				// Instead, grab the contents of the $_FILES superglobal.
				if ( 'file' == $elem['type'] ) {
					if ( !empty($_FILES[$name]) ) {
						$this->formData['elements'][$name]['fileinfo'] = $_FILES[$name];
					}
				}
				else {
					$this->formData['elements'][$name]['value'] = ForceIncomingString($name, '');
				}
			}

			$this->PostBackValidated = 1;
		}
	}

	/**
	 * Return true if the element should be rendered.
	 *
	 * @param string $element
	 * @return boolean
	 */
	function shouldRender($element)
	{
		return (
			('hidden' != $this->formData['elements'][$element]['type'])
			&& !$this->formData['elements'][$element]['norender']
			);
	}

	/**
	 * Render the element's label to an appropriate html string.
	 *
	 * Checkbox labels are a special case. They are rendered in the
	 * renderElementHtml() method, not here.
	 *
	 * @param string $element
	 * @return string
	 */
	function renderLabel($element)
	{
		$label = $this->formData['elements'][$element]['label'];
		if ( $label ) {
			switch ($this->formData['elements'][$element]['type']) {
				case 'text':
				case 'textarea':
					$label = '<label for="txt' . $element . '">' . $label . '</label>';
					break;

				case 'file':
					$label = '<label for="file' . $element . '">' . $label . '</label>';
					break;

				case 'select':
					$label = '<label for="sel' . $element . '">' . $label . '</label>';
					break;

				// Special: Checkbox labels are rendered in renderElementHtml().
				case 'checkbox':
				default:
					$label = '';
					break;
			}
		}
		return $label;
	}

	/**
	 * Render the element's description to an html string.
	 *
	 * @param string $element
	 * @return string
	 */
	function renderDescription($element)
	{
		$description = $this->formData['elements'][$element]['description'];
		if ( $description ) {
			$description = '<p class="Description">' . $description . '</p>';
		}
		return $description;
	}

	/**
	 * Render an element to an html string.
	 *
	 * This does not include the label or description, only the html form
	 * element itself. Checkbox labels are a special case. They are rendered
	 * by this method because the label must appear after the checkbox on the
	 * same line.
	 *
	 * @param string $element
	 * @return string
	 */
	function renderElementHtml($element)
	{
		$html = '';
		$value = FormatStringForDisplay(
			$this->formData['elements'][$element]['value'],
			false,
			true);
		$label = $this->formData['elements'][$element]['label'];

		switch ($this->formData['elements'][$element]['type']) {
			// Special: Checkbox labels are rendered here, not in renderLabel().
			case 'checkbox':
				$html = '<p><span><label for="' . $element . '">' . 
					GetBasicCheckBox($element, 1, (ForceBool($value, 0) ? 1 : 0), 'id="'.$element.'"')
					. ' ' . $label . '</label></span></p>';
				break;

			case 'header':
				$html = '<h2>' . $label . '</h2>';
				break;

			case 'text':
				$html = '<input type="text" name="' . $element
					. '" value="' . $value
					. '" class="SmallInput" id="txt' . $element . '" />';
				break;

			case 'textarea':
				$html = '<textarea name="' . $element . '" id="txt' . $element
					. '" rows="15" cols="40">' . $value . '</textarea>';
				break;

			case 'select':
				$select = $this->Context->ObjectFactory->NewObject($this->Context, 'Select');
				$select->Name = $element;
				$select->Attributes .= 'id="sel' . $element . '"';
				foreach ($this->formData['elements'][$element]['options'] as $option) {
					$select->AddOption($option['value'], $option['label']);
				}
				$select->SelectedValue = $value;
				$html = $select->Get();
				break;

			case 'file':
				$html = '<input type="file" name="' . $element
					. '" class="SmallInput" id="file' . $element . '" />';
				break;

			default:
				break;
		}

		return $html;
	}

	/**
	 * Overrides PostBackControl::Get_PostBackForm().
	 */
	function Get_PostBackForm($FormID = '', $PostBackMethod = 'post', $TargetUrl = '', $EncType = '', $TargetFrame = '') {
		if ( empty($EncType) && !empty($this->formData['enctype']) ) {
			$EncType = $this->formData['enctype'];
		}
		return parent::Get_PostBackForm($FormID, $PostBackMethod, $TargetUrl, $EncType, $TargetFrame);
	}

	/**
	 * Render the extension settings form.
	 * 
	 * Look for a template in the theme path to render. If none exists, the
	 * template in SetList's extension directory is used.
	 */
	function Render()
	{
		if ($this->IsPostBack) {
			$this->CallDelegate('PreRender');

			// Stick the hidden form elements into post back params.
			foreach ($this->formData['elements'] as $key => $elem) {
				if ( ('hidden' == $elem['type']) && !$elem['norender'] ) {
					$value = FormatStringForDisplay($elem['value'], false, true);
					$this->PostBackParams->Set($key, $value, 0, '', 0);
				}
			}

			// If a themed version of the settings form exists, use it.
			$templateFile = 'settings_setlist_form.php';
			if ( file_exists($this->Context->Configuration['THEME_PATH'].$templateFile) ) {
				$templateFile = $this->Context->Configuration['THEME_PATH']
					. $templateFile;
			}
			else {
				$templateFile = $this->Context->Configuration['APPLICATION_PATH']
					. 'extensions/SetList/' . $templateFile;
			}
			include($templateFile);

			$this->CallDelegate('PostRender');
		}
	}
}


/**
 * Oversees all stages of the settings form process, from parsing the
 * form descriptor INI to saving the configuration settings.
 *
 * @author squirrel <squirrel@digitalsquirrel.com>
 */
class SetListManager extends Control
{
	/**
	 * @var array
	 */
	var $forms = array();

	/**
	 * @var string
	 */
	var $_nestSeparator = '.';

	/**
	 * @var array
	 */
	var $_validElements = array(
		'checkbox',
		'header',
		'hidden',
		'select',
		'text',
		'textarea',
		'file',
	);

	/**
	 * Call the base class constructor.
	 */
	function SetListManager(&$Context)
	{
		$this->Name = 'SetList';
		$this->Control($Context);
	}

	/**
	 * Assign the key's value to the property list. Handle the "dot"
	 * notation for sub-properties by recursively splitting dotted keys.
	 *
	 * Code sampled from Zend Framework 1.5.1, library/Zend/Config/Ini.php.
	 * This copying is believed by the author to be "fair use".
	 *
	 * @param  array  $config
	 * @param  string $key
	 * @param  string $value
	 * @return array
	 */
	function _processKey($config, $key, $value)
	{
		if ( strpos($key, $this->_nestSeparator) !== false ) {
			$pieces = explode($this->_nestSeparator, $key, 2);
			if ( strlen($pieces[0]) && strlen($pieces[1]) ) {
				if ( !isset($config[$pieces[0]]) ) {
					$config[$pieces[0]] = array();
				}
				$config[$pieces[0]] = $this->_processKey($config[$pieces[0]], $pieces[1], $value);
			}
		}
		else {
			$config[$key] = $value;
		}
		return $config;
	}

	/**
	 * Build form data from an INI file and add it to the form list.
	 *
	 * Repair broken form data if possible. The goal here is to salvage a
	 * working form from a bare minimum of required syntax. This helps keep
	 * INI files as small as possible.
	 *
	 * Returns true if a form was successfully added to the list.
	 *
	 * @param  string $formKey
	 * @param  string $iniFile
	 * @return boolean
	 */
	function parseIniFile($formKey, $iniFile)
	{
		// Duh.
		if ( !file_exists($iniFile) ) {
			return false;
		}

		// Parse the INI file without sections.
		$ini = parse_ini_file($iniFile, false);
		$form = array();

		// Split nested keys into arrays.
		foreach ($ini as $key => $val) {
			$form = $this->_processKey($form, $key, $val);
		}

		// Drop elements with no valid type.
		$formElements = array_keys($form['elements']);
		foreach ($formElements as $elemKey) {
			if ( !isset($form['elements'][$elemKey]['type']) || !in_array($form['elements'][$elemKey]['type'], $this->_validElements) ) {
				unset($form['elements'][$elemKey]);
			}
		}

		// Only the strong survive.
		if ( empty($form['elements']) ) {
			return false;
		}

		// Check out the form data and make repairs.
		foreach ($form['elements'] as $elemKey => $element) {
			// Set defaults for all expected attributes.
			$default = array();
			$default['label'] = $elemKey;
			$default['description'] = '';
			$default['configkey'] = $formKey.'.'.$elemKey;
			$default['value'] = '';
			$default['nosave'] = false;
			$default['norender'] = false;
			$default['options'] = false;
			$element = array_merge($default, $element);

			// Let translated versions of these texts override the INI.
			$this->Context->SetDefinition($formKey.'.'.$elemKey.'.label', $element['label']);
			$this->Context->SetDefinition($formKey.'.'.$elemKey.'.description', $element['description']);
			$element['label'] = $this->Context->GetDefinition($formKey.'.'.$elemKey.'.label');
			$element['description'] = $this->Context->GetDefinition($formKey.'.'.$elemKey.'.description');

			// Set element value from configuration value.
			if ( isset($this->Context->Configuration[$element['configkey']]) ) {
				$element['value'] = $this->Context->Configuration[$element['configkey']];
			}

			// Special case: Headers don't have a value to save.
			if ( 'header' == $element['type'] ) {
				$element['nosave'] = true;
			}

			// Special case: Set up select input options.
			else if ( 'select' == $element['type'] ) {
				// Force options to an array.
				if ( !is_array($element['options']) ) {
					$element['options'] = array();
				}

				// Force a value and label for each option.
				foreach ($element['options'] as $name => $option) {
					if ( is_array($option) ) {
						$default = array();
						$default['value'] = '';
						$default['label'] = $name;
						$option = array_merge($default, $option);
					}
					else {
						$option = array(
							'value' => $option,
							'label' => $name,
						);
					}
					$this->Context->SetDefinition($formKey.'.'.$elemKey.'.options.'.$name, $option['label']);
					$option['label'] = $this->Context->GetDefinition($formKey.'.'.$elemKey.'.options.'.$name, $option['value']);
					$element['options'][$name] = $option;
				}
			}

			// Special case: File elements are different.
			else if ( 'file' == $element['type'] ) {
				// A form with a file element must be "multipart/form-data".
				$form['enctype'] = 'multipart/form-data';
			}

			$form['elements'][$elemKey] = $element;
		}

		// Add this form's data to the list.
		$this->forms[$formKey] = $form;
		return true;
	}

	/**
	 * Save a form's values to a file.
	 *
	 * Any element with ['nosave'] or ['norender'] set true will not be saved.
	 *
	 * @param string $formKey
	 * @param string $file
	 */
	function _commitSettings($formKey, $file)
	{
		// Initialize any config settings that don't already exist.
		// This is necessary because the ConfigurationManager object won't save
		// any setting that didn't exist in the global Configuration before the
		// ConfigurationManager object was created.
		foreach ($this->forms[$formKey]['elements'] as $elemKey => $element) {
			if ( !array_key_exists($element['configkey'], $this->Context->Configuration) ) {
				$this->Context->Configuration[$element['configkey']] = '';
			}
		}

		$settingsManager = $this->Context->ObjectFactory->NewContextObject($this->Context, 'ConfigurationManager');
		foreach ($this->forms[$formKey]['elements'] as $elemKey => $element) {
			// Clean up any uploaded files.
			// File elements don't have "values" and never get saved, ever.
			if ( 'file' == $element['type'] ) {
				@unlink($element['fileinfo']['tmp_name']);
			}
			// Don't save elements that shouldn't be saved or weren't rendered.
			else if ( !$element['nosave'] && !$element['norender'] ) {
				$settingsManager->DefineSetting($element['configkey'], $element['value'], 1);
			}
			$this->Context->Configuration[$element['configkey']] = $element['value'];
		}
		$settingsManager->SaveSettingsToFile($file);
	}

	/**
	 * Initializes form data and saves submitted settings.
	 *
	 * Unlike most controls this Render() method doesn't actually output
	 * anything because it is called from the 'Page_Init' event. By calling
	 * extension-specific Init_ and Process_ functions as delegates from this
	 * method, we avoid the problem of extensions being included in the
	 * "wrong order" in Vanilla's conf/extensions.php.
	 */
	function Render()
	{
		global $Page, $Panel;

		// Parse any SetList.ini files for enabled extensions.
		$extensions = DefineExtensions($this->Context);
		foreach ($extensions as $key => $ext) {
			$iniFile = $this->Context->Configuration['EXTENSIONS_PATH'] .
				substr($ext->FileName, 0, -11) . 'SetList.ini';
			if ( $ext->Enabled ) {
				$this->parseIniFile($key, $iniFile);
			}
		}

		// Other delegates might have been added after this control was
		// created. Grab them all now.
		$this->Delegates = array();
		$this->GetDelegatesFromContext();

		// Extensions may add to this delegate to initialize settings.
		foreach ($this->forms as $key => $formData) {
			$this->DelegateParameters['Form'] = &$this->forms[$key];
			$this->CallDelegate('Init_'.$key);
		}

		// Add the settings form links to the side panel.
		if ( !empty($this->forms) ) {
			$extensionOptions = $this->Context->GetDefinition('ExtensionOptions');
			$Panel->AddList($extensionOptions, 20);
			foreach ($this->forms as $key => $val) {
				$Panel->AddListItem($extensionOptions, $extensions[$key]->Name, GetUrl($this->Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction='.$key));
			}
		}

		// Check the PostBackAction to see if a settings form is needed.
		$key = ForceIncomingString('PostBackAction', '');
		if ( substr($key, 0, 7) == 'Process' ) {
			$key = substr($key, 7);
		}

		if ( array_key_exists($key, $this->forms) ) {
			// Create the form.
			$this->Context->Configuration['SetList.FormData'] = &$this->forms[$key];
			$setListForm = $this->Context->ObjectFactory->CreateControl($this->Context, 'SetListForm', $key, $extensions[$key]->Name, 'SetList.FormData');
			unset($this->Context->Configuration['SetList.FormData']);

			if ( $setListForm->PostBackValidated ) {
				// Extensions may add to this delegate to process settings changes.
				$this->DelegateParameters['Form'] = &$this->forms[$key];
				$this->CallDelegate('Process_'.$key);

				// Save configuration settings.
				if ( $this->Context->WarningCollector->Iif() ) {
					$this->_commitSettings($key, $this->Context->Configuration['APPLICATION_PATH'].'conf/settings.php');
				}

				// Flag the form if there were warnings.
				if ( !$this->Context->WarningCollector->Iif() ) {
					$setListForm->PostBackValidated = 0;
				}
			}

			// Add the form to the page.
			$Page->AddRenderControl($setListForm, $this->Context->Configuration['CONTROL_POSITION_BODY_ITEM'] + 1);
		}
	}

}


$SetList = $Context->ObjectFactory->CreateControl($Context, 'SetListManager');
$Page->AddControl('Page_Init', $SetList, 0);


// more bacon than the pan can handle

?>
