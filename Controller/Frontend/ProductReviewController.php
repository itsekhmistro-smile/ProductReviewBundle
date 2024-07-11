<?php

namespace Smile\Bundle\ProductReviewBundle\Controller\Frontend;

use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Smile\Bundle\ProductReviewBundle\Form\Type\ProductReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Oro\Bundle\SecurityBundle\Attribute\Acl;

/**
 * Class ProductReviewController
 */
class ProductReviewController extends AbstractController
{
    public function __construct(private \Doctrine\Persistence\ManagerRegistry $managerRegistry)
    {
    }
    /**
     *
     * @param Request $request
     * @return Response
     */
    #[\Symfony\Component\Routing\Attribute\Route(path: '/create', name: 'product_review_create')]
    #[Acl(id: 'product_review_create', type: 'entity', class: 'SmileProductReviewBundle:ProductReview', group_name: 'commerce', permission: 'CREATE')]
    public function createAction(Request $request)
    {
        $form = $this->createForm(ProductReviewType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productReviewFormModel = $form->getData();
            $productReviewFactory = $this->get('smile_product_review.factory.product_review');
            $productReview = $productReviewFactory->createProductReview($productReviewFormModel);

            $em = $this->managerRegistry->getManagerForClass(ProductReview::class);
            $em->persist($productReview);
            $em->flush();

            $this->addFlash(
                'success',
                $this->get('translator')->trans('smile.product_review.form.success_created_text')
            );

            return $this->redirectToRoute('oro_product_frontend_product_view', [
                'id' => $productReview->getProduct()->getId()
            ]);
        }

        return $this->json($form, Response::HTTP_BAD_REQUEST);
    }
}
