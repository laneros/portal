<?php

namespace Laneros\Portal\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

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

			/** @var \Laneros\Portal\Repository\FeaturedThread $featuredRepo */
			$featuredRepo = $this->app()->repository('Laneros\Portal:FeaturedThread');

			$authors = $this->filter('authors', 'str');
			$authors = $featuredRepo->getValidatedAuthors($authors);
			$editor->setAuthors($authors);
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

	public function actionFeaturedStick(ParameterBag $params)
	{
		$thread = $this->assertViewableThread($params->thread_id);
		if (!$thread->canStickUnstick($error)) {
			return $this->noPermission($error);
		}

		if ($this->isPost()) {
			/** @var \Laneros\Portal\XF\Service\Thread\Editor $editor */
			$editor = $this->getEditorService($thread);

			$sticky = $this->filter('featured_sticky', 'int');
			$sticky = empty($sticky) ? 0 : $sticky;

			$editor->setFeaturedSticky($sticky);

			if (!$editor->validate($errors)) {
				return $this->error($errors);
			}

			$editor->save();

			$reply = $this->redirect($this->getDynamicRedirect());
			return $reply;
		} else {
			/** @var \XF\Repository\Node $nodeRepo */
			$nodeRepo = $this->app()->repository('XF:Node');
			$nodes = $nodeRepo->getFullNodeList()->filterViewable();

			$viewParams = [
				'thread' => $thread
			];
			return $this->view('Laneros\Portal:Thread\FeaturedSticky', 'laneros_portal_thread_featured_sticky', $viewParams);
		}
	}

	public function actionEdit(ParameterBag $params)
	{
		$view = parent::actionEdit($params);

		/** @var \Laneros\Portal\XF\Entity\Thread $thread */
		$thread = $this->assertViewableThread($params->thread_id);

		if ($view instanceof \XF\Mvc\Reply\View && $thread->isFeatured()) {
			$view->setParam('authors', $thread->FeaturedThread->getAuthorsList());
		}

		return $view;
	}
}