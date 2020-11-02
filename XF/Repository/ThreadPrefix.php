<?php

namespace Laneros\Portal\XF\Repository;

class ThreadPrefix extends XFCP_ThreadPrefix
{
    public function rebuildPrefixGroupCache()
    {
        $prefixes = $this->finder($this->getClassIdentifier())
            ->fetch();

        $cache = [];
        foreach ($prefixes as $prefix) {
            $cache[$prefix->prefix_id] = $prefix->prefix_group_id;
        }
        \XF::registry()->set('ThreaPrefixGroups', $cache);
        return $cache;
    }
}
