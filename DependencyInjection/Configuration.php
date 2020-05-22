<?php

namespace Smile\Bundle\ProductReviewBundle\DependencyInjection;

use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\ConfigBundle\DependencyInjection\SettingsBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Defines bundle-specific config properties structure
 *
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    public const
        PARAM_NAME_ENABLED_GOOGLE_RECAPTCHA = 'enable_google_recaptcha',
        PARAM_NAME_GOOGLE_RECAPTCHA_SITE_KEY = 'google_recaptcha_site_key',
        PARAM_NAME_GOOGLE_RECAPTCHA_SECRET_KEY = 'google_recaptcha_secret_key'
    ;

    /** {@inheritdoc} */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(ProductReviewExtension::ALIAS);

        SettingsBuilder::append(
            $treeBuilder->getRootNode(),
            [
                self::PARAM_NAME_ENABLED_GOOGLE_RECAPTCHA  => [
                    'type'  => 'boolean',
                    'value' => false
                ],
                self::PARAM_NAME_GOOGLE_RECAPTCHA_SITE_KEY  => [
                    'type'  => 'string',
                    'value' => ''
                ],
                self::PARAM_NAME_GOOGLE_RECAPTCHA_SECRET_KEY  => [
                    'type'  => 'string',
                    'value' => ''
                ]
            ]
        );

        return $treeBuilder;
    }

    /**
     * Returns the full config path key (with namespace) by the config name
     *
     * @param $name string last part of the key name (one of the class const can be used)
     * @return string full config path key
     */
    public static function getConfigKeyByName($name)
    {
        return ProductReviewExtension::ALIAS . ConfigManager::SECTION_MODEL_SEPARATOR . $name;
    }
}
