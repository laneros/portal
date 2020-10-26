<?php

namespace Laneros\Portal\XF\Admin\Controller;

use XF\Mvc\FormAction;

class Forum extends XFCP_Forum
{
	protected function saveTypeData(FormAction $form, \XF\Entity\Node $node, \XF\Entity\AbstractNode $data)
	{
		parent::saveTypeData($form, $node, $data);

		$form->setup(function() use ($data)
		{
			$data->laneros_portal_auto_feature = $this->filter('laneros_portal_auto_feature', 'bool');
		});
	}
}