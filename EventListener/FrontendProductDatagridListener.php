<?php

namespace Smile\Bundle\ProductReviewBundle\EventListener;

use Doctrine\DBAL\Types\Type;
use Oro\Bundle\DataGridBundle\Datasource\ResultRecordInterface;
use Oro\Bundle\DataGridBundle\Event\BuildBefore;
use Oro\Bundle\DataGridBundle\Provider\SelectedFields\SelectedFieldsProviderInterface;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\SearchBundle\Datagrid\Event\SearchResultAfter;
use Smile\Bundle\ProductReviewBundle\Manager\ProductReviewManager;
use Smile\Bundle\ProductReviewBundle\Provider\ProductReviewFieldNameProvider;

/**
 * Class FrontendProductDatagridListener
 */
class FrontendProductDatagridListener
{
    /**
     * @var DoctrineHelper
     */
    protected $doctrineHelper;

    /**
     * @var SelectedFieldsProviderInterface
     */
    protected $selectedFieldsProvider;

    /**
     * @var ProductReviewManager
     */
    protected $productReviewManager;

    /**
     * FrontendProductDatagridListener constructor.
     *
     * @param DoctrineHelper $doctrineHelper
     * @param SelectedFieldsProviderInterface $selectedFieldsProvider
     * @param ProductReviewManager $productReviewManager
     */
    public function __construct(
        DoctrineHelper $doctrineHelper,
        SelectedFieldsProviderInterface $selectedFieldsProvider,
        ProductReviewManager $productReviewManager
    ) {
        $this->doctrineHelper = $doctrineHelper;
        $this->selectedFieldsProvider = $selectedFieldsProvider;
        $this->productReviewManager = $productReviewManager;
    }

    /**
     * @param SearchResultAfter $event
     */
    public function onResultAfter(SearchResultAfter $event): void
    {
        $datagrid = $event->getDatagrid();
        $records = $event->getRecords();
        $selectedFields = $this->selectedFieldsProvider
            ->getSelectedFields($datagrid->getConfig(), $datagrid->getParameters());

        if (!in_array(ProductReviewFieldNameProvider::RATING_FIELD_NAME, $selectedFields, true) ||
            empty($records)
        ) {
            return;
        }

        foreach ($records as $record) {
            $this->setProductRatingField($record);
        }

        $event->setRecords($records);
    }

    /**
     * {@inheritdoc}
     */
    public function onBuildBefore(BuildBefore $event)
    {
        $config = $event->getConfig();

        $config->offsetAddToArrayByPath(
            '[columns]',
            [
                ProductReviewFieldNameProvider::RATING_FIELD_NAME => [
                    'label' => 'smile.product_review.ui.rating'
                ],
            ]
        );

        $config->addSorter(
            ProductReviewFieldNameProvider::RATING_FIELD_NAME,
            ['data_name' => ProductReviewFieldNameProvider::RATING_FIELD_NAME, 'type' => Type::DECIMAL]
        );

        $config->addColumn(
            ProductReviewFieldNameProvider::COUNT_REVIEWS_FIELD_NAME,
            [
                ProductReviewFieldNameProvider::COUNT_REVIEWS_FIELD_NAME => [
                    'label' => 'smile.product_review.ui.count_reviews'
                ],
            ]
        );
    }

    /**
     * @param ResultRecordInterface $record
     */
    protected function setProductRatingField(ResultRecordInterface $record): void
    {
        $rating = $record->getValue('rating');
        $formattedRating = $this->productReviewManager->getFormattedProductRating((floatval($rating))) ?: null;

        $record->setValue(ProductReviewFieldNameProvider::RATING_FIELD_NAME, $formattedRating);
    }
}
