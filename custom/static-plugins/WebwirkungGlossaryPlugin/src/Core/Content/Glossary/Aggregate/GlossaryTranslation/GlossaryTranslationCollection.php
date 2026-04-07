<?php declare(strict_types=1);

namespace Webwirkung\GlossaryPlugin\Core\Content\Glossary\Aggregate\GlossaryTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                          add(GlossaryTranslationEntity $entity)
 * @method void                          set(string $key, GlossaryTranslationEntity $entity)
 * @method GlossaryTranslationEntity[]    getIterator()
 * @method GlossaryTranslationEntity[]    getElements()
 * @method GlossaryTranslationEntity|null get(string $key)
 * @method GlossaryTranslationEntity|null first()
 * @method GlossaryTranslationEntity|null last()
 */
class GlossaryTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return GlossaryTranslationEntity::class;
    }
}