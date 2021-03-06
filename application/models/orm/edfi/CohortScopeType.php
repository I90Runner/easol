<?php

namespace Model\Edfi;

use \Gas\Core;
use \Gas\ORM;

class CohortScopeType extends ORM {

	public $table = "edfi.CohortScopeType";
	public $primary_key = 'CohortScopeTypeId';

	function _init() {

		self::$relationships = [
			'Cohort'=> ORM::has_many('\\Model\\Edfi\\Cohort'),
		];

		self::$fields = [
			'CohortScopeTypeId' => ORM::field('int[10]'),
			'CodeValue'         => ORM::field('char[50]'),
			'Description'       => ORM::field('char[1024]'),
			'ShortDescription'  => ORM::field('char[450]'),
			'Id'                => ORM::field('char[255]'),
			'LastModifiedDate'  => ORM::field('datetime'),
			'CreateDate'        => ORM::field('datetime'),
		];
	}
}
