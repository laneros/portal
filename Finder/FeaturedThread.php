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
			$this->setDefaultOrder([['sticky', 'DESC'], ['featured_date', $direction]]);
		}
		else
		{
			$this->setDefaultOrder([['sticky', 'DESC'], ['Thread.post_date', $direction]]);
		}

		return $this;
	}
}