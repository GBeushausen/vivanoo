<?php declare(strict_types=1);

namespace Webwirkung\GlossaryPlugin\DataResolver;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Webwirkung\GlossaryPlugin\Core\Content\Glossary\GlossaryDefinition;

class GlossaryListingCmsElementResolver extends AbstractCmsElementResolver
{
    /**
     * Returns the definition of the element.
     * It's name of the element component in this case cms-element-glossary-listing.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'glossary-listing';
    }

    /**
     * Loads all glossary entries and returns them as a CriteriaCollection
     *
     * @param CmsSlotEntity $slot
     * @param ResolverContext $resolverContext
     * @return CriteriaCollection|null
     */
    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', true)); // Add filter for active = true
        $criteria->addSorting(new FieldSorting('name')); // Sort by name alphabetically
//        $criteria->setPage(1); // Start from page 1
//        $criteria->setTotalCountMode(Criteria::TOTAL_COUNT_MODE_EXACT); // Get the exact total count (not necessary in this case, but can be useful in other cases
//        $criteria->setLimit(25); // Limit the result to 25 items (pagination can be added here as well
//        $criteria->setOffset(1); // Start from the beginning

        $criteriaCollection = new CriteriaCollection();

        $criteriaCollection->add(
            GlossaryDefinition::ENTITY_NAME,
            GlossaryDefinition::class,
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
        $slot->setData($result->get(GlossaryDefinition::ENTITY_NAME));
    }

}
