<?php
/**
 * Copyright (c) 2026. All rights reserved.
 * @author: Volodymyr Hryvinskyi <mailto:volodymyr@hryvinskyi.com>
 */

declare(strict_types=1);

namespace Hryvinskyi\SeoRobotsCategoryFrontend\Model;

use Hryvinskyi\SeoRobotsCategoryApi\Api\GetCategoryRobotsInterface;
use Magento\Framework\App\HttpRequestInterface;
use Magento\Framework\Registry;

/**
 * Provides X-Robots-Tag HTTP header directives based on category settings
 */
class CategoryXRobotsProvider
{
    /**
     * @var GetCategoryRobotsInterface
     */
    private $getCategoryRobots;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param GetCategoryRobotsInterface $getCategoryRobots
     * @param Registry $registry
     */
    public function __construct(
        GetCategoryRobotsInterface $getCategoryRobots,
        Registry $registry
    ) {
        $this->getCategoryRobots = $getCategoryRobots;
        $this->registry = $registry;
    }

    /**
     * Get X-Robots-Tag directives for the current request
     *
     * @param HttpRequestInterface $request
     * @return array Directive array or empty array if no category X-Robots applies
     */
    public function getXRobotsDirectives(HttpRequestInterface $request): array
    {
        // Check if we're on a category page
        if ($request->getFullActionName() === 'catalog_category_view') {
            $category = $this->registry->registry('current_category');
            if ($category) {
                return $this->getCategoryRobots->executeXRobots($category);
            }
        }

        // Check if we're on a product page
        if ($request->getFullActionName() === 'catalog_product_view') {
            $product = $this->registry->registry('current_product');
            if ($product) {
                return $this->getCategoryRobots->executeXRobotsForProduct($product);
            }
        }

        return [];
    }
}
