<?php
namespace NeosRulez\DynamicResources\Service;

use Neos\Flow\Annotations as Flow;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Formatter\Compressed;

/**
 * @Flow\Scope("singleton")
 */
class CompileService {

    /**
     * @param string $resource
     * @return string
     */
    public function compileScss($resource) {
        $file = file_get_contents($resource);

        $scss = new Compiler();
        $scss->setFormatter('ScssPhp\ScssPhp\Formatter\Compressed');

        $importPath = $this->getImportPaths($resource);
        $scss->setImportPaths($importPath);
        $scss = $scss->compile($file);
        return $scss;
    }

    public function getImportPaths($scssFile) {
        $serverPath = constant('FLOW_PATH_ROOT');
        $realPath = dirname($scssFile);
        $realPath = str_replace('resource://','', $realPath);
        $part1 = substr($realPath, 0, strpos($realPath, '/'));
        $part2 = str_replace($part1,'', $realPath);
        $realPath1 = $serverPath.'Packages/Application/'.$part1.'/Resources'.$part2;
        $realPath2 = $serverPath.'Packages/Plugins/'.$part1.'/Resources'.$part2;
        $realPath3 = $serverPath.'Packages/Sites/'.$part1.'/Resources'.$part2;
        $result = array($realPath1,$realPath2,$realPath3);
        return $result;
    }

}