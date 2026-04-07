<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\DataResolver;

use Webwirkung\FaqPlugin\Core\Content\FaqCategory\FaqCategoryDefinition;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class FaqCategoryListingCmsElementResolver extends AbstractCmsElementResolver
{
    /**
     * Returns the definition of the element.
     * It's name of the element component in this case cms-element-faq-category-listing.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'faq-category-listing';
    }

    /**
     * Prepares the criteria object
     * It gets element configuration
     * It creates a new criteria instance based on the configuration
     * It dispatches an event to modify the criteria object
     * It creates criteria collection based on the criteria
     * It returns the criteria collection
     *
     * @param CmsSlotEntity $slot
     * @param ResolverContext $resolverContext
     * @return CriteriaCollection|null
     */
    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $criteria = new Criteria();
        $criteria->addAssociation('faq');

        $criteriaCollection = new CriteriaCollection();

        $criteriaCollection->add(
            FaqCategoryDefinition::ENTITY_NAME,
            FaqCategoryDefinition::class,
            $criteria
        );

        return $criteriaCollection;
    }

    /**
     * Perform additional logic on the data that has been resolved
     * It sets the resolved data to the cms slot
     *
     * @param CmsSlotEntity $slot
     * @param ResolverContext $resolverContext
     * @param ElementDataCollection $result
     * @return void
     */
    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $slot->setData($result->get(FaqCategoryDefinition::ENTITY_NAME));
    }

}
