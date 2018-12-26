<?php

namespace Laneros\Portal\Pub\View\Portal;

class View extends \XF\Mvc\View
{
    public function renderHtml()
    {
        $router = \XF::app()->router('public');

        /** @var \Laneros\Portal\Repository\FeaturedThread $thread */
        foreach($this->params['featuredThreads'] AS $key => &$thread)
        {
            $message = $thread->Thread->FirstPost->message;

            // Sacamos la primra imagen del post sea de IMG o ATTACH
            preg_match('/\[(img|IMG)\]\s*(https?:\/\/([^*\r\n]+|[a-z0-9\/\\\._\- !]+))\[\/(img|IMG)\]/', $message, $matches);

            $this->params['portalImages'][$key] = isset($matches[2]) ? $matches[2] : '';

            if (empty($thread['portalImages']))
            {
                preg_match_all('#\[attach[^\]]*\](?P<id>\d+)(\D.*)?\[/attach\]#iU', $message, $matches);

                if (empty($matches['id'])) {
                    // No encontramos una imagen
                    // @TODO: No debemos eliminarla sino mostrar un placeholder
                    //unset($this->params['featuredThreads'][$key]);
                    continue;
                } else {
                    $this->params['portalImages'][$key] = $router->buildLink('full:attachments', ['attachment_id' => $matches['id'][0]]);
                }
            }
        }
    }
}
