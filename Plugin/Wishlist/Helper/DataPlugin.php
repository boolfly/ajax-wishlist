<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Ajax Wishlist
 */
namespace Boolfly\AjaxWishlist\Plugin\Wishlist\Helper;

use Magento\Framework\App\Http\Context;
use Magento\Wishlist\Helper\Data as HelperWishlist;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Customer\Model\Context as CustomerContext;

/**
 * Class DataPlugin
 *
 * @package Boolfly\AjaxWishlist\Plugin\Wishlist\Helper
 * @see HelperWishlist
 */
class DataPlugin
{
    /**
     * @var PostHelper
     */
    protected $postHelper;

    /**
     * @var Http|RequestInterface
     */
    protected $request;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var Context
     */
    private $httpContext;

    /**
     * @var boolean
     */
    private $isWishlistPage;

    /**
     * DataPlugin constructor.
     *
     * @param PostHelper $postHelper
     * @param Context $httpContext
     * @param Json $serializer
     * @param RequestInterface $request
     */
    public function __construct(
        PostHelper $postHelper,
        Context $httpContext,
        Json $serializer,
        RequestInterface $request
    ) {
        $this->postHelper = $postHelper;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->httpContext = $httpContext;
    }

    /**
     * Check Is Wishlist Page
     *
     * @return boolean
     */
    private function isWistlistPage()
    {
        if ($this->isWishlistPage === null) {
            $this->isWishlistPage = $this->request->getFullActionName() === 'wishlist_index_index';
        }

        return $this->isWishlistPage;
    }

    /**
     * Check Is Logged In
     *
     * @return bool
     */
    protected function isLoggedIn()
    {
        return (bool)$this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }

    /**
     * @param HelperWishlist $subject
     * @param $result
     * @param $item
     * @param array $params
     * @return string
     */
    public function afterGetAddParams(HelperWishlist $subject, $result, $item, $params = [])
    {
        if ($this->isLoggedIn() && is_string($result)) {
            $data = $this->serializer->unserialize($result);
            if (!empty($data['action']) && !empty($data['data'])) {
                $data['data']['isAjax'] = true;
                $data['data']['showLoader'] = $this->isProductPage();
                return $this->serializer->serialize($data);
            }
        }

        return $result;
    }

    /**
     * Check Is Product Page
     *
     * @return boolean
     */
    private function isProductPage()
    {
        return $this->request->getFullActionName() === 'catalog_product_view';
    }

    /**
     * @param HelperWishlist $subject
     * @param $result
     * @param $item
     * @param bool $addReferer
     * @return bool|string
     */
    public function afterGetRemoveParams(HelperWishlist $subject, $result, $item,  $addReferer = false)
    {
        if ($this->isLoggedIn() && !$this->isWistlistPage() && is_string($result)) {
            $data = $this->serializer->unserialize($result);
            if (!empty($data['action']) && !empty($data['data'])) {
                $data['data']['isAjax'] = true;
                $data['data']['showLoader'] = true;
                return $this->serializer->serialize($data);
            }
        }

        return $result;
    }
}
