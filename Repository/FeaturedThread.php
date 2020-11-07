<?php

namespace Laneros\Portal\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class FeaturedThread extends Repository
{
	/**
	 * @return Finder
	 */
	public function findFeaturedThreadsForPortalView()
	{
		$visitor = \XF::visitor();

		$finder = $this->finder('Laneros\Portal:FeaturedThread');
		$finder
			->applyFeaturedOrder('DESC')
			->with('Thread', true)
			->with('Thread.User')
			->with('Thread.Forum', true)
			->with('Thread.Forum.Node.Permissions|' . $visitor->permission_combination_id)
			->with('Thread.FirstPost', true)
			->with('Thread.FirstPost.User')
			->where('Thread.laneros_portal_featured', '=', '1')
			->where('Thread.discussion_type', '<>', 'redirect')
			->where('Thread.discussion_state', 'visible');

		return $finder;
	}
}