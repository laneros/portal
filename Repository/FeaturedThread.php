<?php

namespace Laneros\Portal\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\Util\Arr;

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

	public function getValidatedAuthors($authors, &$error = null)
    {
        $error = null;

        $authors = Arr::stringToArray($authors, '#\s*,\s*#');

        if (!count($authors)) {
			return [];			
        }

        /** @var \XF\Repository\User $userRepo */
        $userRepo = $this->repository('XF:User');
        $authors = $userRepo->getUsersByNames($authors, $notFound);

        if ($notFound) {
            $error = \XF::phraseDeferred(
                'laneros_portal_the_following_authors_could_not_be_found_x',
                ['names' => implode(', ', $notFound)]
            );
        }

        $userList = [];

        foreach ($authors as $user) {
            $userList[$user->user_id] = $user;
        }

        return $userList;
    }
}