<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\Aggregate;

use Webwirkung\FaqPlugin\Core\Content\Faq\FaqDefinition;
use Webwirkung\FaqPlugin\Core\Content\FaqCategory\FaqCategoryDefinition;

use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;

class FaqCategoryMappingDefinition extends MappingEntityDefinition
{
    public const ENTITY_NAME = 'ww_faq_faq_category';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('faq_id', 'faqId', FaqDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('faq_category_id', 'faqCategoryId', FaqCategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('faq', 'faq_id', FaqDefinition::class),
            new ManyToOneAssociationField('faqCategory', 'faq_category_id', FaqCategoryDefinition::class),
            new CreatedAtField(),
        ]);
    }
}
