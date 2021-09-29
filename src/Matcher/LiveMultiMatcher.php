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

namespace Madcoders\SyliusSizechartPlugin\Matcher;

use Madcoders\SyliusSizechartPlugin\Entity\SizeChartInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class LiveMultiMatcher implements MatcherInterface
{
    /** @var RepositoryInterface */
    private $sizeChartRepository;

    /** @var SizeChartValidatorInterface */
    private $sizeChartValidator;

    public function __construct(
        RepositoryInterface         $sizeChartRepository,
        SizeChartValidatorInterface $sizeChartValidator
    )
    {
        $this->sizeChartRepository = $sizeChartRepository;
        $this->sizeChartValidator = $sizeChartValidator;
    }

    /**
     * @param ProductInterface $product
     *
     * @return SizeChartInterface[]
     */
    public function match(ProductInterface $product): array
    {
        /** @var SizeChartInterface[] $sizeCharts */
        $sizeCharts = $this->sizeChartRepository->findBy(['enabled' => true]);

        $matches = [];
        foreach ($sizeCharts as $sizeChart) {
            if ($this->sizeChartValidator->isValidForTheProduct($sizeChart, $product)) {
                $matches[] = $sizeChart;
            }
        }

        return $matches;
    }
}
