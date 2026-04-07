<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\Aggregate\FaqCategoryTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Webwirkung\FaqPlugin\Core\Content\FaqCategory\FaqCategoryDefinition;

class FaqCategoryTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'ww_faq_category_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return FaqCategoryTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return FaqCategoryDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name')),
        ]);
    }

}