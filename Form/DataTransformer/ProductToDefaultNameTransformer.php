<?php

namespace Smile\Bundle\ProductReviewBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;

use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Entity\Repository\ProductRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class ProductToDefaultNameTransformer
 */
class ProductToDefaultNameTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ProductToDefaultNameTransformer constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (product) to a string (id).
     *
     * @param  Product $product
     * @return string
     */
    public function transform($product)
    {
        if (null === $product) {
            return '';
        }

        return $product->getId();
    }

    /**
     * Transforms a string (id to an object (product).
     *
     * @param string $productId
     *
     * @return mixed|object
     * @throws TransformationFailedException if object (product) is not found.
     */
    public function reverseTransform($productId)
    {
        $product = $this->getProduct($productId);

        if (null === $product) {
            throw new TransformationFailedException(sprintf('Product with name "%s" does not exist!', $product));
        }

        return $product;
    }

    /**
     * @param string $productId
     *
     * @return object|null
     */
    protected function getProduct(string $productId): ?Product
    {
        return $this->getProductRepository()->find((int)$productId);
    }

    /**
     * @return ProductRepository
     */
    protected function getProductRepository(): ProductRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }
}
