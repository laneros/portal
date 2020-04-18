<?php

namespace Laneros\Portal\XF\Service\Thread;

class Editor extends XFCP_Editor
{
    protected $featureThread;
    protected $featuredTitle;

    public function setFeatureThread($featureThread)
    {
        $this->featureThread = $featureThread;
    }

    public function setFeatureTitle($featuredTitle)
    {
        $this->featuredTitle = $featuredTitle;
    }

	protected function _save()
	{
		$thread = parent::_save();

		if ($this->featureThread !== null && $thread->discussion_state == 'visible')
		{
			/** @var \Laneros\Portal\Entity\FeaturedThread $featuredThread */
			$featuredThread = $thread->getRelationOrDefault('FeaturedThread', false);

			if ($this->featureThread)
			{
				if (!$featuredThread->exists())
				{
                    $thread->fastUpdate('laneros_portal_featured', true);
                }

                $featuredThread->set('featured_title', $this->featuredTitle);
                $featuredThread->save();
            }
			else
			{
				if ($featuredThread->exists())
				{
					$featuredThread->delete();
					$thread->fastUpdate('laneros_portal_featured', false);
				}
			}
		}

		return $thread;
	}
}