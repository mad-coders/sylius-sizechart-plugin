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

namespace Tests\Madcoders\SyliusSizechartPlugin\Unit\Matcher;

use Madcoders\SyliusSizechartPlugin\Entity\SizeChart;
use Madcoders\SyliusSizechartPlugin\Matcher\LiveMatcher;
use Madcoders\SyliusSizechartPlugin\Matcher\SizeChartValidatorInterface;
use Prophecy\Argument;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\Madcoders\SyliusSizechartPlugin\Unit\AbstractUnit;

class LiveMatcherTest extends AbstractUnit
{
    /**
     * @test
     */
    function it_returns_first_match_only()
    {
        // given
        $sizeChartRepository = $this->prophesize(RepositoryInterface::class);
        $sizeChartValidator = $this->prophesize(SizeChartValidatorInterface::class);
        $product = $this->prophesize(ProductInterface::class);
        $sizeChartA = new SizeChart();
        $sizeChartB = new SizeChart();

        $sizeChartRepository->findBy(Argument::any())->willReturn([ $sizeChartA, $sizeChartB ]);
        $sizeChartValidator->isValidForTheProduct($sizeChartA, $product->reveal())->willReturn(true);
        $sizeChartValidator->isValidForTheProduct($sizeChartB, $product->reveal())->willReturn(true);

        $matcher = new LiveMatcher($sizeChartRepository->reveal(), $sizeChartValidator->reveal());

        // when
        $result = $matcher->match($product->reveal());

        // then
        $this->assertEquals([$sizeChartA], $result);
    }

    /**
     * @test
     */
    function it_returns_empty_array_when_nothing_is_matched()
    {
        // given
        $sizeChartRepository = $this->prophesize(RepositoryInterface::class);
        $sizeChartValidator = $this->prophesize(SizeChartValidatorInterface::class);
        $product = $this->prophesize(ProductInterface::class);
        $sizeChartA = new SizeChart();
        $sizeChartB = new SizeChart();

        $sizeChartRepository->findBy(Argument::any())->willReturn([ $sizeChartA, $sizeChartB ]);
        $sizeChartValidator->isValidForTheProduct($sizeChartA, $product->reveal())->willReturn(false);
        $sizeChartValidator->isValidForTheProduct($sizeChartB, $product->reveal())->willReturn(false);

        $matcher = new LiveMatcher($sizeChartRepository->reveal(), $sizeChartValidator->reveal());

        // when
        $result = $matcher->match($product->reveal());

        // then
        $this->assertEquals([], $result);
    }
}
