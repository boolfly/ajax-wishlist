<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Ajax Wishlist
 */
namespace Boolfly\AjaxWishlist\Plugin;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Wishlist\Controller\AbstractIndex;

/**
 * Class ProductWishlistControllerPlugin
 *
 * @package Boolfly\AjaxWishlist\Plugin\Catalog\Helper
 * @see \Magento\Wishlist\Controller\Index\Add
 * @see \Magento\Wishlist\Controller\Index\Remove
 */
class ProductWishlistControllerPlugin
{
    /**
     * @var Json
     */
    private $resultJson;

    /**
     * ProductWishlistControllerPlugin constructor.
     *
     * @param Json $resultJson
     */
    public function __construct(
        Json $resultJson
    ) {
        $this->resultJson = $resultJson;
    }

    /**
     * @param AbstractIndex $subject
     * @param ResultInterface $result
     * @return $this|ResultInterface
     */
    public function afterExecute(AbstractIndex $subject, ResultInterface $result)
    {
        if ($subject->getRequest()->getParam('isAjax', false)) {
            return $this->resultJson->setData([
                'errors' => false,
                'messages' => __('Success')
            ]);
        }

        return $result;
    }
}
