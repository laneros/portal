<?php

namespace Laneros\Portal\XF\Pub\Controller;

class Forum extends XFCP_Forum
{
    protected function setupThreadCreate(\XF\Entity\Forum $forum)
	{
		/** @var \Laneros\Portal\XF\Service\Thread\Creator $creator */
		$creator = parent::setupThreadCreate($forum);

		if ($forum->laneros_portal_auto_feature)
		{
			$creator->setFeatureThread(true);
		}
		else
		{
			$setOptions = $this->filter('_xfSet', 'array-bool');
			if ($setOptions)
			{
				$thread = $creator->getThread();

				if ($thread->canFeatureUnfeature() && isset($setOptions['featured']))
				{
					$creator->setFeatureThread($this->filter('featured', 'bool'));
				}
			}
		}

		return $creator;
	}
}