<?php

namespace Laneros\Portal\XF\Service\Thread;

class Creator extends XFCP_Creator
{
	protected $featureThread;

	public function setFeatureThread($featureThread)
	{
		$this->featureThread = $featureThread;
	}

	protected function _save()
	{
		$thread = parent::_save();

		if ($this->featureThread && $thread->discussion_state == 'visible')
		{
			/** @var \Laneros\Portal\Entity\FeaturedThread $featuredThread */
			$featuredThread = $thread->getRelationOrDefault('FeaturedThread');
			$featuredThread->save();

			$thread->fastUpdate('laneros_portal_featured', true);
		}

		return $thread;
	}
}