<?php

namespace Laneros\Portal\XF\Pub\Controller;

class Thread extends XFCP_Thread
{
	public function setupThreadEdit(\XF\Entity\Thread $thread)
	{
		/** @var \Laneros\Portal\XF\Service\Thread\Editor $editor */
		$editor = parent::setupThreadEdit($thread);

		$canFeatureUnfeature = $thread->canFeatureUnfeature();
		if ($canFeatureUnfeature)
		{
			$editor->setFeatureThread($this->filter('featured', 'bool'));
		}

		return $editor;
	}

	public function finalizeThreadReply(\XF\Service\Thread\Replier $replier)
	{
		parent::finalizeThreadReply($replier);

		$setOptions = $this->filter('_xfSet', 'array-bool');
		if ($setOptions)
		{
			$thread = $replier->getThread();

			if ($thread->canFeatureUnfeature() && isset($setOptions['featured']))
			{
				$replier->setFeatureThread($this->filter('featured', 'bool'));
			}
		}
	}
}