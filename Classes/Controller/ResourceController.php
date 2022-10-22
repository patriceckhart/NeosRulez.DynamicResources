<?php
namespace NeosRulez\DynamicResources\Controller;

/*
 * This file is part of the NeosRulez.DynamicResources package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use NeosRulez\DynamicResources\Service\MinifyService;
use NeosRulez\DynamicResources\Service\CompileService;
use Neos\Cache\Frontend\StringFrontend;

class ResourceController extends ActionController
{

    /**
     * @Flow\Inject
     * @var ContextFactoryInterface
     */
    protected $contextFactory;

    /**
     * @Flow\Inject
     * @var MinifyService
     */
    protected $minifyService;

    /**
     * @Flow\Inject
     * @var CompileService
     */
    protected $compileService;

    /**
     * @var StringFrontend
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
    public function injectSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function headStylesAction(): string
    {
        $this->response->setContentType('text/css');
        if ($this->resourceCache->has('compiledHeaderCss') && $this->getContext() == 'Production') {
            return $this->resourceCache->get('compiledHeaderCss');
        } else {
            if(array_key_exists($this->getCurrentSiteName(), $this->settings)) {
                if(array_key_exists('resources', $this->settings[$this->getCurrentSiteName()])) {
                    if(array_key_exists('head', $this->settings[$this->getCurrentSiteName()]['resources'])) {
                        if(array_key_exists('styles', $this->settings[$this->getCurrentSiteName()]['resources']['head'])) {
                            $resources = $this->settings[$this->getCurrentSiteName()]['resources']['head']['styles'];
                            $result = $this->buildStyles($resources);
                            $this->resourceCache->set('compiledHeaderCss', $result);
                            return $result;
                        }
                    }
                }
            }
        }
        return '';
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function headScriptsAction(): string
    {
        $this->response->setContentType('application/javascript');
        if ($this->resourceCache->has('compiledHeaderJs') && $this->getContext() == 'Production') {
            return $this->resourceCache->get('compiledHeaderJs');
        } else {
            if(array_key_exists($this->getCurrentSiteName(), $this->settings)) {
                if (array_key_exists('resources', $this->settings[$this->getCurrentSiteName()])) {
                    if (array_key_exists('head', $this->settings[$this->getCurrentSiteName()]['resources'])) {
                        if (array_key_exists('scripts', $this->settings[$this->getCurrentSiteName()]['resources']['head'])) {
                            $resources = $this->settings[$this->getCurrentSiteName()]['resources']['head']['scripts'];
                            $result = $this->buildScripts($resources);
                            $this->resourceCache->set('compiledHeaderJs', $result);
                            return $result;
                        }
                    }
                }
            }
        }
        return '';
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function footerStylesAction(): string
    {
        $this->response->setContentType('text/css');
        if ($this->resourceCache->has('compiledFooterCss') && $this->getContext() == 'Production') {
            return $this->resourceCache->get('compiledFooterCss');
        } else {
            if(array_key_exists($this->getCurrentSiteName(), $this->settings)) {
                if (array_key_exists('resources', $this->settings[$this->getCurrentSiteName()])) {
                    if (array_key_exists('footer', $this->settings[$this->getCurrentSiteName()]['resources'])) {
                        if (array_key_exists('styles', $this->settings[$this->getCurrentSiteName()]['resources']['footer'])) {
                            $resources = $this->settings[$this->getCurrentSiteName()]['resources']['footer']['styles'];
                            $result = $this->buildStyles($resources);
                            $this->resourceCache->set('compiledFooterCss', $result);
                            return $result;
                        }
                    }
                }
            }
        }
        return '';
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    public function footerScriptsAction(): string
    {
        $this->response->setContentType('application/javascript');
        if ($this->resourceCache->has('compiledFooterJs') && $this->getContext() == 'Production') {
            return $this->resourceCache->get('compiledFooterJs');
        } else {
            if(array_key_exists($this->getCurrentSiteName(), $this->settings)) {
                if (array_key_exists('resources', $this->settings[$this->getCurrentSiteName()])) {
                    if (array_key_exists('footer', $this->settings[$this->getCurrentSiteName()]['resources'])) {
                        if (array_key_exists('scripts', $this->settings[$this->getCurrentSiteName()]['resources']['footer'])) {
                            $resources = $this->settings[$this->getCurrentSiteName()]['resources']['footer']['scripts'];
                            $result = $this->buildScripts($resources);
                            $this->resourceCache->set('compiledFooterJs', $result);
                            return $result;
                        }
                    }
                }
            }
        }
        return '';
    }

    /**
     * @param array $resources
     * @return string
     * @Flow\SkipCsrfProtection
     */
    private function buildStyles(array $resources): string
    {
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
    private function buildScripts(array $resources): string
    {
        $result = '/*' . $this->getTstamp() . '*/';
        if(!empty($resources)) {
            foreach ($resources as $i => $resource) {
                $result .= $this->buildDeclaration($i) . $this->minifyService->minifyScript($resource) . ';';
            }
        }
        $result .= ';';
        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    private function buildDeclaration(string $name): string
    {
        return '/*NeosRulez.DynamicResources:' . $name . '*/';
    }

    /**
     * @return string
     */
    private function getTstamp(): string
    {
        $date = new \DateTime();
        return $date->getTimestamp();
    }

    /**
     * @return string
     * @Flow\SkipCsrfProtection
     */
    private function getCurrentSiteName(): string
    {
        $context = $this->contextFactory->create();
        $currentSiteNode = $context->getCurrentSiteNode();
        return $currentSiteNode->getName();
    }

    /**
     * @return string
     */
    private function getContext(): string
    {
        return \Neos\Flow\Core\Bootstrap::getEnvironmentConfigurationSetting('FLOW_CONTEXT') ?: 'Development';
    }

}
