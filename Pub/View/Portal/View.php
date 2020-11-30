<?php

namespace Laneros\Portal\Pub\View\Portal;

use XF\Str\Formatter;

class View extends \XF\Mvc\View
{
    public function renderHtml()
    {
        $app = \XF::app();
        $stringFormatter = $app->stringFormatter();

        /** @var \Laneros\Portal\Repository\FeaturedThread $featuredThread */
        $featuredThreads = $this->getParams();
        foreach ($this->params['featuredThreads'] as $postId => &$featuredThread)
        {
            $message = $featuredThread->Thread->FirstPost->message;

            $this->params['portalImages'][$postId] = $this->extractPortalImage($message);

            $featuredThread->Thread->FirstPost->message = $this->generateSnippet($featuredThread, $stringFormatter);

            if (is_array($featuredThread->authors) && sizeof($featuredThread->authors) > 0) {
                $featuredThread->Thread->username = implode(', ', $featuredThread->authors);
            }
        }
    }

    protected function extractPortalImage(string $message): string
    {
        $router = \XF::app()->router('public');

        // Extract the first post image wether it's from a IMG or ATTACH tag
        preg_match('/\[(img|IMG)\]\s*(https?:\/\/([^*\r\n]+|[a-z0-9\/\\\._\- !]+))\[\/(img|IMG)\]/', $message, $matches);

        $portalImage = isset($matches[2]) ? $matches[2] : '';

        if (empty($portalImage)) {
            preg_match_all('#\[attach[^\]]*\](?P<id>\d+)(\D.*)?\[/attach\]#iU', $message, $matches);

            if (!empty($matches['id'])) {
                $portalImage = $router->buildLink('full:attachments', ['attachment_id' => $matches['id'][0]]);
            }
        }

        return $portalImage;
    }

    protected function generateSnippet(\Laneros\Portal\Entity\FeaturedThread $featuredThread, \XF\Str\Formatter $formatter): string
    {
        $snippet = \XF::app()->options()->lanerosPortalSnippet;

        $message = $featuredThread->Thread->FirstPost->message;

        if (!empty($featuredThread->snippet)) {
            $message = $featuredThread->snippet;
        }

        if ($snippet['enabled'] == true) {
            $message = $formatter->stripBbCode($message, ['stripQuote' => true,]);
        } else {
            $message = '';
        }

        return $message;
    }
}
