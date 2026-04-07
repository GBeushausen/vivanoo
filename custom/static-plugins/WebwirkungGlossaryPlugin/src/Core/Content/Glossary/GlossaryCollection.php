<?php declare(strict_types=1);

namespace Webwirkung\GlossaryPlugin\Core\Content\Glossary;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void add(GlossaryEntity $entity)
 * @method void set(string $key, GlossaryEntity $entity)
 * @method GlossaryEntity[] getIterator()
 * @method GlossaryEntity[] getElements()
 * @method GlossaryEntity|null get(string $key)
 * @method GlossaryEntity|null first()
 * @method GlossaryEntity|null last()
 */
class GlossaryCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return GlossaryEntity::class;
    }
}
