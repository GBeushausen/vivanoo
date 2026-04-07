<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\DataResolver;

use Webwirkung\FaqPlugin\Core\Content\Faq\FaqDefinition;

use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class FaqSelectListingCmsElementResolver extends AbstractCmsElementResolver
{
    /**
     * Returns the definition of the element.
     * It's name of the element component in this case cms-element-faq-category-listing.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'faq-select-element';
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

        $faqItemsConfig = $slot->getFieldConfig()->get('faqEntry')?? null;
        if ($faqItemsConfig === null || $faqItemsConfig->isMapped() || $faqItemsConfig->isDefault()) {
            return null;
        }
        $faqItems = $faqItemsConfig->getArrayValue();
        if (empty($faqItems)) {
            return null;
        }

        $criteria = new Criteria($faqItems);
        $criteria->addFilter(
            new EqualsAnyFilter('id', $faqItems)
        );

        $criteriaCollection = new CriteriaCollection();
        $criteriaCollection->add(
            'faq_select_element',
            FaqDefinition::class,
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
        $result = $result->get('faq_select_element') ?? null;
        if ($result !== null && $result->first() !== null) {
            $slot->setData($result);
        }
    }

}
