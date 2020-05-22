<?php

namespace Smile\Bundle\ProductReviewBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class AddOrganization
 * @package Smile\Bundle\ProductReviewBundle\Migrations\Schema\v1_1
 */
class AddOrganization implements Migration
{
    private const SMILE_PRODUCT_REVIEW_TABLE = 'smile_product_review';

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->updateProductTable($schema);
        $this->addOroProductForeignKeys($schema);
    }

    /**
     * @param Schema $schema
     * @throws SchemaException
     */
    protected function updateProductTable(Schema $schema)
    {
        $table = $schema->getTable(self::SMILE_PRODUCT_REVIEW_TABLE);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('business_unit_owner_id', 'integer', ['notnull' => false]);
    }

    /**
     * @param Schema $schema
     * @throws SchemaException
     */
    protected function addOroProductForeignKeys(Schema $schema)
    {
        $table = $schema->getTable(self::SMILE_PRODUCT_REVIEW_TABLE);
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_business_unit'),
            ['business_unit_owner_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }
}
