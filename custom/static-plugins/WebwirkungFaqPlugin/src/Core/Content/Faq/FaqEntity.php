<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\Faq;

use Webwirkung\FaqPlugin\Core\Content\FaqCategory\FaqCategoryCollection;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class FaqEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $question;
    protected ?string $answer;
    protected ?string $variant;
    protected ?bool $hidden;

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): void
    {
        $this->question = $question;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): void
    {
        $this->answer = $answer;
    }

    public function getFaqCategory()
    {
        return $this->faqCategory;
    }
    public function setFaqCategory(FaqCategoryCollection $faqCategory): void
    {
        $this->faqCategory = $faqCategory;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }

    public function setVariant(?string $variant): void
    {
        $this->variant = $variant;
    }

    public function getHidden(): ?string
    {
        return $this->hidden;
    }

    public function setHidden(?string $hidden): void
    {
        $this->hidden = $hidden;
    }

}