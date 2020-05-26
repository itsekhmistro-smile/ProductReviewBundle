<?php

namespace Smile\Bundle\ProductReviewBundle\Controller\Frontend;

use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Smile\Bundle\ProductReviewBundle\Form\Type\ProductReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Oro\Bundle\SecurityBundle\Annotation\Acl;

/**
 * Class ProductReviewController
 */
class ProductReviewController extends AbstractController
{
    /**
     * @Route("/create", name="product_review_create")
     * @Acl(
     *      id="product_review_create",
     *      type="entity",
     *      class="ProductReviewBundle:ProductReview",
     *      group_name="commerce",
     *      permission="CREATE"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(ProductReviewType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productReviewFormModel = $form->getData();
            $productReviewFactory = $this->get('smile_product_review.factory.product_review');
            $productReview = $productReviewFactory->createProductReview($productReviewFormModel);

            $em = $this->getDoctrine()->getManagerForClass(ProductReview::class);
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
