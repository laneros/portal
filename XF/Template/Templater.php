<?php

namespace Laneros\Portal\XF\Template;

class Templater extends XFCP_Templater
{

    protected $myFunctions = [
        'prefix_class'          => 'fnPrefixClass',
        'prefix_group_from_id'  => 'fnPrefixGroupFromId',
    ];

    public function fnPrefixClass($templater, &$escape, $contentType, $prefixId)
    {
        $prefixGroup = $this->func('prefix_group_from_id', [$contentType, $prefixId], $escape);
        $prefixGroup = '<span class="label-append">' . $prefixGroup . '</span>';

        $prefix = parent::fnPrefix($templater, $escape, $contentType, $prefixId, 'html', $prefixGroup);

        if (empty($prefix)) {
            return $prefix;
        }

        $prefix = str_replace('<span', '<div', $prefix);
        $prefix = str_replace('span>', 'div>', $prefix);

        return $prefix;
    }

    public function fnPrefixGroupFromId($templater, &$escape, $contentType, $prefixId)
    {
        if (!is_int($prefixId)) {
            $prefixId = $prefixId->prefix_id;
        }

        if (!$prefixId) {
            return '';
        }

        $prefixCache = $this->app->container('prefixes.group');
        $prefixGroup = $prefixCache[$prefixId] ?? null;

        if (!$prefixGroup) {
            return '';
        }

        $output = $this->func('prefix_group', [$contentType, $prefixGroup], false);

        return $output;
    }

    public function addFunctions(array $functions)
    {
        return parent::addFunctions(array_merge($this->myFunctions, $functions));
    }
}
