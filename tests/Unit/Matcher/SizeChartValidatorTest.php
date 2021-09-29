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

use Madcoders\SyliusSizechartPlugin\Entity\SizeChartInterface;
use Madcoders\SyliusSizechartPlugin\Matcher\SizeChartValidator;
use Prophecy\Argument;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Tests\Madcoders\SyliusSizechartPlugin\Unit\AbstractUnit;

class SizeChartValidatorTest extends AbstractUnit
{
    /**
     * @test
     */
    function it_returns_true_when_there_are_no_attributes_in_criteria_defined()
    {
        // given
        $product = $this->prophesize(ProductInterface::class);
        $sizeChart = $this->prophesize(SizeChartInterface::class);
        $sizeChart->getCriteria()->willReturn([ 'attributes' => [] ]);
        $validator = new SizeChartValidator();

        // when
        $result = $validator->isValidForTheProduct($sizeChart->reveal(), $product->reveal());

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    function it_returns_true_when_all_criteria_are_matched()
    {
        // given
        $valueX = $this->prophesize(ProductAttributeValueInterface::class);
        $valueX->getValue()->willReturn('x');
        $valueY = $this->prophesize(ProductAttributeValueInterface::class);
        $valueY->getValue()->willReturn('y');

        $product = $this->prophesize(ProductInterface::class);
        $product->getAttributeByCodeAndLocale('A', Argument::any())->willReturn($valueX->reveal());
        $product->getAttributeByCodeAndLocale('B', Argument::any())->willReturn($valueY->reveal());

        $sizeChart = $this->prophesize(SizeChartInterface::class);
        $sizeChart->getCriteria()->willReturn([
            'attributes' => [ 'A', 'B' ],
            'attributeA' => 'x',
            'attributeB' => 'y',
        ]);

        $validator = new SizeChartValidator();

        // when
        $result = $validator->isValidForTheProduct($sizeChart->reveal(), $product->reveal());

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    function it_returns_false_when_a_criteria_fails()
    {
        // given
        $valueX = $this->prophesize(ProductAttributeValueInterface::class);
        $valueX->getValue()->willReturn('x');
        $valueY = $this->prophesize(ProductAttributeValueInterface::class);
        $valueY->getValue()->willReturn('VALUE_THAT_IS_NOT_SELECTED_IN_CRITERIA');

        $product = $this->prophesize(ProductInterface::class);
        $product->getAttributeByCodeAndLocale('A', Argument::any())->willReturn($valueX->reveal());
        $product->getAttributeByCodeAndLocale('B', Argument::any())->willReturn($valueY->reveal());

        $sizeChart = $this->prophesize(SizeChartInterface::class);
        $sizeChart->getCriteria()->willReturn([
            'attributes' => [ 'A', 'B' ],
            'attributeA' => 'x',
            'attributeB' => 'y',
        ]);

        $validator = new SizeChartValidator();

        // when
        $result = $validator->isValidForTheProduct($sizeChart->reveal(), $product->reveal());

        // then
        $this->assertFalse($result);
    }
}
