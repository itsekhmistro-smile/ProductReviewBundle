<?php

namespace Smile\Bundle\ProductReviewBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class ProductReviewBundle
 */
class ProductReviewBundle implements Migration, ExtendExtensionAwareInterface
{
    private const SMILE_PRODUCT_REVIEW_TABLE = 'smile_product_review';

    /** @var ExtendExtension */
    private $extendExtension;

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries): void
    {
        /** Tables generation **/
        $this->createProductReviewTable($schema);

        /** Additional relations */
        $this->addRelationToProduct($schema);
        $this->addRelationToUser($schema);

        /** Update product table */
        $this->updateProductTable($schema);
    }

    /**
     * @param ExtendExtension $extendExtension
     */
    public function setExtendExtension(ExtendExtension $extendExtension)
    {
        $this->extendExtension = $extendExtension;
    }

    /**
     * Create smile_product_review tabl3
     *
     * @param Schema $schema
     */
    protected function createProductReviewTable(Schema $schema): void
    {
        $table = $schema->createTable(self::SMILE_PRODUCT_REVIEW_TABLE);
        $table->addColumn('id', Type::INTEGER, ['autoincrement' => true]);
        $table->addColumn('rating', Type::DECIMAL, ['precision' => 3, 'scale' => 2]);
        $table->addColumn('comment', Type::TEXT, ['notnull' => false]);
        $table->addColumn('status', Type::STRING, ['length' => 30]);
        $table->addColumn('author', Type::STRING, ['length' => 255, 'notnull' => false]);
        $table->addColumn('created_at', Type::DATETIME, []);
        $table->addColumn('updated_at', Type::DATETIME, []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Add relation from ProductReview entity to Product entity
     *
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    protected function addRelationToProduct(Schema $schema): void
    {
        $this->extendExtension->addManyToOneRelation(
            $schema,
            self::SMILE_PRODUCT_REVIEW_TABLE,
            'product',
            'oro_product',
            'id',
            [
                'extend' => ['owner' => ExtendScope::OWNER_CUSTOM],
            ]
        );
    }

    /**
     * Add relation from ProductReview entity to User entity
     *
     * @param Schema $schema
     */
    protected function addRelationToUser(Schema $schema): void
    {
        $this->extendExtension->addManyToOneRelation(
            $schema,
            self::SMILE_PRODUCT_REVIEW_TABLE,
            'customer_user',
            'oro_customer_user',
            'id',
            [
                'extend' => ['owner' => ExtendScope::OWNER_CUSTOM],
            ]
        );
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    protected function updateProductTable(Schema $schema): void
    {
        $table = $schema->getTable('oro_product');
        $table->addColumn(
            'rating',
            Type::DECIMAL,
            $this->getOroOptions()
        );
    }

    /**
     * @return array
     */
    protected function getOroOptions(): array
    {
        return [
            'precision' => 3,
            'scale' => 2,
            'oro_options' => [
                'extend' => [
                    'is_extend' => true,
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'is_serialized' => false
                ],
                'entity' => [
                    'label' => 'oro.translation.product_review_widget.label'
                ],
                'datagrid' => [
                    'is_visible' => true
                ],
                'attribute' => [
                    'is_attribute' => true,
                    'searchable' => false,
                    'filterable' => true,
                    'filter_by' => 'exact_value',
                    'sortable' => true
                ]

            ]
        ];
    }
}
