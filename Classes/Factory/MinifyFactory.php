<?php
namespace NeosRulez\DynamicResources\Factory;

use Neos\Flow\Annotations as Flow;
use MatthiasMullie\Minify;

/**
 * @Flow\Scope("singleton")
 */
class MinifyFactory {

    /**
     * @param string $script
     * @return string
     */
    public function minifyScript($script) {
        $sourcePath = file_get_contents($script);
        $minifier = new Minify\JS($sourcePath);
        $result = $minifier->minify();
        return $result;
    }

}