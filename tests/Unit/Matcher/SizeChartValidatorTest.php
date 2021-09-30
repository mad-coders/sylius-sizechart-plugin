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
use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
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
        $validator = new SizeChartValidator('en_US');

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
        $attributeA = $this->prophesize(ProductAttributeInterface::class);
        $attributeA->getType()->willReturn(TextAttributeType::TYPE);
        $attributeB = $this->prophesize(ProductAttributeInterface::class);
        $attributeB->getType()->willReturn(TextAttributeType::TYPE);

        $valueX = $this->prophesize(ProductAttributeValueInterface::class);
        $valueX->getValue()->willReturn('x');
        $valueX->getAttribute()->willReturn($attributeA->reveal());
        $valueY = $this->prophesize(ProductAttributeValueInterface::class);
        $valueY->getValue()->willReturn('y');
        $valueY->getAttribute()->willReturn($attributeB->reveal());

        $product = $this->prophesize(ProductInterface::class);
        $product->getAttributeByCodeAndLocale('A', Argument::any())->willReturn($valueX->reveal());
        $product->getAttributeByCodeAndLocale('B', Argument::any())->willReturn($valueY->reveal());

        $sizeChart = $this->prophesize(SizeChartInterface::class);
        $sizeChart->getCriteria()->willReturn([
            'attributes' => [ 'A', 'B' ],
            'attributeA' => 'x',
            'attributeB' => 'y',
        ]);

        $validator = new SizeChartValidator('en_US');

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
        $attributeA = $this->prophesize(ProductAttributeInterface::class);
        $attributeA->getType()->willReturn(TextAttributeType::TYPE);
        $attributeB = $this->prophesize(ProductAttributeInterface::class);
        $attributeB->getType()->willReturn(TextAttributeType::TYPE);

        $valueX = $this->prophesize(ProductAttributeValueInterface::class);
        $valueX->getValue()->willReturn('x');
        $valueX->getAttribute()->willReturn($attributeA->reveal());
        $valueY = $this->prophesize(ProductAttributeValueInterface::class);
        $valueY->getValue()->willReturn('VALUE_THAT_IS_NOT_SELECTED_IN_CRITERIA');
        $valueY->getAttribute()->willReturn($attributeB->reveal());

        $product = $this->prophesize(ProductInterface::class);
        $product->getAttributeByCodeAndLocale('A', Argument::any())->willReturn($valueX->reveal());
        $product->getAttributeByCodeAndLocale('B', Argument::any())->willReturn($valueY->reveal());

        $sizeChart = $this->prophesize(SizeChartInterface::class);
        $sizeChart->getCriteria()->willReturn([
            'attributes' => [ 'A', 'B' ],
            'attributeA' => 'x',
            'attributeB' => 'y',
        ]);

        $validator = new SizeChartValidator('en_US');

        // when
        $result = $validator->isValidForTheProduct($sizeChart->reveal(), $product->reveal());

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    function it_supports_select_attributes()
    {
        // given
        $attributeA = $this->prophesize(ProductAttributeInterface::class);
        $attributeA->getType()->willReturn(SelectAttributeType::TYPE);
        $attributeB = $this->prophesize(ProductAttributeInterface::class);
        $attributeB->getType()->willReturn(SelectAttributeType::TYPE);

        $valueX = $this->prophesize(ProductAttributeValueInterface::class);
        $valueX->getValue()->willReturn(['x']);
        $valueX->getAttribute()->willReturn($attributeA->reveal());
        $valueY = $this->prophesize(ProductAttributeValueInterface::class);
        $valueY->getValue()->willReturn(['y']);
        $valueY->getAttribute()->willReturn($attributeB->reveal());

        $product = $this->prophesize(ProductInterface::class);
        $product->getAttributeByCodeAndLocale('A', Argument::any())->willReturn($valueX->reveal());
        $product->getAttributeByCodeAndLocale('B', Argument::any())->willReturn($valueY->reveal());

        $sizeChart = $this->prophesize(SizeChartInterface::class);
        $sizeChart->getCriteria()->willReturn([
            'attributes' => [ 'A', 'B' ],
            'attributeA' => 'x',
            'attributeB' => 'y',
        ]);

        $validator = new SizeChartValidator('en_US');

        // when
        $result = $validator->isValidForTheProduct($sizeChart->reveal(), $product->reveal());

        // then
        $this->assertTrue($result);
    }
}
