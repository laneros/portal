<?php

namespace Laneros\Portal\XF\Pub\Controller;

class Thread extends XFCP_Thread
{
	protected function setupThreadEdit(\XF\Entity\Thread $thread)
	{
		/** @var \Laneros\Portal\XF\Entity\Thread $thread */
		/** @var \Laneros\Portal\XF\Service\Thread\Editor $editor */
		$editor = parent::setupThreadEdit($thread);

		$canFeatureUnfeature = $thread->canFeatureUnfeature();
		if ($canFeatureUnfeature)
		{
			$editor->setFeatureThread($this->filter('featured', 'bool'));
			$editor->setFeatureTitle($this->filter('featured_title', 'str'));
			$editor->setSnippet($this->filter('snippet', 'str'));
		}

		return $editor;
	}

    protected function finalizeThreadReply(\XF\Service\Thread\Replier $replier)
	{
		parent::finalizeThreadReply($replier);

		$setOptions = $this->filter('_xfSet', 'array-bool');
		if ($setOptions)
		{
			/** @var \Laneros\Portal\XF\Entity\Thread $thread */
			$thread = $replier->getThread();

			if ($thread->canFeatureUnfeature() && isset($setOptions['featured']))
			{
				$replier->setFeatureThread($this->filter('featured', 'bool'));
			}
		}
	}
}