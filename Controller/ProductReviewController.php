<?php

namespace Smile\Bundle\ProductReviewBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Oro\Bundle\SecurityBundle\Annotation\Acl;

/**
 * Class ProductReviewController
 *
 * @Route("/product-reviews")
 */
class ProductReviewController extends AbstractController
{
    /**
     * @Route("/", name="product_reviews_index")
     * @Acl(
     *      id="product_reviews_index",
     *      type="entity",
     *      class="SmileProductReviewBundle:ProductReview",
     *      permission="VIEW"
     * )
     *
     * @Template()
     *
     * @return array
     */
    public function indexAction(): array
    {
        return [];
    }
}
