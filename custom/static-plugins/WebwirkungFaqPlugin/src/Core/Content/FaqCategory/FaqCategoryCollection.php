<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\FaqCategory;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void               add(FaqCategoryEntity $entity)
 * @method void               set(string $key, FaqCategoryEntity $entity)
 * @method FaqCategoryEntity[]    getIterator()
 * @method FaqCategoryEntity[]    getElements()
 * @method FaqCategoryEntity|null get(string $key)
 * @method FaqCategoryEntity|null first()
 * @method FaqCategoryEntity|null last()
 */
class FaqCategoryCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return FaqCategoryEntity::class;
    }
}