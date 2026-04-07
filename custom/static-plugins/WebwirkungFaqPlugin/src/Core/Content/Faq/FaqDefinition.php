<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\Faq;

use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Webwirkung\FaqPlugin\Core\Content\Aggregate\FaqCategoryMappingDefinition;
use Webwirkung\FaqPlugin\Core\Content\Aggregate\FaqTranslation\FaqTranslationDefinition;
use Webwirkung\FaqPlugin\Core\Content\FaqCategory\FaqCategoryDefinition;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;

class FaqDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'ww_faq';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new TranslatedField('question'))->addFlags(new ApiAware(), new Required()),
            (new TranslatedField('answer'))->addFlags(new ApiAware()),
            (new TranslatedField('variant'))->addFlags(new ApiAware()),
            (new BoolField('hidden', 'hidden'))->addFlags(new AllowHtml()),
            new ManyToManyAssociationField(
               'faqCategory',
               FaqCategoryDefinition::class,
                FaqCategoryMappingDefinition::class,
               'faq_id',
               'faq_category_id'),

            (new TranslationsAssociationField(
                FaqTranslationDefinition::class,
                'ww_faq_id'
            ))->addFlags(new ApiAware(), new Required())

        ]);
    }

    public function getEntityClass(): string
    {
        return FaqEntity::class;
    }

    public function getCollectionClass(): string
    {
        return FaqCollection::class;
    }
}