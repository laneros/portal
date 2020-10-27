<?php

namespace Laneros\Portal\Entity;

use XF\Mvc\Entity\Structure;

class FeaturedThread extends \XF\Mvc\Entity\Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_laneros_portal_featured_thread';
		$structure->shortName = 'XF:FeaturedThread';
		$structure->primaryKey = 'thread_id';
		$structure->columns = [
			'thread_id' => ['type' => self::UINT, 'required' => true],
			'featured_date' => ['type' => self::UINT, 'default' => time()],
			'featured_title' => ['type' => self::STR, 'default' => ''],
			'snippet' => ['type' => self::STR, 'default' => ''],
		];
		$structure->getters = [];
		$structure->relations = [
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => true
			],
		];

		return $structure;
	}
}