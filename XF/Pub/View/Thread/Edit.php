<?php

namespace Laneros\Portal\XF\Pub\View\Thread;

class Edit extends XFCP_Edit
{
    public function renderHtml()
    {
        if (!isset($this->params['authors']) && !empty($this->params['authors'])) {
            /** @var \XF\Entity\Thread $thread */
            $thread = $this->params['thread'];
    
            $this->params['authors'] = $thread->username;
        }
    }
}
