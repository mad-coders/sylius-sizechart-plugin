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

use Gaufrette\Filesystem;
use Madcoders\SyliusSizechartPlugin\Entity\SizeChartTranslationInterface;
use Madcoders\SyliusSizechartPlugin\Generator\SizeChartPdfPathGenerator;
use Madcoders\SyliusSizechartPlugin\Generator\SizeChartPdfPathGeneratorInterface;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

class SizeChartPdfUploader implements SizeChartPdfUploaderInterface
{
    /** @var Filesystem */
    protected $filesystem;

    /** @var SizeChartPdfPathGeneratorInterface */
    protected $sizeChartPdfPathGenerator;

    public function __construct(
        Filesystem $filesystem,
        ?SizeChartPdfPathGeneratorInterface $sizeChartPdfPathGenerator = null
    ) {
        $this->filesystem = $filesystem;

        if ($sizeChartPdfPathGenerator === null) {
            @trigger_error(sprintf(
                'Not passing an $sizeChartPdfPathGenerator to %s constructor is deprecated since Sylius 1.6 and will be not possible in Sylius 2.0.', self::class
            ), \E_USER_DEPRECATED);
        }

        $this->sizeChartPdfPathGenerator = $sizeChartPdfPathGenerator ?? new SizeChartPdfPathGenerator();
    }

    public function upload(SizeChartTranslationInterface $sizeChartTranslation): void
    {
        if (!$sizeChartTranslation->hasFile()) {
            return;
        }

        $file = $sizeChartTranslation->getFile();

        /** @var File $file */
        Assert::isInstanceOf($file, File::class);

        if (null !== $sizeChartTranslation->getfilePath() && $this->has($sizeChartTranslation->getFilePath())) {
            $this->remove($sizeChartTranslation->getFilePath());
        }

        do {
            $path = $this->sizeChartPdfPathGenerator->generate($sizeChartTranslation);
        } while ($this->isAdBlockingProne($path) || $this->filesystem->has($path));

        $sizeChartTranslation->setFilePath($path);

        $this->filesystem->write(
            $sizeChartTranslation->getFilePath(),
            file_get_contents($sizeChartTranslation->getFile()->getPathname())
        );
    }

    public function remove(string $path): bool
    {
        if ($this->filesystem->has($path)) {
            return $this->filesystem->delete($path);
        }

        return false;
    }

    private function has(string $path): bool
    {
        return $this->filesystem->has($path);
    }

    /**
     * Will return true if the path is prone to be blocked by ad blockers
     */
    private function isAdBlockingProne(string $path): bool
    {
        return strpos($path, 'ad') !== false;
    }
}
