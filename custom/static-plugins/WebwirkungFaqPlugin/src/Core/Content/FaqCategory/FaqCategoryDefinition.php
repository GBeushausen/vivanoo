<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\FaqCategory;

use Webwirkung\FaqPlugin\Core\Content\Aggregate\FaqCategoryMappingDefinition;
use Webwirkung\FaqPlugin\Core\Content\Aggregate\FaqCategoryTranslation\FaqCategoryTranslationDefinition;
use Webwirkung\FaqPlugin\Core\Content\Faq\FaqDefinition;

use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;

class FaqCategoryDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'ww_faq_category';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new TranslatedField('name'))->addFlags(new ApiAware(), new Required()),
            new ManyToManyAssociationField(
                'faq',
                FaqDefinition::class,
                FaqCategoryMappingDefinition::class,
                'faq_category_id',
                'faq_id'
            ),
            (new TranslationsAssociationField(
                FaqCategoryTranslationDefinition::class,
                'ww_faq_category_id'
            ))->addFlags(new ApiAware(), new Required())
        ]);
    }

    public function getEntityClass(): string
    {
        return FaqCategoryEntity::class;
    }

    public function getCollectionClass(): string
    {
        return FaqCategoryCollection::class;
    }
}