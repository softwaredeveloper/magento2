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

namespace Magento\UrlRewrite\Test\TestCase;

use Mtf\TestCase\Injectable;
use Magento\UrlRewrite\Test\Fixture\UrlRewrite;
use Magento\UrlRewrite\Test\Page\Adminhtml\UrlrewriteIndex;
use Magento\UrlRewrite\Test\Page\Adminhtml\UrlrewriteEdit;
use Mtf\Fixture\FixtureFactory;

/**
 * Test Creation for Update Product URL Rewrites Entity
 *
 * Test Flow:
 *
 * Preconditions:
 * 1. Create custom store view
 * 2. Create simple product
 * 3. Create product UrlRewrite
 *
 * Steps:
 * 1. Open Backend
 * 2. Go to Marketing->Url Redirects
 * 3. Search and open created Url Redirect
 * 4. Fill data according to dataset
 * 5. Perform all assertions
 *
 * @group URL_Rewrites_(PS)
 * @ZephyrId MAGETWO-24819
 */
class UpdateProductUrlRewriteEntityTest extends Injectable
{
    /**
     * Url rewrite index page
     *
     * @var UrlrewriteIndex
     */
    protected $urlRewriteIndex;

    /**
     * Url rewrite edit page
     *
     * @var UrlrewriteEdit
     */
    protected $urlRewriteEdit;

    /**
     * Prepare dataSets and pages
     *
     * @param UrlrewriteIndex $urlRewriteIndex
     * @param UrlrewriteEdit $urlRewriteEdit
     * @return array
     */
    public function __inject(
        UrlrewriteIndex $urlRewriteIndex,
        UrlrewriteEdit $urlRewriteEdit
    ) {
        $this->urlRewriteIndex = $urlRewriteIndex;
        $this->urlRewriteEdit = $urlRewriteEdit;
    }

    /**
     * Update product URL rewrites
     *
     * @param UrlRewrite $urlRewrite
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function testUpdateProductUrlRewrite(
        UrlRewrite $urlRewrite,
        FixtureFactory $fixtureFactory
    ) {
        /** @var UrlRewrite $productRedirect */
        $productRedirect = $fixtureFactory->createByCode(
            'urlRewrite',
            [
                'dataSet' => 'default',
                'data' => ['id_path' => [$urlRewrite->getIdPath()]]
            ]
        );
        $productRedirect->persist();
        //Steps
        $this->urlRewriteIndex->open();
        $filter = ['request_path' => $productRedirect->getRequestPath()];
        $this->urlRewriteIndex->getUrlRedirectGrid()->searchAndOpen($filter);
        $this->urlRewriteEdit->getFormBlock()->fill($urlRewrite);
        $this->urlRewriteEdit->getPageMainActions()->save();
    }
}
