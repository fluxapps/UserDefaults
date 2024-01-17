<#1>
<?php

use srag\Plugins\UserDefaults\UserSetting\UserSetting;

\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#2>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#3>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#4>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#5>
<?php
//
?>
<#6>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#7>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#8>
<?php
/**
 * @var \srag\Plugins\UserDefaults\UserSetting\UserSetting $ilUserSetting
 */
foreach (\srag\Plugins\UserDefaults\UserSetting\UserSetting::get() as $ilUserSetting) {
	$ilUserSetting->setOnCreate(true);
	$ilUserSetting->setOnUpdate(false);
	$ilUserSetting->setOnManual(true);
	$ilUserSetting->update();
}
?>
<#9>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#10>
<?php
\srag\Plugins\UserDefaults\Config\UserDefaultsConfig::updateDB();
?>
<#11>
<?php
//
?>
<#12>
<?php
//
?>
<#13>
<?php
if (\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableExists(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME)) {
	\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::updateDB();

	if(\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableColumnExists(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME,"udf_field_id")
		&& \srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableColumnExists(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME,"field_key")) {
		\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->dropTableColumn(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME,"field_key");
	}

	\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()
		->renameTableColumn(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME, "udf_field_id", "field_key");

	\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->modifyTableColumn(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME, "field_key", [
		"type" => "text"
	]);
}
?>
<#14>
<?php
if (\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableExists(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME)) {
	\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::updateDB();

	/**
	 * @var \srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld $UDFCheckOld
	 */
	foreach (\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::where([
		"field_category" => 0
	])->get() as $UDFCheckOld) {
		$UDFCheckOld->setFieldCategory(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckUDF::FIELD_CATEGORY);
		$UDFCheckOld->store();
	}
}
?>
<#15>
<?php
if (\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableExists(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME)) {
	\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::updateDB();

	\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->modifyTableColumn(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME, "field_key", [
		"type" => "text",
		"length" => "256"
	]);
}
?>
<#16>
<?php
\srag\Plugins\UserDefaults\UDFCheck\UDFCheckUser::updateDB();
\srag\Plugins\UserDefaults\UDFCheck\UDFCheckUDF::updateDB();

if (\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableExists(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME)) {
	\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::updateDB();

	/**
	 * @var \srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld $UDFCheckOld
	 */
	foreach (\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::get() as $UDFCheckOld) {
		$UDFCheck = \srag\Plugins\UserDefaults\UDFCheck\UDFCheck::newInstance($UDFCheckOld->getFieldCategory());

		$UDFCheck->setParentId($UDFCheckOld->getParentId());
		$UDFCheck->setFieldKey($UDFCheckOld->getFieldKey());
		$UDFCheck->setCheckValue($UDFCheckOld->getCheckValue());
		$UDFCheck->setOperator($UDFCheckOld->getOperator());
		$UDFCheck->setNegated($UDFCheckOld->isNegated());
		$UDFCheck->setOwner($UDFCheckOld->getOwner());
		$UDFCheck->setStatus($UDFCheckOld->getStatus());
		$UDFCheck->setCreateDate($UDFCheckOld->getCreateDate());
		$UDFCheck->setUpdateDate($UDFCheckOld->getUpdateDate());

		$UDFCheck->store();
	}

	\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->dropTable(\srag\Plugins\UserDefaults\UDFCheck\UDFCheckOld::TABLE_NAME, false);
}
?>
<#17>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#18>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#19>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#20>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#21>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
$usr_setting_table = \srag\Plugins\UserDefaults\UserSetting\UserSetting::TABLE_NAME;
if ($ilDB->tableColumnExists($usr_setting_table, 'global_role')) {
	$ilDB->query('UPDATE ' . $usr_setting_table . ' SET global_roles = CONCAT("[", global_role, "]") WHERE true');
	$ilDB->dropTableColumn($usr_setting_table, 'global_role');
}
?>
<#22>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#23>
<?php
if (\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableColumnExists(\srag\Plugins\UserDefaults\UserSetting\UserSetting::TABLE_NAME, "assigned_courses_desktop"))
    \srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->dropTableColumn(\srag\Plugins\UserDefaults\UserSetting\UserSetting::TABLE_NAME, "assigned_courses_desktop");

if (\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableColumnExists(\srag\Plugins\UserDefaults\UserSetting\UserSetting::TABLE_NAME, "assigned_categories_desktop"))
    \srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->dropTableColumn(\srag\Plugins\UserDefaults\UserSetting\UserSetting::TABLE_NAME, "assigned_categories_desktop");

if (\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableColumnExists(\srag\Plugins\UserDefaults\UserSetting\UserSetting::TABLE_NAME, "assigned_groupes_desktop"))
    \srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->dropTableColumn(\srag\Plugins\UserDefaults\UserSetting\UserSetting::TABLE_NAME, "assigned_groupes_desktop");
?>
<#24>
<?php
if (!\srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->tableColumnExists(\srag\Plugins\UserDefaults\UserSetting\UserSetting::TABLE_NAME, "unsubscr_from_grp"))
    \srag\DIC\UserDefaults\DICStatic::dic()->databaseCore()->addTableColumn(\srag\Plugins\UserDefaults\UserSetting\UserSetting::TABLE_NAME, "unsubscr_from_grp", ["type" => "integer"]);
?>
<#25>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#26>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>
<#27>
<?php
\srag\Plugins\UserDefaults\UserSetting\UserSetting::updateDB();
?>