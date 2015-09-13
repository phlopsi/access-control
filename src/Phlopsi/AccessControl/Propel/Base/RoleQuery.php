<?php

namespace Phlopsi\AccessControl\Propel\Base;

use \Exception;
use \PDO;
use Phlopsi\AccessControl\Propel\Role as ChildRole;
use Phlopsi\AccessControl\Propel\RoleQuery as ChildRoleQuery;
use Phlopsi\AccessControl\Propel\Map\RoleTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'roles' table.
 *
 *
 *
 * @method     ChildRoleQuery orderByExternalId($order = Criteria::ASC) Order by the external_id column
 * @method     ChildRoleQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildRoleQuery groupByExternalId() Group by the external_id column
 * @method     ChildRoleQuery groupById() Group by the id column
 *
 * @method     ChildRoleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRoleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRoleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRoleQuery leftJoinPermissionToRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the PermissionToRole relation
 * @method     ChildRoleQuery rightJoinPermissionToRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PermissionToRole relation
 * @method     ChildRoleQuery innerJoinPermissionToRole($relationAlias = null) Adds a INNER JOIN clause to the query using the PermissionToRole relation
 *
 * @method     ChildRoleQuery leftJoinRoleToSessionType($relationAlias = null) Adds a LEFT JOIN clause to the query using the RoleToSessionType relation
 * @method     ChildRoleQuery rightJoinRoleToSessionType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RoleToSessionType relation
 * @method     ChildRoleQuery innerJoinRoleToSessionType($relationAlias = null) Adds a INNER JOIN clause to the query using the RoleToSessionType relation
 *
 * @method     ChildRoleQuery leftJoinRoleToUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the RoleToUser relation
 * @method     ChildRoleQuery rightJoinRoleToUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RoleToUser relation
 * @method     ChildRoleQuery innerJoinRoleToUser($relationAlias = null) Adds a INNER JOIN clause to the query using the RoleToUser relation
 *
 * @method     \Phlopsi\AccessControl\Propel\PermissionToRoleQuery|\Phlopsi\AccessControl\Propel\RoleToSessionTypeQuery|\Phlopsi\AccessControl\Propel\RoleToUserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildRole findOne(ConnectionInterface $con = null) Return the first ChildRole matching the query
 * @method     ChildRole findOneOrCreate(ConnectionInterface $con = null) Return the first ChildRole matching the query, or a new ChildRole object populated from the query conditions when no match is found
 *
 * @method     ChildRole findOneByExternalId(string $external_id) Return the first ChildRole filtered by the external_id column
 * @method     ChildRole findOneById(int $id) Return the first ChildRole filtered by the id column *

 * @method     ChildRole requirePk($key, ConnectionInterface $con = null) Return the ChildRole by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRole requireOne(ConnectionInterface $con = null) Return the first ChildRole matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRole requireOneByExternalId(string $external_id) Return the first ChildRole filtered by the external_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRole requireOneById(int $id) Return the first ChildRole filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRole[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildRole objects based on current ModelCriteria
 * @method     ChildRole[]|ObjectCollection findByExternalId(string $external_id) Return ChildRole objects filtered by the external_id column
 * @method     ChildRole[]|ObjectCollection findById(int $id) Return ChildRole objects filtered by the id column
 * @method     ChildRole[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class RoleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Phlopsi\AccessControl\Propel\Base\RoleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'access_control', $modelName = '\\Phlopsi\\AccessControl\\Propel\\Role', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRoleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRoleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildRoleQuery) {
            return $criteria;
        }
        $query = new ChildRoleQuery();
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
     * @return ChildRole|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RoleTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RoleTableMap::DATABASE_NAME);
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
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildRole A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT external_id, id FROM roles WHERE id = :p0';
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
            /** @var ChildRole $obj */
            $obj = new ChildRole();
            $obj->hydrate($row);
            RoleTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildRole|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
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
    public function findPks($keys, ConnectionInterface $con = null)
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
     * @return $this|ChildRoleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RoleTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildRoleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RoleTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildRoleQuery The current query, for fluid interface
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

        return $this->addUsingAlias(RoleTableMap::COL_EXTERNAL_ID, $externalId, $comparison);
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
     * @return $this|ChildRoleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(RoleTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RoleTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RoleTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \Phlopsi\AccessControl\Propel\PermissionToRole object
     *
     * @param \Phlopsi\AccessControl\Propel\PermissionToRole|ObjectCollection $permissionToRole the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRoleQuery The current query, for fluid interface
     */
    public function filterByPermissionToRole($permissionToRole, $comparison = null)
    {
        if ($permissionToRole instanceof \Phlopsi\AccessControl\Propel\PermissionToRole) {
            return $this
                ->addUsingAlias(RoleTableMap::COL_ID, $permissionToRole->getRoleId(), $comparison);
        } elseif ($permissionToRole instanceof ObjectCollection) {
            return $this
                ->usePermissionToRoleQuery()
                ->filterByPrimaryKeys($permissionToRole->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPermissionToRole() only accepts arguments of type \Phlopsi\AccessControl\Propel\PermissionToRole or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PermissionToRole relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRoleQuery The current query, for fluid interface
     */
    public function joinPermissionToRole($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PermissionToRole');

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
            $this->addJoinObject($join, 'PermissionToRole');
        }

        return $this;
    }

    /**
     * Use the PermissionToRole relation PermissionToRole object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Phlopsi\AccessControl\Propel\PermissionToRoleQuery A secondary query class using the current class as primary query
     */
    public function usePermissionToRoleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPermissionToRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PermissionToRole', '\Phlopsi\AccessControl\Propel\PermissionToRoleQuery');
    }

    /**
     * Filter the query by a related \Phlopsi\AccessControl\Propel\RoleToSessionType object
     *
     * @param \Phlopsi\AccessControl\Propel\RoleToSessionType|ObjectCollection $roleToSessionType the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRoleQuery The current query, for fluid interface
     */
    public function filterByRoleToSessionType($roleToSessionType, $comparison = null)
    {
        if ($roleToSessionType instanceof \Phlopsi\AccessControl\Propel\RoleToSessionType) {
            return $this
                ->addUsingAlias(RoleTableMap::COL_ID, $roleToSessionType->getRoleId(), $comparison);
        } elseif ($roleToSessionType instanceof ObjectCollection) {
            return $this
                ->useRoleToSessionTypeQuery()
                ->filterByPrimaryKeys($roleToSessionType->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRoleToSessionType() only accepts arguments of type \Phlopsi\AccessControl\Propel\RoleToSessionType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RoleToSessionType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRoleQuery The current query, for fluid interface
     */
    public function joinRoleToSessionType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RoleToSessionType');

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
            $this->addJoinObject($join, 'RoleToSessionType');
        }

        return $this;
    }

    /**
     * Use the RoleToSessionType relation RoleToSessionType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Phlopsi\AccessControl\Propel\RoleToSessionTypeQuery A secondary query class using the current class as primary query
     */
    public function useRoleToSessionTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRoleToSessionType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RoleToSessionType', '\Phlopsi\AccessControl\Propel\RoleToSessionTypeQuery');
    }

    /**
     * Filter the query by a related \Phlopsi\AccessControl\Propel\RoleToUser object
     *
     * @param \Phlopsi\AccessControl\Propel\RoleToUser|ObjectCollection $roleToUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRoleQuery The current query, for fluid interface
     */
    public function filterByRoleToUser($roleToUser, $comparison = null)
    {
        if ($roleToUser instanceof \Phlopsi\AccessControl\Propel\RoleToUser) {
            return $this
                ->addUsingAlias(RoleTableMap::COL_ID, $roleToUser->getRoleId(), $comparison);
        } elseif ($roleToUser instanceof ObjectCollection) {
            return $this
                ->useRoleToUserQuery()
                ->filterByPrimaryKeys($roleToUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRoleToUser() only accepts arguments of type \Phlopsi\AccessControl\Propel\RoleToUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RoleToUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRoleQuery The current query, for fluid interface
     */
    public function joinRoleToUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RoleToUser');

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
            $this->addJoinObject($join, 'RoleToUser');
        }

        return $this;
    }

    /**
     * Use the RoleToUser relation RoleToUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Phlopsi\AccessControl\Propel\RoleToUserQuery A secondary query class using the current class as primary query
     */
    public function useRoleToUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRoleToUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RoleToUser', '\Phlopsi\AccessControl\Propel\RoleToUserQuery');
    }

    /**
     * Filter the query by a related Permission object
     * using the permissions_roles table as cross reference
     *
     * @param Permission $permission the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRoleQuery The current query, for fluid interface
     */
    public function filterByPermission($permission, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePermissionToRoleQuery()
            ->filterByPermission($permission, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related SessionType object
     * using the roles_session_types table as cross reference
     *
     * @param SessionType $sessionType the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRoleQuery The current query, for fluid interface
     */
    public function filterBySessionType($sessionType, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useRoleToSessionTypeQuery()
            ->filterBySessionType($sessionType, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the roles_users table as cross reference
     *
     * @param User $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRoleQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useRoleToUserQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildRole $role Object to remove from the list of results
     *
     * @return $this|ChildRoleQuery The current query, for fluid interface
     */
    public function prune($role = null)
    {
        if ($role) {
            $this->addUsingAlias(RoleTableMap::COL_ID, $role->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the roles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RoleTableMap::clearInstancePool();
            RoleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RoleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            RoleTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            RoleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }
} // RoleQuery
