<?php

namespace Smile\Bundle\ProductReviewBundle\Entity\Factory;

use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;
use Smile\Bundle\ProductReviewBundle\Form\Model\ProductReviewFormModel;

/**
 * Class ProductReviewFactory
 */
class ProductReviewFactory
{
    /**
     * @param ProductReviewFormModel|null $data
     *
     * @return ProductReview
     */
    public function createProductReview(ProductReviewFormModel $data = null): ProductReview
    {
        return $data ? $this->createFromData($data) : new ProductReview();
    }

    /**
     * @param ProductReviewFormModel $data
     *
     * @return ProductReview
     */
    protected function createFromData(ProductReviewFormModel $data): ProductReview
    {
        $productReview = new ProductReview();
        $productReview->setRating($data->rating);
        $productReview->setComment($data->comment);
        $productReview->setAuthor($data->author);
        $productReview->setProduct($data->product);
        $productReview->setCustomerUser($data->customerUser);

        return $productReview;
    }
}
