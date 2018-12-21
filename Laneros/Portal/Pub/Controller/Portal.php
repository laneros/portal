<?php

namespace Laneros\Portal\Pub\Controller;

class Portal extends \XF\Pub\Controller\AbstractController
{
	public function actionIndex()
	{
		$page = $this->filterPage();
		$perPage = $this->options()->lanerosPortalFeaturedPerPage;

		/** @var \Laneros\Portal\Repository\FeaturedThread $repo */
		$repo = $this->repository('Laneros\Portal:FeaturedThread');

		$finder = $repo->findFeaturedThreadsForPortalView()
			->limitByPage($page, $perPage);

		$featuredThreads = $finder->fetch()
			->filter(function(\Laneros\Portal\Entity\FeaturedThread $featuredThread)
			{
				return ($featuredThread->Thread->canView());
			})
			->slice(0, $perPage, true);

		$threads = $featuredThreads->pluckNamed('Thread');
		$posts = $threads->pluckNamed('FirstPost', 'first_post_id');

		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = $this->repository('XF:Attachment');
		$attachRepo->addAttachmentsToContent($posts, 'post');

		$viewParams = [
			'featuredThreads' => $featuredThreads,
			'total' => $finder->total(),
			'page' => $page,
			'perPage' => $perPage
		];
		return $this->view('Laneros\Portal:View', 'laneros_portal_view', $viewParams);
	}
}