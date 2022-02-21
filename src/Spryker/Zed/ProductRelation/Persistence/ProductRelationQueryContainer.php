<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTypeTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationPersistenceFactory getFactory()
 */
class ProductRelationQueryContainer extends AbstractQueryContainer implements ProductRelationQueryContainerInterface
{
    /**
     * @var string
     */
    public const COL_ASSIGNED_CATEGORIES = 'assignedCategories';

    /**
     * @var string
     */
    public const COL_NUMBER_OF_RELATED_PRODUCTS = 'numberOfRelatedProducts';

    /**
     * @var string
     */
    public const COL_CATEGORY_NAME = 'category_name';

    /**
     * @var string
     */
    public const COL_NAME = 'name';

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    public const COL_SKU = 'sku';

    /**
     * @var string
     */
    public const COL_IS_ACTIVE_AGGREGATION = 'is_active_aggregation';

    /**
     * @var string
     */
    public const COL_PRICE_PRODUCT = 'spy_price_product.price';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery
     */
    public function queryProductRelationType()
    {
        return $this->getFactory()
            ->createProductRelationTypeQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryAllProductRelations()
    {
        return $this->getFactory()
            ->createProductRelationQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery
     */
    public function queryProductRelationTypeByKey($key)
    {
        return $this->getFactory()
            ->createProductRelationTypeQuery()
            ->filterByKey($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationByIdProductRelation($idProductRelation)
    {
        return $this->getFactory()
            ->createProductRelationQuery()
            ->filterByIdProductRelation($idProductRelation);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductRelationType
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationByIdRelationType($idProductRelationType)
    {
        return $this->getFactory()
            ->createProductRelationQuery()
            ->filterByFkProductRelationType($idProductRelationType);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $relationKey
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationByIdProductAbstractAndRelationKey($idProductAbstract, $relationKey)
    {
        return $this->getFactory()
            ->createProductRelationQuery()
            ->useSpyProductRelationTypeQuery()
                ->filterByKey($relationKey)
            ->endUse()
            ->filterByFkProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductRelation
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryProductRelationProductAbstractByIdRelationAndIdProduct($idProductRelation, $idProductAbstract)
    {
        return $this->getFactory()
            ->createProductRelationProductAbstractQuery()
            ->filterByFkProductRelation($idProductRelation)
            ->filterByFkProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryProductRelationProductAbstractByIdProductRelation($idProductRelation)
    {
        return $this->getFactory()
            ->createProductRelationProductAbstractQuery()
            ->filterByFkProductRelation($idProductRelation);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Propel\Runtime\ActiveQuery\Criteria
     */
    public function queryProductsWithCategoriesByFkLocale($idLocale)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->leftJoinSpyProduct()
            ->select([
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
                SpyProductAbstractLocalizedAttributesTableMap::COL_DESCRIPTION,
                static::COL_PRICE_PRODUCT,
                SpyProductImageTableMap::COL_EXTERNAL_URL_SMALL,
            ])
            ->withColumn(
                sprintf(
                    'GROUP_CONCAT(%s)',
                    SpyCategoryAttributeTableMap::COL_NAME,
                ),
                static::COL_ASSIGNED_CATEGORIES,
            )
            ->leftJoinPriceProduct()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->leftJoinSpyProductCategory()
            ->useSpyProductImageSetQuery()
                ->filterByFkLocale($idLocale)
                ->_or()
                ->filterByFkLocale(null)
                ->useSpyProductImageSetToProductImageQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithSpyProductImage()
                ->endUse()
            ->endUse()
            ->addJoin(
                [SpyProductCategoryTableMap::COL_FK_CATEGORY, $idLocale],
                [SpyCategoryAttributeTableMap::COL_FK_CATEGORY, SpyCategoryAttributeTableMap::COL_FK_LOCALE],
                Criteria::LEFT_JOIN,
            )
            ->withColumn(
                'GROUP_CONCAT(' . SpyProductTableMap::COL_IS_ACTIVE . ')',
                static::COL_IS_ACTIVE_AGGREGATION,
            )
            ->addGroupByColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->addGroupByColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME)
            ->addGroupByColumn(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addGroupByColumn(static::COL_PRICE_PRODUCT);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductRelationsWithProductCount($idLocale)
    {
        $query = $this->getFactory()
            ->createProductRelationQuery()
            ->select([
                SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION,
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductRelationTypeTableMap::COL_KEY,
                SpyProductRelationTableMap::COL_IS_ACTIVE,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            ])
            ->joinSpyProductAbstract()
            ->joinSpyProductRelationProductAbstract('num_alias')
            ->useSpyProductAbstractQuery()
                ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse()
            ->withColumn("COUNT('num_alias')", static::COL_NUMBER_OF_RELATED_PRODUCTS)
            ->joinSpyProductRelationType()
            ->groupByIdProductRelation();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductRelation
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryProductRelationWithProductAbstractByIdRelationAndLocale($idProductRelation, $idLocale)
    {
        return $this->queryProductRelationProductAbstractByIdProductRelation($idProductRelation)
            ->useSpyProductRelationQuery()
                ->joinSpyProductRelationType()
            ->endUse()
            ->useSpyProductAbstractQuery()
                ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
                ->useSpyUrlQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse()
            ->select([
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
                SpyProductRelationProductAbstractTableMap::COL_ORDER,
                SpyUrlTableMap::COL_URL,
                SpyProductRelationTypeTableMap::COL_KEY,
            ]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getRulePropelQuery(ProductRelationTransfer $productRelationTransfer)
    {
        return $this->getFactory()
            ->createCatalogPriceRuleQueryCreator()
            ->createQuery($productRelationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryActiveProductRelations()
    {
        return $this->getFactory()
            ->createProductRelationQuery()
            ->filterByIsActive(true);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function queryActiveProductRelationProductAbstract()
    {
        /** @phpstan-var \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery */
        return $this->getFactory()
            ->createProductRelationProductAbstractQuery()
            ->joinSpyProductAbstract()
            ->useSpyProductRelationQuery()
              ->filterByIsActive(true)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAttributeKey();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryActiveAndScheduledRelations()
    {
        return $this->queryActiveProductRelations()
            ->filterByIsRebuildScheduled(true);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryRulePropelQueryWithLocalizedProductData(ProductRelationTransfer $productRelationTransfer)
    {
        return $this->getRulePropelQuery($productRelationTransfer)
            ->clearSelectColumns()
            ->withColumn(
                'GROUP_CONCAT(' . SpyProductTableMap::COL_IS_ACTIVE . ')',
                static::COL_IS_ACTIVE_AGGREGATION,
            )
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::COL_ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, static::COL_SKU)
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::COL_NAME)
            ->withColumn(
                'GROUP_CONCAT(DISTINCT ' . SpyCategoryAttributeTableMap::COL_NAME . ')',
                static::COL_CATEGORY_NAME,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery|\Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function queryProductsWithCategoriesRelationsByFkLocaleAndIdRelation($idLocale, $idProductRelation)
    {
        return $this->queryProductsWithCategoriesByFkLocale($idLocale)
            ->useSpyProductRelationProductAbstractQuery()
                ->filterByFkProductRelation($idProductRelation)
            ->endUse();
    }
}
