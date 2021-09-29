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

use Sylius\Component\Resource\Model\AbstractTranslation;

class SizeChartTranslation extends AbstractTranslation implements SizeChartTranslationInterface, SizeChartPdfAwareInterface
{
    /** @var mixed */
    private $id;

    /** @var string|null */
    private $name;

    /** @var string|null */
    private $description;

    /** @var \SplFileInfo|null */
    private $file;

    /** @var string|null */
    private $filePath;

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \SplFileInfo|null
     */
    public function getFile(): ?\SplFileInfo
    {
        return $this->file;
    }

    /**
     * @param \SplFileInfo|null $file
     */
    public function setFile(?\SplFileInfo $file): void
    {
        $this->file = $file;
    }

    public function hasFile(): bool
    {
        return null !== $this->file;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @param string|null $filePath
     */
    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }
}
