<?php
/**
 * Copyright (c) 2026. All rights reserved.
 * @author: Volodymyr Hryvinskyi <mailto:volodymyr@hryvinskyi.com>
 */

declare(strict_types=1);

namespace Hryvinskyi\SeoRobotsCategoryFrontend\Plugin;

use Hryvinskyi\SeoRobotsCategoryFrontend\Model\CategoryXRobotsProvider;
use Hryvinskyi\SeoRobotsFrontend\Model\GetXRobotsByRequest;
use Magento\Framework\App\RequestInterface;

/**
 * Plugin to inject category X-Robots-Tag directives into the X-Robots-Tag header
 */
class GetXRobotsByRequestPlugin
{
    /**
     * @var CategoryXRobotsProvider
     */
    private $categoryXRobotsProvider;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param CategoryXRobotsProvider $categoryXRobotsProvider
     * @param RequestInterface $request
     */
    public function __construct(
        CategoryXRobotsProvider $categoryXRobotsProvider,
        RequestInterface $request
    ) {
        $this->categoryXRobotsProvider = $categoryXRobotsProvider;
        $this->request = $request;
    }

    /**
     * Add category X-Robots-Tag directives to the result
     *
     * @param GetXRobotsByRequest $subject
     * @param array $result
     * @return array
     */
    public function afterExecute(GetXRobotsByRequest $subject, array $result): array
    {
        // If there are already X-Robots directives from base rules, use those (higher priority)
        if (!empty($result)) {
            return $result;
        }

        // Try to get category X-Robots directives
        /** @var \Magento\Framework\App\HttpRequestInterface $httpRequest */
        $httpRequest = $this->request;

        $categoryDirectives = $this->categoryXRobotsProvider->getXRobotsDirectives($httpRequest);

        if (!empty($categoryDirectives)) {
            return $categoryDirectives;
        }

        return $result;
    }
}
