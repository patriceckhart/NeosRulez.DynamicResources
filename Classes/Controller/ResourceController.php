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
     * @var \Neos\ContentRepository\Domain\Service\ContextFactoryInterface
     */
    protected $contextFactory;

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
     * @var \Neos\Cache\Frontend\StringFrontend
     * @Flow\Inject
     */
    protected $resourceCache;

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
        $this->response->setContentType('text/css');
        if ($this->resourceCache->has('compiledHeaderCss') && $_ENV['FLOW_CONTEXT'] == 'Production') {
            $result = $this->resourceCache->get('compiledHeaderCss');
        } else {
            if(array_key_exists($this->getCurrentSiteName(), $this->settings)) {
                if(array_key_exists('resources', $this->settings[$this->getCurrentSiteName()])) {
                    if(array_key_exists('head', $this->settings[$this->getCurrentSiteName()]['resources'])) {
                        if(array_key_exists('styles', $this->settings[$this->getCurrentSiteName()]['resources']['head'])) {
                            $resources = $this->settings[$this->getCurrentSiteName()]['resources']['head']['styles'];
                            $result = $this->buildStyles($resources);
                            $this->resourceCache->set('compiledHeaderCss', $result);
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function headScriptsAction() {
        $this->response->setContentType('application/javascript');
        if ($this->resourceCache->has('compiledHeaderJs') && $_ENV['FLOW_CONTEXT'] == 'Production') {
            $result = $this->resourceCache->get('compiledHeaderJs');
        } else {
            if(array_key_exists($this->getCurrentSiteName(), $this->settings)) {
                if (array_key_exists('resources', $this->settings[$this->getCurrentSiteName()])) {
                    if (array_key_exists('head', $this->settings[$this->getCurrentSiteName()]['resources'])) {
                        if (array_key_exists('scripts', $this->settings[$this->getCurrentSiteName()]['resources']['head'])) {
                            $resources = $this->settings[$this->getCurrentSiteName()]['resources']['head']['scripts'];
                            $result = $this->buildScripts($resources);
                            $this->resourceCache->set('compiledHeaderJs', $result);
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function footerStylesAction() {
        $this->response->setContentType('text/css');
        if ($this->resourceCache->has('compiledFooterCss') && $_ENV['FLOW_CONTEXT'] == 'Production') {
            $result = $this->resourceCache->get('compiledFooterCss');
        } else {
            if(array_key_exists($this->getCurrentSiteName(), $this->settings)) {
                if (array_key_exists('resources', $this->settings[$this->getCurrentSiteName()])) {
                    if (array_key_exists('footer', $this->settings[$this->getCurrentSiteName()]['resources'])) {
                        if (array_key_exists('styles', $this->settings[$this->getCurrentSiteName()]['resources']['footer'])) {
                            $resources = $this->settings[$this->getCurrentSiteName()]['resources']['footer']['styles'];
                            $result = $this->buildStyles($resources);
                            $this->resourceCache->set('compiledFooterCss', $result);
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function footerScriptsAction() {
        $this->response->setContentType('application/javascript');
        if ($this->resourceCache->has('compiledFooterJs') && $_ENV['FLOW_CONTEXT'] == 'Production') {
            $result = $this->resourceCache->get('compiledFooterJs');
        } else {
            if(array_key_exists($this->getCurrentSiteName(), $this->settings)) {
                if (array_key_exists('resources', $this->settings[$this->getCurrentSiteName()])) {
                    if (array_key_exists('footer', $this->settings[$this->getCurrentSiteName()]['resources'])) {
                        if (array_key_exists('scripts', $this->settings[$this->getCurrentSiteName()]['resources']['footer'])) {
                            $resources = $this->settings[$this->getCurrentSiteName()]['resources']['footer']['scripts'];
                            $result = $this->buildScripts($resources);
                            $this->resourceCache->set('compiledFooterJs', $result);
                        }
                    }
                }
            }
        }
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

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function getCurrentSiteName() {
        $context = $this->contextFactory->create();
        $currentSiteNode = $context->getCurrentSiteNode();
        return $currentSiteNode->getName();
    }

}
