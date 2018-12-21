<?php

namespace Laneros\Portal\Finder;

use XF\Mvc\Entity\Finder;

class FeaturedThread extends Finder
{
	public function applyFeaturedOrder($direction = 'ASC')
	{
		$options = \XF::options();

		if ($options->lanerosPortalDefaultSort == 'featured_date')
		{
			$this->setDefaultOrder('featured_date', $direction);
		}
		else
		{
			$this->setDefaultOrder('Thread.post_date', $direction);
		}

		return $this;
	}
}