<?php

namespace Laneros\Portal;

use XF\Mvc\Entity\Entity;
use XF\Container;

class Listener
{
	public static function forumEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->columns['laneros_portal_auto_feature'] = ['type' => Entity::BOOL, 'default' => false];
	}

	public static function threadEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
	{
		$structure->columns['laneros_portal_featured'] = ['type' => Entity::BOOL, 'default' => false];

		$structure->relations['FeaturedThread'] = [
			'entity' => 'Laneros\Portal:FeaturedThread',
			'type' => Entity::TO_ONE,
			'conditions' => 'thread_id',
			'primary' => true
		];
	}

	public static function threadEntityPostSave(\XF\Mvc\Entity\Entity $entity)
	{
		if ($entity->isUpdate())
		{
			$visibilityChange = $entity->isStateChanged('discussion_state', 'visible');
			if ($visibilityChange == 'leave')
			{
				$featuredThread = $entity->FeaturedThread;
				if ($featuredThread)
				{
					$featuredThread->delete();
					$entity->fastUpdate('laneros_portal_featured', false);
				}
			}
		}
	}

	public static function threadEntityPostDelete(\XF\Mvc\Entity\Entity $entity)
	{
		$featuredThread = $entity->FeaturedThread;
		if ($featuredThread)
		{
			$featuredThread->delete();
		}
	}

	public static function homePageUrl(&$homePageUrl, \XF\Mvc\Router $router)
	{
		$homePageUrl = $router->buildLink('canonical:portal');
	}

	public static function app_setup(\XF\App $app)
	{
		$app->container()->set('prefixes.group', $app->fromRegistry('threadPrefixGroups', function (Container $c) {
			return $c['em']->getRepository('XF:ThreadPrefix')->rebuildPrefixGroupCache();
		}));

		$laneros = $app->container()->getOriginal('prefixes.group');

		return true;
	}
}