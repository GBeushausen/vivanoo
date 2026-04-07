<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\Aggregate\FaqTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;
use Webwirkung\FaqPlugin\Core\Content\Faq\FaqEntity;

class FaqTranslationEntity extends TranslationEntity
{
    protected string $faqId;

    protected ?string $question;
    protected ?string $answer;
    protected ?string $variant;

    protected FaqEntity $faq;

    public function getFaqId(): string
    {
        return $this->faqId;
    }

    public function setFaqId(string $faqId): void
    {
        $this->faqId = $faqId;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }

    public function setVariant(string $variant): void
    {
        $this->variant = $variant;
    }

    public function getFaq(): FaqEntity
    {
        return $this->faq;
    }

    public function setFaq(FaqEntity $faq): void
    {
        $this->faq = $faq;
    }
}