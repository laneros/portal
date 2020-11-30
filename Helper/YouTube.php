<?php

namespace Laneros\Portal\Helper;

class YouTube
{
    public static function getThumbnail(string $videoId, string $size = 'maxres'): string
    {
        switch ($size) {
            case 'maxres':
            case 'sd':
            case 'mq':
            case 'hq':
                break;

            default:
                $size = 'maxres';
        }

        return sprintf('https://img.youtube.com/vi/%s/%sdefault.jpg', $videoId, $size);
    }
}
