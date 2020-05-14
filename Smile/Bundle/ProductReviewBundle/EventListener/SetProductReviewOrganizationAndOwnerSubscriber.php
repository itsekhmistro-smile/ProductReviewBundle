<?php

namespace Smile\Bundle\ProductReviewBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Smile\Bundle\ProductReviewBundle\Entity\ProductReview;

/**
 * Class SetProductReviewOrganizationAndOwnerSubscriber
 */
class SetProductReviewOrganizationAndOwnerSubscriber implements EventSubscriber
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TokenAccessorInterface
     */
    protected $tokenAccessor;

    /**
     * SetProductReviewOrganizationAndOwnerSubscriber constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param TokenAccessorInterface $tokenAccessor
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenAccessorInterface $tokenAccessor
    ) {
        $this->entityManager = $entityManager;
        $this->tokenAccessor = $tokenAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof ProductReview) {
            return;
        }

        $this->setOrganizationAndOwner($entity);
    }

    /**
     * @param ProductReview $review
     */
    protected function setOrganizationAndOwner(ProductReview $review): void
    {
        $organization = $this->tokenAccessor->getOrganization();

        if (!$organization instanceof Organization) {
            $organization = $this->getDefaultOrganization();
        }

        if ($organization instanceof Organization) {
            $review->setOrganization($organization);
            $review->setOwner($organization->getBusinessUnits()->first());
        }
    }

    /**
     * @return Organization|null
     */
    protected function getDefaultOrganization(): ?Organization
    {
        return $this->entityManager->getRepository(Organization::class)->findOneBy([]);
    }
}
