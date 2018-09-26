<?php

namespace srag\Plugins\UserDefaults\UDFCheck;

use ilCheckboxInputGUI;
use ilCustomUserFieldsHelper;
use ilHiddenInputGUI;
use ilPropertyFormGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilTextInputGUI;
use UDFCheckGUI;
use ilUDFDefinitionPlugin;
use ilUserDefaultsPlugin;
use UserSettingsGUI;
use srag\DIC\DICTrait;

/**
 * Class UDFCheckFormGUI
 *
 * @package srag\Plugins\UserDefaults\UDFChec
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class UDFCheckFormGUI extends ilPropertyFormGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilUserDefaultsPlugin::class;
	const F_UDF_FIELD_ID = 'udf_field_id';
	const F_CHECK_VALUE = 'check_value';
	const F_CHECK_VALUE_MUL = 'check_value_mul_';
	const F_UDF_NEGATE_ID = 'udf_negate_value';
	const F_UDF_OPERATOR = 'udf_operator';
	const F_CHECK_RADIO = 'check_radio';
	const F_CHECK_TEXT = 'check_text';
	const F_CHECK_SELECT = 'check_select';
	const ILIAS_VERSION_5_2 = 2;
	/**
	 * @var UserSettingsGUI
	 */
	protected $parent_gui;
	/**
	 * @var UDFCheck
	 */
	protected $object;
	/**
	 * @var bool
	 */
	protected $is_new = true;


	/**
	 * @param UDFCheckGUI $parent_gui
	 * @param UDFCheck    $ilUDFCheck
	 */
	public function __construct(UDFCheckGUI $parent_gui, UDFCheck $ilUDFCheck) {
		parent::__construct();

		$this->parent_gui = $parent_gui;
		$this->object = $ilUDFCheck;
		$this->is_new = $ilUDFCheck->getId() == 0;

		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent_gui));
		$this->initForm();
	}


	/**
	 * @param $key
	 *
	 * @return string
	 */
	protected function txt($key) {
		return self::plugin()->translate( $key,'check');
	}


	protected function initForm() {
		$this->setTitle(self::plugin()->translate('form_title'));
		$te = new ilSelectInputGUI($this->txt(self::F_UDF_FIELD_ID), self::F_UDF_FIELD_ID);
		$te->setDisabled(!$this->is_new);
		$te->setRequired(true);
		$te->setOptions(UDFCheck::getDefinitionData());
		$this->addItem($te);

		if (!$this->is_new) {
			$te = new ilHiddenInputGUI($this->txt(self::F_UDF_FIELD_ID), self::F_UDF_FIELD_ID); // TODO Fix PostVar
			$this->addItem($te);

			$cb = new ilCheckboxInputGUI($this->txt(self::F_UDF_NEGATE_ID), self::F_UDF_NEGATE_ID);
			$cb->setInfo($this->txt(self::F_UDF_NEGATE_ID . "_info"));
			$this->addItem($cb);

			$op = new ilSelectInputGUI($this->txt(self::F_UDF_OPERATOR), self::F_UDF_OPERATOR);
			$op->setInfo(self::plugin()->translate('check_op_reg_ex_info'));
			$options = array();
			foreach (UDFCheck::$operator_text_keys as $key => $v) {
				$options[$key] = self::plugin()->translate("check_op_" . $v);
			}
			$op->setOptions($options);
			$this->addItem($op);

			$udf_type = UDFCheck::getDefinitionTypeForId($this->object->getUdfFieldId());
			$definition = UDFCheck::getDefinitionForId($udf_type);

			switch ($udf_type) {
				case UDFCheck::TYPE_TEXT:
					$se = new ilTextInputGUI(self::plugin()->translate(self::F_CHECK_VALUE), self::F_CHECK_VALUE);
					$this->addItem($se);
					break;
				case UDFCheck::TYPE_SELECT:
					$se = new ilSelectInputGUI(self::plugin()->translate(self::F_CHECK_VALUE), self::F_CHECK_VALUE);
					$se->setOptions(UDFCheck::getDefinitionValuesForId($this->object->getUdfFieldId()));
					$this->addItem($se);
					break;
				default:

					//Do not use ilCustomUserFieldsHelper for ILIAS 5.2 - bebause it's not available
					if ($this->isCustomUserFieldsHelperAvailable()) {
						require_once "./Services/User/classes/class.ilCustomUserFieldsHelper.php";
						$plugin = ilCustomUserFieldsHelper::getInstance()->getPluginForType($udf_type);
						if ($plugin instanceof ilUDFDefinitionPlugin) {

							$select_gui = $plugin->getFormPropertyForDefinition($definition);

							$check_radio = new ilRadioGroupInputGUI("", self::F_CHECK_RADIO);

							$check_radio_text = new ilRadioOption(self::plugin()->translate("check_text_fields"), self::F_CHECK_TEXT);
							$check_radio->addOption($check_radio_text);

							foreach ($select_gui->getColumnDefinition() as $key => $name) {
								$text_gui = new ilTextInputGUI($name, self::F_CHECK_VALUE_MUL . $key);
								$check_radio_text->addSubItem($text_gui);
							}

							$check_radio_select = new ilRadioOption(self::plugin()->translate("check_select_lists"), self::F_CHECK_SELECT);
							$check_radio->addOption($check_radio_select);

							$select_gui->setPostVar(self::F_CHECK_VALUE);
							$select_gui->setRequired(false);
							$check_radio_select->addSubItem($select_gui);

							$this->addItem($check_radio);
						}
					}
					break;
			}
		}

		$this->addCommandButtons();
	}


	public function fillForm() {
		$array = array(
			self::F_UDF_FIELD_ID => $this->object->getUdfFieldId(),
			self::F_CHECK_VALUE => $this->object->getCheckValue(),
			self::F_UDF_NEGATE_ID => $this->object->isNegated(),
			self::F_UDF_OPERATOR => $this->object->getOperator(),
			self::F_CHECK_RADIO => self::F_CHECK_TEXT
		);

		$udf_type = UDFCheck::getDefinitionTypeForId($this->object->getUdfFieldId());
		$definition = UDFCheck::getDefinitionForId($udf_type);

		//DHBW Spec
		if ($this->isCustomUserFieldsHelperAvailable()) {
			require_once "./Services/User/classes/class.ilCustomUserFieldsHelper.php";
			$plugin = ilCustomUserFieldsHelper::getInstance()->getPluginForType($udf_type);
			if ($plugin instanceof ilUDFDefinitionPlugin) {
				$select_gui = $plugin->getFormPropertyForDefinition($definition);

				$check_values = $this->object->getCheckValues();
				foreach ($select_gui->getColumnDefinition() as $key => $name) {
					$array[self::F_CHECK_VALUE_MUL . $key] = $check_values[$key];
				}
			}
		}

		$this->setValuesByArray($array);
	}


	/**
	 * @return bool
	 */
	public function saveObject() {
		if (!$this->checkInput()) {
			return false;
		}

		if (!$this->is_new) {
			$check_radio = $this->getInput(self::F_CHECK_RADIO);
			switch ($check_radio) {
				case self::F_CHECK_TEXT:
					$udf_type = UDFCheck::getDefinitionTypeForId($this->object->getUdfFieldId());
					$definition = UDFCheck::getDefinitionForId($udf_type);

					//DHBW Spec
					if ($this->isCustomUserFieldsHelperAvailable()) {
						require_once "./Services/User/classes/class.ilCustomUserFieldsHelper.php";
						$plugin = ilCustomUserFieldsHelper::getInstance()->getPluginForType($udf_type);
						if ($plugin instanceof ilUDFDefinitionPlugin) {
							$select_gui = $plugin->getFormPropertyForDefinition($definition);
							$check_values = [];
							foreach ($select_gui->getColumnDefinition() as $key => $name) {
								$check_values[] = $this->getInput(self::F_CHECK_VALUE_MUL . $key);
							}
							$this->object->setCheckValues($check_values);
						}
					}
					break;
				case self::F_CHECK_SELECT:
				default:
					$this->object->setCheckValue($this->getInput(self::F_CHECK_VALUE));
					break;
			}
			$this->object->setNegated($this->getInput(self::F_UDF_NEGATE_ID));
			$this->object->setOperator($this->getInput(self::F_UDF_OPERATOR));
			$this->object->update();
		} else {
			$this->object->setUdfFieldId($this->getInput(self::F_UDF_FIELD_ID));
			$this->object->setParentId($_GET[UserSettingsGUI::IDENTIFIER]);
			$this->object->create();
		}

		return $this->object->getId();
	}


	protected function addCommandButtons() {
		if (!$this->is_new) {
			$this->addCommandButton(UDFCheckGUI::CMD_UPDATE, self::plugin()->translate('form_button_update'));
		} else {
			$this->addCommandButton(UDFCheckGUI::CMD_CREATE, self::plugin()->translate('form_button_create'));
		}
		$this->addCommandButton(UDFCheckGUI::CMD_CANCEL, self::plugin()->translate('form_button_cancel'));
	}


	/**
	 * @return bool
	 *
	 * CustomUserFieldsHelper is only available for ILIAS 5.3 and above!
	 */
	private function isCustomUserFieldsHelperAvailable() {
		return file_exists("./Services/User/classes/class.ilCustomUserFieldsHelper.php");
	}
}