<?php declare(strict_types=1);

namespace Webwirkung\GlossaryPlugin\Core\Content\Glossary\Aggregate\GlossaryTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Webwirkung\GlossaryPlugin\Core\Content\Glossary\GlossaryDefinition;

class GlossaryTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'ww_glossary_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return GlossaryTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return GlossaryDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new LongTextField('description', 'description'))->addFlags(new Required()),
        ]);
    }

}