<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\Aggregate\FaqTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                          add(FaqTranslationEntity $entity)
 * @method void                          set(string $key, FaqTranslationEntity $entity)
 * @method FaqTranslationEntity[]    getIterator()
 * @method FaqTranslationEntity[]    getElements()
 * @method FaqTranslationEntity|null get(string $key)
 * @method FaqTranslationEntity|null first()
 * @method FaqTranslationEntity|null last()
 */
class FaqTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return FaqTranslationEntity::class;
    }
}