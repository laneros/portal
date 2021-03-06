<?php

namespace Laneros\Portal\XF\Service\Thread;

class Editor extends XFCP_Editor
{
    protected $featureThread;
	protected $featuredTitle;
	protected $snippet;
	protected $featuredSticky;
	protected $authors;

    public function setFeatureThread($featureThread)
    {
        $this->featureThread = $featureThread;
    }

    public function setFeatureTitle($featuredTitle)
    {
        $this->featuredTitle = $featuredTitle;
	}

	public function setSnippet($snippet)
	{
		$this->snippet = $snippet;
	}

	public function setFeaturedSticky($sticky)
	{
		$this->featuredSticky = $sticky;
	}

	public function setAuthors($authors)
	{
		$this->authors = $authors;
	}

	protected function _save()
	{
		$thread = parent::_save();

		/** @var \Laneros\Portal\Entity\FeaturedThread $featuredThread */
		$featuredThread = $thread->getRelationOrDefault('FeaturedThread', false);

		if ($featuredThread->exists()) {
			//Check if we need to update the sticky status of an portal article
			if ($this->featuredSticky !== null) {
				$featuredThread->set('sticky', $this->featuredSticky);
			}
		}

		//This takes care of the Edit Thread modal
		if ($this->featureThread !== null && $thread->discussion_state == 'visible') {
			if ($this->featureThread)
			{
				if ($thread->laneros_portal_featured == false)
				{
                    $thread->fastUpdate('laneros_portal_featured', true);
                }

				$featuredThread->set('featured_title', $this->featuredTitle);
				$featuredThread->set('snippet', $this->snippet);
				$featuredThread->set('authors', $this->authors);
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

		if ($thread->laneros_portal_featured && !$featuredThread->isDeleted()) {
			$featuredThread->save();
		}
		
		return $thread;
	}
}