<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Tax\Pricing;

use Magento\Framework\Pricing\Object\SaleableInterface;

class AdjustmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Adjustment
     */
    protected $adjustment;

    /**
     * @var \Magento\Tax\Helper\Data | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $taxHelper;

    /**
     * @var int
     */
    protected $sortOrder = 5;

    public function setUp()
    {
        $this->taxHelper = $this->getMock('Magento\Tax\Helper\Data', [], [], '', false);
        $this->adjustment = new Adjustment($this->taxHelper, $this->sortOrder);
    }

    public function testGetAdjustmentCode()
    {
        $this->assertEquals(Adjustment::ADJUSTMENT_CODE, $this->adjustment->getAdjustmentCode());
    }

    /**
     * @param bool $expectedResult
     * @dataProvider isIncludedInBasePriceDataProvider
     */
    public function testIsIncludedInBasePrice($expectedResult)
    {
        $this->taxHelper->expects($this->once())
            ->method('priceIncludesTax')
            ->will($this->returnValue($expectedResult));
        $this->assertEquals($expectedResult, $this->adjustment->isIncludedInBasePrice());
    }

    public function isIncludedInBasePriceDataProvider()
    {
        return [[true], [false]];
    }

    /**
     * @dataProvider isIncludedInDisplayPriceDataProvider
     */
    public function testIsIncludedInDisplayPrice($displayPriceIncludingTax, $displayBothPrices, $expectedResult)
    {
        $this->taxHelper->expects($this->once())
            ->method('displayPriceIncludingTax')
            ->will($this->returnValue($displayPriceIncludingTax));
        if (!$displayPriceIncludingTax) {
            $this->taxHelper->expects($this->once())
                ->method('displayBothPrices')
                ->will($this->returnValue($displayBothPrices));
        }

        $this->assertEquals($expectedResult, $this->adjustment->isIncludedInDisplayPrice());
    }

    /**
     * @return array
     */
    public function isIncludedInDisplayPriceDataProvider()
    {
        return [
            [false, false, false],
            [false, true, true],
            [true, false, true],
            [true, true, true],
        ];
    }

    /**
     * @param float $amount
     * @param bool $isPriceIncludesTax
     * @param float $price
     * @param float $expectedResult
     * @dataProvider extractAdjustmentDataProvider
     */
    public function testExtractAdjustment($isPriceIncludesTax, $amount, $price, $expectedResult)
    {
        $object = $this->getMockForAbstractClass('Magento\Framework\Pricing\Object\SaleableInterface');

        $this->taxHelper->expects($this->any())
            ->method('priceIncludesTax')
            ->will($this->returnValue($isPriceIncludesTax));
        $this->taxHelper->expects($this->any())
            ->method('getPrice')
            ->with($object, $amount)
            ->will($this->returnValue($price));
        $this->taxHelper->expects($this->any())
            ->method('getPriceUnrounded')
            ->with($object, $amount)
            ->will($this->returnValue($price));

        $this->assertEquals($expectedResult, $this->adjustment->extractAdjustment($amount, $object));
    }

    public function extractAdjustmentDataProvider()
    {
        return [
            [false, 'not_important', 'not_important', 0.00],
            [true, 10.1, 0.2, 9.9],
            [true, 10.1, 20.3, -10.2],
            [true, 0.0, 0.0, 0],
        ];
    }

    /**
     * @param bool $isPriceIncludesTax
     * @param float $amount
     * @param float $price
     * @param $expectedResult
     * @dataProvider applyAdjustmentDataProvider
     */
    public function testApplyAdjustment($isPriceIncludesTax, $amount, $price, $expectedResult)
    {
        $object = $this->getMockBuilder('Magento\Framework\Pricing\Object\SaleableInterface')->getMock();

        $this->taxHelper->expects($this->any())
            ->method('priceIncludesTax')
            ->will($this->returnValue($isPriceIncludesTax));
        $this->taxHelper->expects($this->any())
            ->method('getPrice')
            ->with($object, $amount, !$isPriceIncludesTax)
            ->will($this->returnValue($price));
        $this->taxHelper->expects($this->any())
            ->method('getPriceUnrounded')
            ->with($object, $amount, !$isPriceIncludesTax)
            ->will($this->returnValue($price));

        $this->assertEquals($expectedResult, $this->adjustment->applyAdjustment($amount, $object));
    }

    /**
     * @return array
     */
    public function applyAdjustmentDataProvider()
    {
        return [
            [true, 1.1, 2.2, 2.2],
            [true, 0.0, 2.2, 2.2],
            [true, 1.1, 0.0, 0.0],
        ];
    }

    /**
     * @dataProvider isExcludedWithDataProvider
     * @param string $adjustmentCode
     * @param bool $expectedResult
     */
    public function testIsExcludedWith($adjustmentCode, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->adjustment->isExcludedWith($adjustmentCode));
    }

    public function isExcludedWithDataProvider()
    {
        return [
            [Adjustment::ADJUSTMENT_CODE, true],
            ['not_tax', false]
        ];
    }

    public function testGetSortOrder()
    {
        $this->assertEquals($this->sortOrder, $this->adjustment->getSortOrder());
    }
}
