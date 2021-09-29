<?php

/*
 * This file is part of package
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

namespace Tests\Madcoders\SyliusSizechartPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Madcoders\SyliusSizechartPlugin\Entity\SizeChart;
use Madcoders\SyliusSizechartPlugin\Entity\SizeChartInterface;
use Madcoders\SyliusSizechartPlugin\Entity\SizeChartTranslationInterface;
use Madcoders\SyliusSizechartPlugin\Uploader\SizeChartPdfUploaderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\Madcoders\SyliusSizechartPlugin\Behat\Page\Admin\SizeChart\IndexPageInterface;
use Webmozart\Assert\Assert;

class SizeChartContext  implements Context
{
    /** @var IndexPageInterface */
    private $indexPage;

    /** @var RepositoryInterface */
    private $sizeChartRepository;

    /** @var SizeChartPdfUploaderInterface */
    private $uploader;

    /** @var array */
    private $minkParameters;

    public function __construct(
        IndexPageInterface $indexPage,
        RepositoryInterface $sizeChartRepository,
        SizeChartPdfUploaderInterface $uploader,
        $minkParameters
    )
    {
        $this->indexPage = $indexPage;
        $this->sizeChartRepository = $sizeChartRepository;
        $this->uploader = $uploader;
        $this->minkParameters = $minkParameters;
    }

    /**
     * @Given there are size charts witch :code and :name in :language
     */
    public function thereAreSizeChart(string $code, string $name, string $language = 'en_US'): void
    {
        $sizeChart = new SizeChart();
        $sizeChart->setCode($code);
        $sizeChart->setCurrentLocale($language);
        $sizeChart->setName($name);

        $this->sizeChartRepository->add($sizeChart);
    }

    /**
     * @Given this size chart with code :code has :path file in :language
     */
    public function thereAreSizeChartWithFile(string $code, string $patch, ?string $language = 'en_US'): void
    {
        $filesPath = $this->getParameter('files_path');
        $file = new UploadedFile($filesPath . $patch, basename($patch));
        $sizeChart = $this->findSizeChartByCode($code);

        $sizeChart->getTranslation($language)->setFile($file);

        /** @var SizeChartTranslationInterface $translate */
        $translate = $sizeChart->getTranslation($language);
        $this->uploader->upload($translate);

        $this->sizeChartRepository->add($sizeChart);
    }

    /**
     * @When I delete the size chart with code :sizeChartCode
     */
    public function iDeleteSizeChartByCode(string $code): void
    {
        $this->indexPage->deleteResourceOnPage(['code' => $code]);
    }

    /**
     * @param string $name
     *
     * @return NodeElement
     */
    private function getParameter($name)
    {
        return $this->minkParameters[$name] ?? null;
    }

    private function findSizeChartByCode(string $code): SizeChartInterface
    {
        $sizeChart = $this->sizeChartRepository->findOneBy(['code' => $code]);
        Assert::notNull($sizeChart);

        return $sizeChart;
    }
}
