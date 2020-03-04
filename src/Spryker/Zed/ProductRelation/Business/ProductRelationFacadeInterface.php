<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business;

use Generated\Shared\Transfer\ProductRelationCriteriaTransfer;
use Generated\Shared\Transfer\ProductRelationResponseTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;

/**
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationBusinessFactory getFactory()
 */
interface ProductRelationFacadeInterface
{
    /**
     * Specification:
     *  - Create product relation type is not persisted
     *  - Create Product relation
     *  - Save related product based on given query
     *  - Touch product relation collector
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function createProductRelation(ProductRelationTransfer $productRelationTransfer): ProductRelationResponseTransfer;

    /**
     * Specification:
     *  - Create product relation type is not persisted
     *  - Update Product relation
     *  - Save related product based on given query, remove old relations
     *  - Touch product relation collector
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function updateProductRelation(ProductRelationTransfer $productRelationTransfer): ProductRelationResponseTransfer;

    /**
     * Specification:
     *  - Read product relations from persistence
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function findProductRelationById($idProductRelation): ProductRelationResponseTransfer;

    /**
     * Specification:
     * - Finds product relation by provided product relation key.
     *
     * @api
     *
     * @param string $productRelationKey
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationByKey(string $productRelationKey): ?ProductRelationTransfer;

    /**
     * Specification:
     *  - Returns product relation type list as stored in persistence
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer[]
     */
    public function getProductRelationTypeList();

    /**
     * Specification:
     * - Activates product relation and touches relation collector
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function activateProductRelation($idProductRelation);

    /**
     * Specification:
     * - Deactivates product relation and touches relation collector
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function deactivateProductRelation($idProductRelation);

    /**
     * Specification:
     * - Rebuilds all active relations with selected is_rebuild_scheduled = true, reruns stored query.
     *
     * @api
     *
     * @return void
     */
    public function rebuildRelations();

    /**
     * Specification:
     * - Deletes product relation from persistence
     * - Touches delete relation
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function deleteProductRelation(int $idProductRelation): ProductRelationResponseTransfer;

    /**
     * Specification:
     * - Finds product relation by given criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationByCriteria(
        ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
    ): ?ProductRelationTransfer;

    /**
     * Specification:
     * - Finds product abstract with categories and image by provided product abstract id and locale id.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    public function getProductAbstractDataById(int $idProductAbstract, int $idLocale): array;
}
