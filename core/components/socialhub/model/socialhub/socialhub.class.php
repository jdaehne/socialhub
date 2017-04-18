<?php

/**
 * The main SocialHub service class.
 *
 * @package socialhub
 *
 * @author Sterc <modx@sterc.nl>
 */

class SocialHub
{
    public $modx = null;
    public $namespace = 'socialhub';
    public $cache = null;
    public $options = array();

    /**
     * SocialHub constructor.
     *
     * @param modX  $modx
     * @param array $options
     */
    public function __construct(modX &$modx, array $options = array())
    {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, 'socialhub');

        $corePath = $this->getOption(
            'core_path',
            $options,
            $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/socialhub/'
        );

        $assetsPath = $this->getOption(
            'assets_path',
            $options,
            $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/socialhub/'
        );

        $assetsUrl = $this->getOption(
            'assets_url',
            $options,
            $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/socialhub/'
        );

        /* loads some default paths for easier management */
        $this->options = array_merge(
            array(
                'namespace' => $this->namespace,
                'corePath' => $corePath,
                'modelPath' => $corePath . 'model/',
                'chunksPath' => $corePath . 'elements/chunks/',
                'snippetsPath' => $corePath . 'elements/snippets/',
                'templatesPath' => $corePath . 'templates/',
                'assetsPath' => $assetsPath,
                'assetsUrl' => $assetsUrl,
                'jsUrl' => $assetsUrl . 'js/',
                'cssUrl' => $assetsUrl . 'css/',
                'connectorUrl' => $assetsUrl . 'connector.php'
            ),
            $options
        );

        $this->modx->addPackage('socialhub', $this->getOption('modelPath'));
        $this->modx->lexicon->load('socialhub:default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }
}
