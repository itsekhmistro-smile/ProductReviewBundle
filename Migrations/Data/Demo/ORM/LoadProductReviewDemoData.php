<?php

namespace Smile\Bundle\ProductReviewBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Oro\Bundle\ProductBundle\Entity\Product;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;

/**
 * Class LoadProductReviewDemoData
 */
class LoadProductReviewDemoData implements FixtureInterface, DependentFixtureInterface
{
    /** @var Generator */
    protected $faker;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $products = $this->getProducts($manager);

        /** @var Product $product */
        foreach ($products as $product) {
            $sumRating = $countPublishedReviews = 0;

            for ($i = 0; $i < 2; $i++) {
                $productReview = new ProductReview();
                $productReview->setRating(
                    $this->faker->numberBetween(ProductReview::MIN_RATING, ProductReview::MAX_RATING)
                );
                $productReview->setComment($this->faker->text());
                $productReview->setAuthor($this->faker->name());
                $productReview->setStatus($this->faker->randomElement(ProductReview::AVAILABLE_STATUSES));
                $productReview->setProduct($product);

                if ($productReview->getStatus() === ProductReview::STATUS_PUBLISHED) {
                    $sumRating += $productReview->getRating();
                    $countPublishedReviews++;
                }

                $manager->persist($productReview);
            }

            $product->setRating($this->calculateAverageRating($sumRating, $countPublishedReviews));
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            'Oro\Bundle\ProductBundle\Migrations\Data\Demo\ORM\LoadProductDemoData',
        ];
    }

    /**
     * @param ObjectManager $manager
     *
     * @return array
     */
    protected function getProducts(ObjectManager $manager): array
    {
        return $manager->getRepository(Product::class)->findBy([
            'status' => 'enabled'
        ]);
    }

    /**
     * @param float $sumRating
     * @param int $countPublishedReviews
     *
     * @return float
     */
    protected function calculateAverageRating(float $sumRating, int $countPublishedReviews): ?float
    {
        if ($countPublishedReviews === 0) {
            return null;
        }

        return $sumRating / $countPublishedReviews;
    }
}
