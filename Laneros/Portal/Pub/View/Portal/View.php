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

        $formatter = new Formatter();

        /** @var \Laneros\Portal\Repository\FeaturedThread $featuredThread */
        foreach($this->params['featuredThreads'] AS $key => &$featuredThread)
        {
            $message = $featuredThread->Thread->FirstPost->message;

            // Sacamos la primra imagen del post sea de IMG o ATTACH
            preg_match('/\[(img|IMG)\]\s*(https?:\/\/([^*\r\n]+|[a-z0-9\/\\\._\- !]+))\[\/(img|IMG)\]/', $message, $matches);

            $this->params['portalImages'][$key] = isset($matches[2]) ? $matches[2] : '';

            // Limpiamos el mensaje de bbcodes / imagenes / espacios
            $featuredThread->Thread->FirstPost->message = $formatter->stripBbCode($message, ['stripQuote' => true, ]);

            if (empty($featuredThread['portalImages']))
            {
                preg_match_all('#\[attach[^\]]*\](?P<id>\d+)(\D.*)?\[/attach\]#iU', $message, $matches);

                if (! empty($matches['id'])) {
                    $this->params['portalImages'][$key] = $router->buildLink('full:attachments', ['attachment_id' => $matches['id'][0]]);
                }
            }

            // Eliminamos todos los tags innecesarios del mensaje
            $stripBbCodeOptions = [
                'stripQuote' => true,
            ];

            $featuredThread->Thread->FirstPost->message = $stringFormatter->stripBbCode($message, $stripBbCodeOptions);

            if (! empty($featuredThread->featured_title))
            {
                $featuredThread->Thread->title = $featuredThread->featured_title;
            }
        }
    }
}
