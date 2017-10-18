<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation\Dependency\Client;

class ProductRelationToPriceProductBridge implements ProductRelationToPriceProductInterface
{
    /**
     * @var \Spryker\Client\PriceProduct\PriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Client\PriceProduct\PriceProductClientInterface $priceProductClient
     */
    public function __construct($priceProductClient)
    {
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param array $priceMap
     *
     * @return array
     */
    public function resolveProductPrice(array $priceMap)
    {
        return $this->priceProductClient->resolveProductPrice($priceMap);
    }

}
