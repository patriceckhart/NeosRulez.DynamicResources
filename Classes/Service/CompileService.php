<?php
namespace NeosRulez\DynamicResources\Service;

use Neos\Flow\Annotations as Flow;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Formatter\Compressed;

/**
 * @Flow\Scope("singleton")
 */
class CompileService
{

    /**
     * @param string $resource
     * @return string
     */
    public function compileScss(string $resource): string
    {
        $file = file_get_contents($resource);

        $scss = new Compiler();
        $scss->setFormatter('ScssPhp\ScssPhp\Formatter\Compressed');

        $importPath = $this->getImportPaths($resource);
        $scss->setImportPaths($importPath);
        return $scss->compile($file);
    }

    /**
     * @param string $scssFile
     * @return array
     */
    private function getImportPaths(string $scssFile): array
    {
        $serverPath = constant('FLOW_PATH_PACKAGES');
        $realPath = dirname($scssFile);
        $realPath = str_replace('resource://','', $realPath);
        $part1 = substr($realPath, 0, strpos($realPath, '/'));
        $part2 = str_replace($part1,'', $realPath);
        return [
            $serverPath . 'Application/' .$part1 . '/Resources' . $part2,
            $serverPath . 'Framework/' .$part1 . '/Resources' . $part2,
            $serverPath . 'Libraries/' .$part1 . '/Resources' . $part2,
            $serverPath . 'Plugins/' .$part1 . '/Resources' . $part2,
            $serverPath . 'Sites/' .$part1 . '/Resources' . $part2
        ];
    }

}
