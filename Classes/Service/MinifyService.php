<?php
namespace NeosRulez\DynamicResources\Service;

use Neos\Flow\Annotations as Flow;
use MatthiasMullie\Minify;

/**
 * @Flow\Scope("singleton")
 */
class MinifyService
{

    /**
     * @param string $script
     * @return string
     */
    public function minifyScript(string $script): string
    {
        $sourcePath = file_get_contents($script);
        $minifier = new Minify\JS($sourcePath);
        return $minifier->minify();
    }

}
