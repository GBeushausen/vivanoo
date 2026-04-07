<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\Aggregate\FaqTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Webwirkung\FaqPlugin\Core\Content\Faq\FaqDefinition;

class FaqTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'ww_faq_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return FaqTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return FaqDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new LongTextField('question', 'question'))->addFlags(new AllowHtml()),
            (new LongTextField('answer', 'answer'))->addFlags(new AllowHtml()),
            (new LongTextField('variant', 'variant'))->addFlags(new AllowHtml()),
        ]);
    }

}