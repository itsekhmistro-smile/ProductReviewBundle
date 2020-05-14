<?php

namespace Smile\Bundle\ProductReviewBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Entity\Repository\ProductRepository;
use Smile\Bundle\ProductReviewBundle\Form\DataTransformer\ProductToDefaultNameTransformer;
use Smile\Bundle\ProductReviewBundle\Form\Model\ProductReviewFormModel;
use Smile\Bundle\ProductReviewBundle\Manager\GoogleRecaptchaManager;
use Smile\Bundle\ProductReviewBundle\Provider\ProductReviewRatingChoiceProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class ProductReviewType
 *
 * @codingStandardsIgnoreFile
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class ProductReviewType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var ProductReviewRatingChoiceProvider
     */
    protected $productReviewRatingChoiceProvider;

    /**
     * @var GoogleRecaptchaManager
     */
    protected $googleRecaptchaManager;

    /**
     * ProductReviewType constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param ProductReviewRatingChoiceProvider $productReviewRatingChoiceProvider
     * @param GoogleRecaptchaManager $googleRecaptchaManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        ProductReviewRatingChoiceProvider $productReviewRatingChoiceProvider,
        GoogleRecaptchaManager $googleRecaptchaManager
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->productReviewRatingChoiceProvider = $productReviewRatingChoiceProvider;
        $this->googleRecaptchaManager = $googleRecaptchaManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'rating',
                ChoiceType::class,
                [
                    'label' => 'oro.translation.product_review_create_form.rating_label',
                    'required' => false,
                    'choices' => $this->productReviewRatingChoiceProvider->getAvailableProductReviewRatingChoices(),
                    'expanded' => true
                ]
            )
            ->add(
                'comment',
                TextareaType::class,
                [
                    'label' => 'oro.translation.product_review_create_form.comment_label',
                    'required' => false
                ]
            )
            ->add(
                'author',
                TextType::class,
                [
                    'label' => 'oro.translation.product_review_create_form.author_label',
                    'required' => true
                ]
            )
            ->add('product',
                HiddenType::class,
                [
                    'required' => true
                ]
            )
            ->add('customerUser',
                HiddenType::class,
                [
                    'required' => false
                ]
            )
            ->add('recaptchaResponse',
                HiddenType::class,
                [
                    'required' => false
                ]
            )
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit'])
        ;

        $builder->get('product')
            ->addModelTransformer(new ProductToDefaultNameTransformer($this->entityManager))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductReviewFormModel::class,
            'validation_groups' => $this->getValidationGroup()
        ]);
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event): void
    {
        if ($this->getCustomerUser()) {
            $data = $event->getData();
            $this->setField($data, 'customerUser', $this->getCustomerUser());
            $event->setData($data);
        }
    }

    /**
     * @return ProductRepository
     */
    protected function getProductRepository(): ProductRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    /**
     * @return CustomerUser|null
     */
    protected function getCustomerUser(): ?CustomerUser
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return $user instanceof CustomerUser ? $user : null;
    }

    /**
     * @param array $data
     * @param string $fieldName
     * @param $fieldValue
     */
    protected function setField(array &$data, string $fieldName, $fieldValue): void
    {
        $data[$fieldName] = $fieldValue;
    }

    /**
     * @return array
     */
    protected function getValidationGroup(): array
    {
        $groups = [];

        if (!$this->getCustomerUser()) {
            $groups[] = ProductReviewFormModel::POST_PRODUCT_REVIEW_FOR_ANONYMOUS;
        } else {
            $groups[] = ProductReviewFormModel::POST_PRODUCT_REVIEW_FOR_LOGGED;
        }

        if ($this->googleRecaptchaManager->isGoogleRecaptchaSettingsValid()) {
            $groups[] = ProductReviewFormModel::POST_PRODUCT_REVIEW_WITH_RECAPTCHA_VALIDATION_GROUP;
        }

        return $groups;
    }
}
