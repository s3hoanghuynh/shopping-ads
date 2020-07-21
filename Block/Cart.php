<?php

/**
 * SourceKnowledge Shopping Ads
 *
 * PHP version 7
 *
 * Copyright © Sourceknowledge. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 Sourceknowledge. All rights reserved.
 * @license   LICENSE.txt GNU GENERAL PUBLIC LICENSE
 * @link      https://www.sourceknowledge.com/
 */

namespace Sourceknowledge\ShoppingAds\Block;

use Magento\Framework\Exception\LocalizedException;
use Sourceknowledge\ShoppingAds\Model\Pixel;

/**
 * Class Cart
 * Responsible to load Cart Info for pixel.
 *
 * @category  SourceKnowledge
 * @package   Sourceknowledge_ShoppingAds
 * @author    SourceKnowledge Development <dev@sourceknowledge.com>
 * @copyright 2020 Sourceknowledge. All rights reserved.
 * @license   LICENSE.txt GNU GENERAL PUBLIC LICENSE
 * @link      https://www.sourceknowledge.com/
 */
class Cart extends AbstractPixel
{
    /**
     * Gets query param for the pixel
     *
     * @return string
     */
    public function getQueryParams()
    {
        $data = [];
        try {
            $productIds = $this->getProductIds();
            if (!empty($productIds)) {
                $data = [Pixel::VAR_PRODUCT_ID => implode(',', $productIds)];
            }
        } catch (LocalizedException $e) {
            // Silently exit, no valid order data.
            $data[Pixel::VAR_ERROR] = $this->helper->getErrorInfo(__CLASS__, $e);
        }

        return $this->buildQueryParams(Pixel::EVENT_TYPE_CART, $data);
    }


    /**
     * Gets Product Ids
     *
     * @return array
     * @throws LocalizedException
     */
    protected function getProductIds()
    {
        $productIds = [];
        $quote      = $this->getCurrentQuote();
        if (empty($quote)) {
            return [];
        }

        foreach ($quote->getAllVisibleItems() as $item) {
            $productIds[] = $item->getId();
        }

        return $productIds;
    }
}
