<?php
namespace NeosRulez\DynamicResources\Controller;

/*
 * This file is part of the NeosRulez.DynamicResources package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

class ResourceController extends ActionController {

    /**
     * @Flow\Inject
     * @var \NeosRulez\DynamicResources\Factory\MinifyFactory
     */
    protected $minifyFactory;

    /**
     * @Flow\Inject
     * @var \NeosRulez\DynamicResources\Service\CompileService
     */
    protected $compileService;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings) {
        $this->settings = $settings;
    }


    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function headStylesAction() {
        $resources = $this->settings['resources']['head']['styles'];
        $result = $this->buildStyles($resources);
        return $result;
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function headScriptsAction() {
        $resources = $this->settings['resources']['head']['scripts'];
        $result = $this->buildScripts($resources);
        return $result;
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function footerStylesAction() {
        $resources = $this->settings['resources']['footer']['styles'];
        $result = $this->buildStyles($resources);
        return $result;
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function footerScriptsAction() {
        $resources = $this->settings['resources']['footer']['scripts'];
        $result = $this->buildScripts($resources);
        return $result;
    }

    /**
     * @param array $resources
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function buildStyles($resources) {
        $result = '/*' . $this->getTstamp() . '*/';
        if(!empty($resources)) {
            foreach ($resources as $i => $resource) {
                $css = $this->compileService->compileScss($resource);
                $result .= $this->buildDeclaration($i) . $css;
            }
        }
        header('Content-Type: text/css');
        return $result;
    }

    /**
     * @param array $resources
     * @return string
     */
    public function buildScripts($resources) {
        $result = '/*' . $this->getTstamp() . '*/';
        if(!empty($resources)) {
            foreach ($resources as $i => $resource) {
                $result .= $this->buildDeclaration($i) . $this->minifyFactory->minifyScript($resource) . ';';
            }
        }
        $result .= ';';
        header('Content-Type: application/javascript');
        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    public function buildDeclaration($name) {
        $result = '/*NeosRulez.DynamicResources:' . $name . '*/';
        return $result;
    }

    /**
     * @return string
     */
    public function getTstamp() {
        $date = new \DateTime();
        return $date->getTimestamp();
    }

}
