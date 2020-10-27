<?php

namespace Laneros\Portal\Pub\View\Portal;

use XF\Str\Formatter;

class View extends \XF\Mvc\View
{
    public function renderHtml()
    {
        $app = \XF::app();
        $stringFormatter = $app->stringFormatter();

        $router = \XF::app()->router('public');

        $snippet = $app->options()->lanerosPortalSnippet;

        $formatter = new Formatter();

        /** @var \Laneros\Portal\Repository\FeaturedThread $featuredThread */
        $featuredThreads = $this->getParams();
        foreach ($this->params['featuredThreads'] as $postId => &$featuredThread)
        {
            $message = $featuredThread->Thread->FirstPost->message;

            // Sacamos la primra imagen del post sea de IMG o ATTACH
            preg_match('/\[(img|IMG)\]\s*(https?:\/\/([^*\r\n]+|[a-z0-9\/\\\._\- !]+))\[\/(img|IMG)\]/', $message, $matches);

            $this->params['portalImages'][$postId] = isset($matches[2]) ? $matches[2] : '';

            // Limpiamos el mensaje de bbcodes / imagenes / espacios
            $featuredThread->Thread->FirstPost->message = $formatter->stripBbCode($message, ['stripQuote' => true, ]);

            if (empty($featuredThread['portalImages']))
            {
                preg_match_all('#\[attach[^\]]*\](?P<id>\d+)(\D.*)?\[/attach\]#iU', $message, $matches);

                if (! empty($matches['id'])) {
                    $this->params['portalImages'][$postId] = $router->buildLink('full:attachments', ['attachment_id' => $matches['id'][0]]);
                }
            }

            // Eliminamos todos los tags innecesarios del mensaje
            $stripBbCodeOptions = [
                'stripQuote' => true,
            ];

            if ($snippet['enabled'] == true) {
                $featuredThread->Thread->FirstPost->message = $stringFormatter->stripBbCode($message, $stripBbCodeOptions);
            } else {
                $featuredThread->Thread->FirstPost->message = '';
            }
        }
    }
}
