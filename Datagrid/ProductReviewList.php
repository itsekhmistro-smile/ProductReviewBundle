<?php

namespace Smile\Bundle\ProductReviewBundle\Datagrid;

use Oro\Bundle\DataGridBundle\Extension\GridViews\AbstractViewsList;
use Oro\Bundle\DataGridBundle\Extension\GridViews\View;
use Oro\Bundle\FilterBundle\Form\Type\Filter\EnumFilterType;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;

/**
 * Class ProductReviewList
 */
class ProductReviewList extends AbstractViewsList
{
    /**
     * {@inheritdoc}
     */
    protected function getViewsList()
    {
        $view = new View(
            'product_review.need_reviews_status',
            [
                'status' => [
                    'type'  => EnumFilterType::TYPE_IN,
                    'value' => [ProductReview::STATUS_NEEDS_REVIEW]
                ]
            ]
        );
        $view
            ->setLabel($this->translator->trans('smile.product_review.datagrid.view.needs_review'))
            ->setDefault(true);

        return [$view];
    }
}
