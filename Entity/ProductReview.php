<?php

namespace Smile\Bundle\ProductReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\BusinessUnit;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationInterface;
use Smile\Bundle\ProductReviewBundle\Model\ExtendProductReview;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductReview entity class
 *
 * @ORM\Entity(repositoryClass="Smile\Bundle\ProductReviewBundle\Entity\Repository\ProductReviewRepository")
 * @ORM\Table(
 *     name="smile_product_review",
 *     indexes={
 *          @ORM\Index(name="product_id", columns={"product_id"})
 *     }
 * )
 * @Config(
 *     routeName="product_reviews_index",
 *     routeCreate="product_review_create",
 *     routeUpdate="product_reviews_update",
 *     defaultValues={
 *          "dataaudit"={
 *              "auditable"=false
 *          },
 *          "attribute"={
 *              "has_attributes"=true
 *          },
 *          "ownership"={
 *              "owner_type"="BUSINESS_UNIT",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="business_unit_owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"="commerce"
 *         }
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class ProductReview extends ExtendProductReview implements DatesAwareInterface
{
    use DatesAwareTrait;

    public const
        VALIDATION_GROUP = "post_product_review",
        MIN_RATING = 1,
        MAX_RATING = 5,
        STATUS_PUBLISHED = 'published',
        STATUS_NEEDS_REVIEW = 'needs_review',
        STATUS_SPAM = 'spam',
        STATUS_DELETED = 'deleted',

        AVAILABLE_STATUSES = [
            self::STATUS_PUBLISHED, self::STATUS_NEEDS_REVIEW, self::STATUS_SPAM, self::STATUS_DELETED
        ]
    ;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="smile.product_review.ui.id"
     *          }
     *      }
     * )
     *
     * @var int
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=3, scale=2)
     * @Assert\NotBlank(groups={ProductReview::VALIDATION_GROUP})
     * @Assert\Range(
     *     min=ProductReview::MIN_RATING,
     *     max=ProductReview::MAX_RATING,
     *     minMessage="Min rating is {{ limit }}",
     *     maxMessage="Max rating is {{ limit }}",
     *     groups={ProductReview::VALIDATION_GROUP}
     * )
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="smile.product_review.ui.rating"
     *          }
     *      }
     * )
     */
    protected $rating;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="smile.product_review.ui.comment"
     *          }
     *      }
     * )
     */
    protected $comment;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="smile.product_review.ui.status"
     *          }
     *      }
     * )
     */
    protected $status = self::STATUS_NEEDS_REVIEW;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="smile.product_review.ui.author"
     *          }
     *      }
     * )
     */
    protected $author;

    /**
     * @var BusinessUnit
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\BusinessUnit")
     * @ORM\JoinColumn(name="business_unit_owner_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $owner;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $organization;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get rating.
     *
     * @return string
     */
    public function getRating(): ?string
    {
        return $this->rating;
    }

    /**
     * Set rating.
     *
     * @param string $rating
     */
    public function setRating(string $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * Get comment
     *
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Set comment
     *
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * Get author
     *
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param string|null $author
     */
    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return BusinessUnit
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param BusinessUnit $owningBusinessUnit
     *
     * @return ProductReview
     */
    public function setOwner($owningBusinessUnit)
    {
        $this->owner = $owningBusinessUnit;

        return $this;
    }

    /**
     * @param OrganizationInterface $organization
     *
     * @return ProductReview
     */
    public function setOrganization(OrganizationInterface $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return OrganizationInterface
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Get author display name
     *
     * @return string|null
     */
    public function getAuthorDisplayName(): ?string
    {
        return $this->getAuthor() ?: $this->getAuthorFullName();
    }

    /**
     * Get author full name
     *
     * @return string|null
     */
    public function getAuthorFullName(): ?string
    {
        $user = $this->getCustomerUser();

        return $user
            ? sprintf('%s %s', $user->getFirstname(), $user->getLastname())
            : null
        ;
    }

    /**
     * Pre persist event handler
     *
     * @ORM\PrePersist
     *
     * @throws \Exception
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * Pre update event handler
     *
     * @ORM\PreUpdate
     *
     * @throws \Exception
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
