<?php

/*
 * This file is part of package:
 * Sylius Size Chart Plugin
 *
 * @copyright MADCODERS Team (www.madcoders.co)
 * @licence For the full copyright and license information, please view the LICENSE
 *
 * Architects of this package:
 * @author Leonid Moshko <l.moshko@madcoders.pl>
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 */

declare(strict_types=1);

namespace Madcoders\SyliusSizechartPlugin\Entity;

use Doctrine\Common\Comparable;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

class SizeChart implements Comparable, SizeChartInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    use ToggleableTrait;

    use TimestampableTrait;

    /** @var int */
    private $id;

    /** @var string|null */
    private $code;

    /** @var array */
    private $criteria = [];

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->enabled = false;
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function setCriteria(array $criteria): void
    {
        $this->criteria = $criteria;
    }

    public function getName(): ?string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(?string $name): void
    {
        $this->getTranslation()->setName($name);
    }

    public static function getTranslationClass(): string
    {
        return SizeChartTranslation::class;
    }

    public function compareTo($other): int
    {
        return $this->code === $other->getCode() ? 0 : 1;
    }

    public function getDescription(): ?string
    {
        return $this->getTranslation()->getDescription();
    }

    public function setDescription(?string $description): void
    {
        $this->getTranslation()->setDescription($description);
    }

    /**
     * @param string|null $locale
     * @return SizeChartTranslationInterface
     */
    public function getTranslation(?string $locale = null): TranslationInterface
    {
        /** @var SizeChartTranslationInterface $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    protected function createTranslation(): SizeChartTranslationInterface
    {
        return new SizeChartTranslation();
    }
}
