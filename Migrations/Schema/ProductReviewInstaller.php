<?php

namespace Smile\Bundle\ProductReviewBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Smile\Bundle\ProductReviewBundle\Migrations\Schema\v1_0\ProductReviewBundle;
use Smile\Bundle\ProductReviewBundle\Migrations\Schema\v1_1\AddOrganization;

/**
 * Class ProductReviewInstaller
 */
class ProductReviewInstaller implements Installation, ExtendExtensionAwareInterface
{
    /**
     * @var ExtendExtension
     */
    protected $extendExtension;

    /**
     * {@inheritdoc}
     */
    public function setExtendExtension(ExtendExtension $extendExtension)
    {
        $this->extendExtension = $extendExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_1';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $reviewMigration = new ProductReviewBundle();
        $reviewMigration->setExtendExtension($this->extendExtension);
        $reviewMigration->up($schema, $queries);

        (new AddOrganization())->up($schema, $queries);
    }
}
