<?php declare(strict_types=1);

namespace Webwirkung\GlossaryPlugin\Core\Content\Glossary;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Webwirkung\GlossaryPlugin\Core\Content\Glossary\Aggregate\GlossaryTranslation\GlossaryTranslationDefinition;

class GlossaryDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'ww_glossary';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return GlossaryEntity::class;
    }

    public function getCollectionClass(): string
    {
        return GlossaryCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey(), new ApiAware()),
            (new TranslatedField('name'))->addFlags(new ApiAware(), new Required()),
            (new TranslatedField('description'))->addFlags(new ApiAware(), new Required()),
            (new BoolField('active', 'active'))->addFlags(new ApiAware()),

            (new TranslationsAssociationField(
                GlossaryTranslationDefinition::class,
                'ww_glossary_id'
            ))->addFlags(new ApiAware(), new Required())
        ]);
    }
}
