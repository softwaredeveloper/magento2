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
namespace Magento\Catalog\Service\V1\Category\ProductLinks;

use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Service\V1\Data\Category;
use Magento\Catalog\Service\V1\Data\Eav\Category\ProductConverterFactory;
use Magento\Catalog\Service\V1\Data\Eav\Category\ProductLink;
use Magento\Catalog\Service\V1\Data\Eav\Category\ProductLinkBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;

class ReadService implements ReadServiceInterface
{
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var ProductLinkBuilder
     */
    private $productLinkBuilder;

    /**
     * @param CategoryFactory $categoryFactory
     * @param ProductLinkBuilder $productLinkBuilder
     */
    public function __construct(
        CategoryFactory $categoryFactory,
        ProductLinkBuilder $productLinkBuilder
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->productLinkBuilder = $productLinkBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function assignedProducts($categoryId)
    {
        $category = $this->getCategory($categoryId);

        $productsPosition = $category->getProductsPosition();
        /** @var \Magento\Framework\Data\Collection\Db $products */
        $products = $category->getProductCollection();

        /** @var \Magento\Catalog\Service\V1\Data\Eav\Category\Product[] $dtoProductList */
        $dtoProductList = [];

        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($products->getItems() as $productId => $product) {
            $dtoProductList[] = $this->productLinkBuilder->populateWithArray(
                [ProductLink::SKU => $product->getSku(), ProductLink::POSITION => $productsPosition[$productId]]
            )->create();
        }

        return $dtoProductList;
    }

    /**
     * @param int $id
     * @return CategoryModel
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getCategory($id)
    {
        /** @var CategoryModel $category */
        $category = $this->categoryFactory->create();
        $category->load($id);

        if (!$category->getId()) {
            throw NoSuchEntityException::singleField(Category::ID, $id);
        }
        return $category;
    }
}
