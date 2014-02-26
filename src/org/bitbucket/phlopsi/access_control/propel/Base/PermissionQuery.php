<?php

namespace org\bitbucket\phlopsi\access_control\propel\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use org\bitbucket\phlopsi\access_control\propel\Permission as ChildPermission;
use org\bitbucket\phlopsi\access_control\propel\PermissionQuery as ChildPermissionQuery;
use org\bitbucket\phlopsi\access_control\propel\Map\PermissionTableMap;

/**
 * Base class that represents a query for the 'permissions' table.
 *
 *
 *
 * @method     ChildPermissionQuery orderByExternalId($order = Criteria::ASC) Order by the external_id column
 * @method     ChildPermissionQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method     ChildPermissionQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method     ChildPermissionQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 * @method     ChildPermissionQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildPermissionQuery groupByExternalId() Group by the external_id column
 * @method     ChildPermissionQuery groupByTreeLeft() Group by the tree_left column
 * @method     ChildPermissionQuery groupByTreeRight() Group by the tree_right column
 * @method     ChildPermissionQuery groupByTreeLevel() Group by the tree_level column
 * @method     ChildPermissionQuery groupById() Group by the id column
 *
 * @method     ChildPermissionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPermissionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPermissionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPermissionQuery leftJoinPermissionsRoles($relationAlias = null) Adds a LEFT JOIN clause to the query using the PermissionsRoles relation
 * @method     ChildPermissionQuery rightJoinPermissionsRoles($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PermissionsRoles relation
 * @method     ChildPermissionQuery innerJoinPermissionsRoles($relationAlias = null) Adds a INNER JOIN clause to the query using the PermissionsRoles relation
 *
 * @method     ChildPermission findOne(ConnectionInterface $con = null) Return the first ChildPermission matching the query
 * @method     ChildPermission findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPermission matching the query, or a new ChildPermission object populated from the query conditions when no match is found
 *
 * @method     ChildPermission findOneByExternalId(string $external_id) Return the first ChildPermission filtered by the external_id column
 * @method     ChildPermission findOneByTreeLeft(int $tree_left) Return the first ChildPermission filtered by the tree_left column
 * @method     ChildPermission findOneByTreeRight(int $tree_right) Return the first ChildPermission filtered by the tree_right column
 * @method     ChildPermission findOneByTreeLevel(int $tree_level) Return the first ChildPermission filtered by the tree_level column
 * @method     ChildPermission findOneById(int $id) Return the first ChildPermission filtered by the id column
 *
 * @method     array findByExternalId(string $external_id) Return ChildPermission objects filtered by the external_id column
 * @method     array findByTreeLeft(int $tree_left) Return ChildPermission objects filtered by the tree_left column
 * @method     array findByTreeRight(int $tree_right) Return ChildPermission objects filtered by the tree_right column
 * @method     array findByTreeLevel(int $tree_level) Return ChildPermission objects filtered by the tree_level column
 * @method     array findById(int $id) Return ChildPermission objects filtered by the id column
 *
 */
abstract class PermissionQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \org\bitbucket\phlopsi\access_control\propel\Base\PermissionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'access_control', $modelName = '\\org\\bitbucket\\phlopsi\\access_control\\propel\\Permission', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPermissionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPermissionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \org\bitbucket\phlopsi\access_control\propel\PermissionQuery) {
            return $criteria;
        }
        $query = new \org\bitbucket\phlopsi\access_control\propel\PermissionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPermission|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PermissionTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PermissionTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildPermission A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT EXTERNAL_ID, TREE_LEFT, TREE_RIGHT, TREE_LEVEL, ID FROM permissions WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildPermission();
            $obj->hydrate($row);
            PermissionTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildPermission|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PermissionTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PermissionTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the external_id column
     *
     * Example usage:
     * <code>
     * $query->filterByExternalId('fooValue');   // WHERE external_id = 'fooValue'
     * $query->filterByExternalId('%fooValue%'); // WHERE external_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $externalId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function filterByExternalId($externalId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($externalId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $externalId)) {
                $externalId = str_replace('*', '%', $externalId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PermissionTableMap::EXTERNAL_ID, $externalId, $comparison);
    }

    /**
     * Filter the query on the tree_left column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLeft(1234); // WHERE tree_left = 1234
     * $query->filterByTreeLeft(array(12, 34)); // WHERE tree_left IN (12, 34)
     * $query->filterByTreeLeft(array('min' => 12)); // WHERE tree_left > 12
     * </code>
     *
     * @param     mixed $treeLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(PermissionTableMap::TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(PermissionTableMap::TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionTableMap::TREE_LEFT, $treeLeft, $comparison);
    }

    /**
     * Filter the query on the tree_right column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeRight(1234); // WHERE tree_right = 1234
     * $query->filterByTreeRight(array(12, 34)); // WHERE tree_right IN (12, 34)
     * $query->filterByTreeRight(array('min' => 12)); // WHERE tree_right > 12
     * </code>
     *
     * @param     mixed $treeRight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(PermissionTableMap::TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(PermissionTableMap::TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionTableMap::TREE_RIGHT, $treeRight, $comparison);
    }

    /**
     * Filter the query on the tree_level column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLevel(1234); // WHERE tree_level = 1234
     * $query->filterByTreeLevel(array(12, 34)); // WHERE tree_level IN (12, 34)
     * $query->filterByTreeLevel(array('min' => 12)); // WHERE tree_level > 12
     * </code>
     *
     * @param     mixed $treeLevel The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(PermissionTableMap::TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(PermissionTableMap::TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionTableMap::TREE_LEVEL, $treeLevel, $comparison);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PermissionTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PermissionTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \org\bitbucket\phlopsi\access_control\propel\PermissionsRoles object
     *
     * @param \org\bitbucket\phlopsi\access_control\propel\PermissionsRoles|ObjectCollection $permissionsRoles  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function filterByPermissionsRoles($permissionsRoles, $comparison = null)
    {
        if ($permissionsRoles instanceof \org\bitbucket\phlopsi\access_control\propel\PermissionsRoles) {
            return $this
                ->addUsingAlias(PermissionTableMap::ID, $permissionsRoles->getPermissionId(), $comparison);
        } elseif ($permissionsRoles instanceof ObjectCollection) {
            return $this
                ->usePermissionsRolesQuery()
                ->filterByPrimaryKeys($permissionsRoles->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPermissionsRoles() only accepts arguments of type \org\bitbucket\phlopsi\access_control\propel\PermissionsRoles or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PermissionsRoles relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function joinPermissionsRoles($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PermissionsRoles');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PermissionsRoles');
        }

        return $this;
    }

    /**
     * Use the PermissionsRoles relation PermissionsRoles object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \org\bitbucket\phlopsi\access_control\propel\PermissionsRolesQuery A secondary query class using the current class as primary query
     */
    public function usePermissionsRolesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPermissionsRoles($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PermissionsRoles', '\org\bitbucket\phlopsi\access_control\propel\PermissionsRolesQuery');
    }

    /**
     * Filter the query by a related Role object
     * using the permissions_roles table as cross reference
     *
     * @param Role $role the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function filterByRole($role, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePermissionsRolesQuery()
            ->filterByRole($role, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPermission $permission Object to remove from the list of results
     *
     * @return ChildPermissionQuery The current query, for fluid interface
     */
    public function prune($permission = null)
    {
        if ($permission) {
            $this->addUsingAlias(PermissionTableMap::ID, $permission->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the permissions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PermissionTableMap::clearInstancePool();
            PermissionTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildPermission or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildPermission object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PermissionTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        PermissionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PermissionTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // nested_set behavior

    /**
     * Filter the query to restrict the result to descendants of an object
     *
     * @param     ChildPermission $permission The object to use for descendant search
     *
     * @return    ChildPermissionQuery The current query, for fluid interface
     */
    public function descendantsOf($permission)
    {
        return $this
            ->addUsingAlias(ChildPermission::LEFT_COL, $permission->getLeftValue(), Criteria::GREATER_THAN)
            ->addUsingAlias(ChildPermission::LEFT_COL, $permission->getRightValue(), Criteria::LESS_THAN);
    }

    /**
     * Filter the query to restrict the result to the branch of an object.
     * Same as descendantsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     ChildPermission $permission The object to use for branch search
     *
     * @return    ChildPermissionQuery The current query, for fluid interface
     */
    public function branchOf($permission)
    {
        return $this
            ->addUsingAlias(ChildPermission::LEFT_COL, $permission->getLeftValue(), Criteria::GREATER_EQUAL)
            ->addUsingAlias(ChildPermission::LEFT_COL, $permission->getRightValue(), Criteria::LESS_EQUAL);
    }

    /**
     * Filter the query to restrict the result to children of an object
     *
     * @param     ChildPermission $permission The object to use for child search
     *
     * @return    ChildPermissionQuery The current query, for fluid interface
     */
    public function childrenOf($permission)
    {
        return $this
            ->descendantsOf($permission)
            ->addUsingAlias(ChildPermission::LEVEL_COL, $permission->getLevel() + 1, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to siblings of an object.
     * The result does not include the object passed as parameter.
     *
     * @param     ChildPermission $permission The object to use for sibling search
     * @param      ConnectionInterface $con Connection to use.
     *
     * @return    ChildPermissionQuery The current query, for fluid interface
     */
    public function siblingsOf($permission, ConnectionInterface $con = null)
    {
        if ($permission->isRoot()) {
            return $this->
                add(ChildPermission::LEVEL_COL, '1<>1', Criteria::CUSTOM);
        } else {
            return $this
                ->childrenOf($permission->getParent($con))
                ->prune($permission);
        }
    }

    /**
     * Filter the query to restrict the result to ancestors of an object
     *
     * @param     ChildPermission $permission The object to use for ancestors search
     *
     * @return    ChildPermissionQuery The current query, for fluid interface
     */
    public function ancestorsOf($permission)
    {
        return $this
            ->addUsingAlias(ChildPermission::LEFT_COL, $permission->getLeftValue(), Criteria::LESS_THAN)
            ->addUsingAlias(ChildPermission::RIGHT_COL, $permission->getRightValue(), Criteria::GREATER_THAN);
    }

    /**
     * Filter the query to restrict the result to roots of an object.
     * Same as ancestorsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     ChildPermission $permission The object to use for roots search
     *
     * @return    ChildPermissionQuery The current query, for fluid interface
     */
    public function rootsOf($permission)
    {
        return $this
            ->addUsingAlias(ChildPermission::LEFT_COL, $permission->getLeftValue(), Criteria::LESS_EQUAL)
            ->addUsingAlias(ChildPermission::RIGHT_COL, $permission->getRightValue(), Criteria::GREATER_EQUAL);
    }

    /**
     * Order the result by branch, i.e. natural tree order
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    ChildPermissionQuery The current query, for fluid interface
     */
    public function orderByBranch($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addDescendingOrderByColumn(ChildPermission::LEFT_COL);
        } else {
            return $this
                ->addAscendingOrderByColumn(ChildPermission::LEFT_COL);
        }
    }

    /**
     * Order the result by level, the closer to the root first
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    ChildPermissionQuery The current query, for fluid interface
     */
    public function orderByLevel($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addAscendingOrderByColumn(ChildPermission::RIGHT_COL);
        } else {
            return $this
                ->addDescendingOrderByColumn(ChildPermission::RIGHT_COL);
        }
    }

    /**
     * Returns the root node for the tree
     *
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     ChildPermission The tree root object
     */
    public function findRoot($con = null)
    {
        return $this
            ->addUsingAlias(ChildPermission::LEFT_COL, 1, Criteria::EQUAL)
            ->findOne($con);
    }

    /**
     * Returns the tree of objects
     *
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findTree($con = null)
    {
        return $this
            ->orderByBranch()
            ->find($con);
    }

    /**
     * Returns the root node for a given scope
     *
     * @param      ConnectionInterface $con    Connection to use.
     * @return     ChildPermission            Propel object for root node
     */
    static public function retrieveRoot(ConnectionInterface $con = null)
    {
        $c = new Criteria(PermissionTableMap::DATABASE_NAME);
        $c->add(ChildPermission::LEFT_COL, 1, Criteria::EQUAL);

        return ChildPermissionQuery::create(null, $c)->findOne($con);
    }

    /**
     * Returns the whole tree node for a given scope
     *
     * @param      Criteria $criteria    Optional Criteria to filter the query
     * @param      ConnectionInterface $con    Connection to use.
     * @return     ChildPermission            Propel object for root node
     */
    static public function retrieveTree(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        if (null === $criteria) {
            $criteria = new Criteria(PermissionTableMap::DATABASE_NAME);
        }
        $criteria->addAscendingOrderByColumn(ChildPermission::LEFT_COL);

        return ChildPermissionQuery::create(null, $criteria)->find($con);
    }

    /**
     * Tests if node is valid
     *
     * @param      ChildPermission $node    Propel object for src node
     * @return     bool
     */
    static public function isValid(ChildPermission $node = null)
    {
        if (is_object($node) && $node->getRightValue() > $node->getLeftValue()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete an entire tree
     *
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     int  The number of deleted nodes
     */
    static public function deleteTree(ConnectionInterface $con = null)
    {

        return PermissionTableMap::doDeleteAll($con);
    }

    /**
     * Adds $delta to all L and R values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param int $delta               Value to be shifted by, can be negative
     * @param int $first               First node to be shifted
     * @param int $last                Last node to be shifted (optional)
     * @param ConnectionInterface $con Connection to use.
     */
    static public function shiftRLValues($delta, $first, $last = null, ConnectionInterface $con = null)
    {
        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionTableMap::DATABASE_NAME);
        }

        // Shift left column values
        $whereCriteria = new Criteria(PermissionTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(ChildPermission::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(ChildPermission::LEFT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(PermissionTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildPermission::LEFT_COL, array('raw' => ChildPermission::LEFT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);

        // Shift right column values
        $whereCriteria = new Criteria(PermissionTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(ChildPermission::RIGHT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(ChildPermission::RIGHT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(PermissionTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildPermission::RIGHT_COL, array('raw' => ChildPermission::RIGHT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Adds $delta to level for nodes having left value >= $first and right value <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta        Value to be shifted by, can be negative
     * @param      int $first        First node to be shifted
     * @param      int $last            Last node to be shifted
     * @param      ConnectionInterface $con        Connection to use.
     */
    static public function shiftLevel($delta, $first, $last, ConnectionInterface $con = null)
    {
        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionTableMap::DATABASE_NAME);
        }

        $whereCriteria = new Criteria(PermissionTableMap::DATABASE_NAME);
        $whereCriteria->add(ChildPermission::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        $whereCriteria->add(ChildPermission::RIGHT_COL, $last, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(PermissionTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildPermission::LEVEL_COL, array('raw' => ChildPermission::LEVEL_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Reload all already loaded nodes to sync them with updated db
     *
     * @param      ChildPermission $prune        Object to prune from the update
     * @param      ConnectionInterface $con        Connection to use.
     */
    static public function updateLoadedNodes($prune = null, ConnectionInterface $con = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            $keys = array();
            foreach (PermissionTableMap::$instances as $obj) {
                if (!$prune || !$prune->equals($obj)) {
                    $keys[] = $obj->getPrimaryKey();
                }
            }

            if (!empty($keys)) {
                // We don't need to alter the object instance pool; we're just modifying these ones
                // already in the pool.
                $criteria = new Criteria(PermissionTableMap::DATABASE_NAME);
                $criteria->add(PermissionTableMap::ID, $keys, Criteria::IN);
                $dataFetcher = ChildPermissionQuery::create(null, $criteria)->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
                while ($row = $dataFetcher->fetch()) {
                    $key = PermissionTableMap::getPrimaryKeyHashFromRow($row, 0);
                    if (null !== ($object = PermissionTableMap::getInstanceFromPool($key))) {
                        $object->setLeftValue($row[1]);
                        $object->setRightValue($row[2]);
                        $object->setLevel($row[3]);
                        $object->clearNestedSetChildren();
                    }
                }
                $dataFetcher->close();
            }
        }
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      int $left    left column value
     * @param      mixed $prune    Object to prune from the shift
     * @param      ConnectionInterface $con    Connection to use.
     */
    static public function makeRoomForLeaf($left, $prune = null, ConnectionInterface $con = null)
    {
        // Update database nodes
        ChildPermissionQuery::shiftRLValues(2, $left, null, $con);

        // Update all loaded nodes
        ChildPermissionQuery::updateLoadedNodes($prune, $con);
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      ConnectionInterface $con    Connection to use.
     */
    static public function fixLevels(ConnectionInterface $con = null)
    {
        $c = new Criteria();
        $c->addAscendingOrderByColumn(ChildPermission::LEFT_COL);
        $dataFetcher = ChildPermissionQuery::create(null, $c)->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);

        // set the class once to avoid overhead in the loop
        $cls = PermissionTableMap::getOMClass(false);
        $level = null;
        // iterate over the statement
        while ($row = $dataFetcher->fetch()) {

            // hydrate object
            $key = PermissionTableMap::getPrimaryKeyHashFromRow($row, 0);
            if (null === ($obj = PermissionTableMap::getInstanceFromPool($key))) {
                $obj = new $cls();
                $obj->hydrate($row);
                PermissionTableMap::addInstanceToPool($obj, $key);
            }

            // compute level
            // Algorithm shamelessly stolen from sfPropelActAsNestedSetBehaviorPlugin
            // Probably authored by Tristan Rivoallan
            if ($level === null) {
                $level = 0;
                $i = 0;
                $prev = array($obj->getRightValue());
            } else {
                while ($obj->getRightValue() > $prev[$i]) {
                    $i--;
                }
                $level = ++$i;
                $prev[$i] = $obj->getRightValue();
            }

            // update level in node if necessary
            if ($obj->getLevel() !== $level) {
                $obj->setLevel($level);
                $obj->save($con);
            }
        }
        $dataFetcher->close();
    }

    /**
     * Updates all scope values for items that has negative left (<=0) values.
     *
     * @param      mixed     $scope
     * @param      ConnectionInterface $con  Connection to use.
     */
    public static function setNegativeScope($scope, ConnectionInterface $con = null)
    {
        //adjust scope value to $scope
        $whereCriteria = new Criteria(PermissionTableMap::DATABASE_NAME);
        $whereCriteria->add(ChildPermission::LEFT_COL, 0, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(PermissionTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildPermission::SCOPE_COL, $scope, Criteria::EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
    }

} // PermissionQuery
