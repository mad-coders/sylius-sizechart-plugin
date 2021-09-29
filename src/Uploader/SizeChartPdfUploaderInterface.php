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

namespace Madcoders\SyliusSizechartPlugin\Uploader;

use Madcoders\SyliusSizechartPlugin\Entity\SizeChartTranslationInterface;

interface SizeChartPdfUploaderInterface
{
    public function upload(SizeChartTranslationInterface $sizeChartTranslation): void;

    public function remove(string $path): bool;
}
