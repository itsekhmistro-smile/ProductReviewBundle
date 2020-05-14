<?php

namespace Smile\Bundle\ProductReviewBundle\Layout\DataProvider;

use Oro\Bundle\LayoutBundle\Layout\DataProvider\AbstractFormProvider;
use Oro\Bundle\ProductBundle\Entity\Product;
use Smile\Bundle\ProductReviewBundle\Form\Type\ProductReviewType;
use Smile\Bundle\ProductReviewBundle\Manager\GoogleRecaptchaManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ProductReviewFormProvider
 */
class ProductReviewFormProvider extends AbstractFormProvider
{
    /**
     * @var GoogleRecaptchaManager
     */
    protected $googleRecaptchaManager;

    /**
     * ProductReviewFormProvider constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param UrlGeneratorInterface $router
     * @param GoogleRecaptchaManager $googleRecaptchaManager
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        GoogleRecaptchaManager $googleRecaptchaManager
    ) {
        parent::__construct($formFactory, $router);
        $this->googleRecaptchaManager = $googleRecaptchaManager;
    }

    /**
     * @param Product $product
     * @param null $data
     * @param array $options
     * @param array $cacheKeyOptions
     *
     * @return FormView
     */
    public function getProductReviewFormView(
        Product $product,
        $data = null,
        array $options = [],
        array $cacheKeyOptions = []
    ): FormView {
        $form = $this->getForm(
            ProductReviewType::class,
            $data,
            $this->getOptions($options),
            $cacheKeyOptions
        );

        $form->get('product')->setData($product);

        return $form->createView();
    }

    /**
     * @return bool
     */
    public function isGoogleRecaptchaSettingsValid(): bool
    {
        return $this->googleRecaptchaManager->isGoogleRecaptchaSettingsValid();
    }

    /**
     * @return string|null
     */
    public function getGoogleRecaptchaSiteKey(): ?string
    {
        return $this->googleRecaptchaManager->getGoogleRecaptchaSiteKey();
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getOptions(array $options = []): array
    {
        return array_merge($this->getDefaultOptions(), $options);
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return ['action' => $this->generateUrl('product_review_create')];
    }
}
