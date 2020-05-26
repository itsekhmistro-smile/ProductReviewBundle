<?php

namespace Smile\Bundle\ProductReviewBundle\DependencyInjection;

use Doctrine\DBAL\Types\Type;
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
    public const PROTECT_PRODUCT_REVIEW_FORM = 'protect_product_review_form';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(ProductReviewExtension::ALIAS);

        SettingsBuilder::append(
            $treeBuilder->getRootNode(),
            [
                self::PROTECT_PRODUCT_REVIEW_FORM => [
                    'type'  => Type::BOOLEAN,
                    'value' => false
                ],
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
