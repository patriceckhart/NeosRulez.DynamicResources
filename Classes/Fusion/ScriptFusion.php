<?php
namespace NeosRulez\DynamicResources\Fusion;

use Neos\Flow\Annotations as Flow;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class ScriptFusion extends AbstractFusionObject {

    /**
     * @Flow\Inject
     * @var \NeosRulez\DynamicResources\Factory\MinifyFactory
     */
    protected $minifyFactory;

    /**
     * @return array
     */
    public function evaluate() {
        $resources = $this->fusionValue('resources');
        $result = '';
        if(!empty($resources)) {
            $scriptResources = explode('resource://', $resources);
            array_shift($scriptResources);
            foreach ($scriptResources as $resource) {
                $result .= $this->minifyFactory->minifyScript('resource://' . $resource);
            }
        }
        return $result;
    }

}