<?php

namespace Laneros\Portal\XF\Entity;

class Thread extends XFCP_Thread
{
	public function canFeatureUnfeature()
	{
		return \XF::visitor()->hasNodePermission($this->node_id, 'lanerosPortalFeature');
	}
}