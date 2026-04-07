<?php declare(strict_types=1);

namespace Webwirkung\FaqPlugin\Core\Content\FaqCategory;

use Webwirkung\FaqPlugin\Core\Content\Faq\FaqCollection;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class FaqCategoryEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $name;
    protected $faq;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getFaq() {
        return $this->faq;
    }

    public function setFaq(FaqCollection $faq): void
    {
        $this->faq = $faq;
    }

}