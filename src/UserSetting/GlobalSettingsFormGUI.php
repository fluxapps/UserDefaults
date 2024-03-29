<?php

namespace srag\Plugins\UserDefaults\UserSetting;

use ilNumberInputGUI;
use ilPropertyFormGUI;
use ilUserDefaultsPlugin;
use srag\DIC\UserDefaults\DICTrait;
use srag\Plugins\UserDefaults\Config\UserDefaultsConfig;
use srag\Plugins\UserDefaults\Utils\UserDefaultsTrait;
use UserDefaultsGlobalSettingsGUI;

/**
 * Class GlobalSettingsFormGUI
 *
 * @package srag\Plugins\UserDefaults\UserSetting
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class GlobalSettingsFormGUI extends ilPropertyFormGUI {

	use DICTrait;
	use UserDefaultsTrait;
	const PLUGIN_CLASS_NAME = ilUserDefaultsPlugin::class;
	/**
	 * @var UserDefaultsGlobalSettingsGUI
	 */
	protected $parent;


	/**
	 * GlobalSettingsFormGUI constructor
	 *
	 * @param UserDefaultsGlobalSettingsGUI $parent
	 */
	public function __construct(UserDefaultsGlobalSettingsGUI $parent) {
		parent::__construct();

		$this->parent = $parent;

		$this->initForm();
	}


	/**
	 *
	 */
	protected function initForm() {
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent));

		$this->setTitle(self::plugin()->translate("tabs_global_settings"));

		$this->addCommandButton(UserDefaultsGlobalSettingsGUI::CMD_UPDATE_CONFIGURE, self::plugin()->translate("save"));

		$category_ref_id = new ilNumberInputGUI(self::plugin()->translate(UserDefaultsConfig::KEY_CATEGORY_REF_ID), UserDefaultsConfig::KEY_CATEGORY_REF_ID);
		$category_ref_id->setInfo(self::plugin()->translate(UserDefaultsConfig::KEY_CATEGORY_REF_ID . "_info"));
		$category_ref_id->setValue(UserDefaultsConfig::getField(UserDefaultsConfig::KEY_CATEGORY_REF_ID));
		$this->addItem($category_ref_id);
	}


	/**
	 *
	 */
	public function updateConfigure() {
		$category_ref_id = $this->getInput(UserDefaultsConfig::KEY_CATEGORY_REF_ID);
		UserDefaultsConfig::setField(UserDefaultsConfig::KEY_CATEGORY_REF_ID, $category_ref_id);
	}
}
