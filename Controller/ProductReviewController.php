<?php

namespace Smile\Bundle\ProductReviewBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Oro\Bundle\SecurityBundle\Attribute\Acl;

/**
 * Class ProductReviewController
 */
#[\Symfony\Component\Routing\Attribute\Route(path: '/product-reviews')]
class ProductReviewController extends AbstractController
{
    /**
     * @return array
     */
    #[\Symfony\Component\Routing\Attribute\Route(path: '/', name: 'product_reviews_index')]
    #[Acl(id: 'product_reviews_index', type: 'entity', class: 'SmileProductReviewBundle:ProductReview', permission: 'VIEW')]
    #[Template]
    public function indexAction(): array
    {
        return [];
    }
}
