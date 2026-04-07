<?php declare(strict_types=1);

namespace Webwirkung\GlossaryPlugin\Core\Content\Glossary\Aggregate\GlossaryTranslation;


use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;
use Webwirkung\GlossaryPlugin\Core\Content\Glossary\GlossaryEntity;

class GlossaryTranslationEntity extends TranslationEntity
{
    protected string $glossaryId;

    protected ?string $name;
    protected ?string $description;

    protected GlossaryEntity $glossary;

    public function getGlossaryId(): string
    {
        return $this->glossaryId;
    }

    public function setGlossaryId(string $glossaryId): void
    {
        $this->glossaryId = $glossaryId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getGlossary(): GlossaryEntity
    {
        return $this->glossary;
    }

    public function setGlossary(GlossaryEntity $glossary): void
    {
        $this->glossary = $glossary;
    }
}