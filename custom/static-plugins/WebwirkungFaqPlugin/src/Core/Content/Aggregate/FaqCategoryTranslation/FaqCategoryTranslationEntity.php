<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\Aggregate\FaqCategoryTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;
use Webwirkung\FaqPlugin\Core\Content\FaqCategory\FaqCategoryEntity;

class FaqCategoryTranslationEntity extends TranslationEntity
{
    protected string $faqCategoryId;

    protected ?string $name;

    protected FaqCategoryEntity $faqCategory;

    public function getFaqCategoryId(): string
    {
        return $this->faqCategoryId;
    }

    public function setFaqCategoryId(string $faqCategoryId): void
    {
        $this->faqCategoryId = $faqCategoryId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFaqCategory(): FaqCategoryEntity
    {
        return $this->faqCategory;
    }

    public function setFaqCategory(FaqCategoryEntity $faqCategory): void
    {
        $this->faqCategory = $faqCategory;
    }
}