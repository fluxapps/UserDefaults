<?php

namespace srag\Plugins\UserDefaults\Form;

use ilGroupParticipants;
use ilObject;
use ilObjGroup;
use ilTemplateException;
use srag\DIC\UserDefaults\Exception\DICException;
use srag\Plugins\UserDefaults\Access\Courses;
use srag\Plugins\UserDefaults\UserSearch\usrdefObj;

/**
 * Class ilContainerMultiSelectInputGUI
 *
 * @package srag\Plugins\UserDefaults\Form
 *
 * @author  Oskar Truffer <ot@studer-raimann.ch>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class ilContainerMultiSelectInputGUI extends ilMultiSelectSearchInput2GUI {

	/**
	 * @var string
	 */
	protected $container_type = Courses::TYPE_CRS;
    /**
     * @var bool
     */
	protected $with_parent = false;
    /**
     * @var bool
     */
    protected $with_members = false;


    /**
     * @param string $container_type
     * @param string $title
     * @param        $post_var
     * @param bool   $multiple
     *
     * @param bool   $with_parent
     *
     * @param bool   $with_members
     *
     * @throws DICException
     * @throws ilTemplateException
     */
	public function __construct($container_type, $title, $post_var, $multiple = true, $with_parent = false, $with_members = false) {
		$this->setContainerType($container_type);
		parent::__construct($title, $post_var, $multiple);
        $this->with_parent = $with_parent;
        $this->with_members = $with_members;
    }


    /**
     * @return string
     * @throws DICException
     */
	protected function getValueAsJson() {
        $result = array();
        if ($this->multiple) {
            $query = "SELECT obj_id, title FROM " . usrdefObj::TABLE_NAME . " WHERE type = '" . $this->getContainerType() . "' AND " .
                self::dic()->databaseCore()->in("obj_id", $this->getValue(), false, "integer");
            $res = self::dic()->databaseCore()->query($query);
            while ($row = self::dic()->databaseCore()->fetchAssoc($res)) {
                $title = $row["title"];
                if ($this->with_parent) {
                    $ref_id = array_shift(ilObject::_getAllReferences($row["obj_id"]));
                    $title = ilObject::_lookupTitle(ilObject::_lookupObjectId(self::dic()->repositoryTree()->getParentId($ref_id))) . ' » ' . $title;
                }
                $result[] = array( "id" => $row['obj_id'], "text" => $title );
            }
        } else {
            $query = "SELECT obj_id, title FROM " . usrdefObj::TABLE_NAME . " WHERE type = '" . $this->getContainerType() . "' AND " .
                self::dic()->databaseCore()->equals("obj_id", $this->getValue(),"integer");
            $res = self::dic()->databaseCore()->query($query);
            if ($row = self::dic()->databaseCore()->fetchAssoc($res)) {
                $title = $row["title"];
                if ($this->with_parent) {
                    $ref_id = array_shift(ilObject::_getAllReferences($row["obj_id"]));
                    $title = ilObject::_lookupTitle(ilObject::_lookupObjectId(self::dic()->repositoryTree()->getParentId($ref_id))) . ' » ' . $title;
                }
                if ($this->with_members && $this->getContainerType() == 'grp') {
                    $group = new ilObjGroup($row['obj_id'], false);
                    $part = ilGroupParticipants::_getInstanceByObjId($row['obj_id']);
                    $title = $title . ' (' . $part->getCountMembers() . '/' . ($group->getMaxMembers() == 0 ? '-' : $group->getMaxMembers()) . ')';
                }
                $result = ["id" => $row['obj_id'], "text" => $title];
            }
        }

		return json_encode($result);
	}


	/**
	 * @return mixed
	 */
	public function getValues() {
		return $this->value;
	}


	/**
	 * @param string $container_type
	 */
	public function setContainerType($container_type) {
		$this->container_type = $container_type;
	}


	/**
	 * @return string
	 */
	public function getContainerType() {
		return $this->container_type;
	}
}
