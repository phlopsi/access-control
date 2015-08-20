<?php

namespace Phlopsi\AccessControl\Propel\Base;

use \Exception;
use \PDO;
use Phlopsi\AccessControl\Propel\RolesSessionTypes as ChildRolesSessionTypes;
use Phlopsi\AccessControl\Propel\RolesSessionTypesQuery as ChildRolesSessionTypesQuery;
use Phlopsi\AccessControl\Propel\Map\RolesSessionTypesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'roles_session_types' table.
 *
 *
 *
 * @method     ChildRolesSessionTypesQuery orderByRoleId($order = Criteria::ASC) Order by the roles_id column
 * @method     ChildRolesSessionTypesQuery orderBySessionTypeId($order = Criteria::ASC) Order by the session_types_id column
 *
 * @method     ChildRolesSessionTypesQuery groupByRoleId() Group by the roles_id column
 * @method     ChildRolesSessionTypesQuery groupBySessionTypeId() Group by the session_types_id column
 *
 * @method     ChildRolesSessionTypesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRolesSessionTypesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRolesSessionTypesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRolesSessionTypesQuery leftJoinRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the Role relation
 * @method     ChildRolesSessionTypesQuery rightJoinRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Role relation
 * @method     ChildRolesSessionTypesQuery innerJoinRole($relationAlias = null) Adds a INNER JOIN clause to the query using the Role relation
 *
 * @method     ChildRolesSessionTypesQuery leftJoinSessionType($relationAlias = null) Adds a LEFT JOIN clause to the query using the SessionType relation
 * @method     ChildRolesSessionTypesQuery rightJoinSessionType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SessionType relation
 * @method     ChildRolesSessionTypesQuery innerJoinSessionType($relationAlias = null) Adds a INNER JOIN clause to the query using the SessionType relation
 *
 * @method     \Phlopsi\AccessControl\Propel\RoleQuery|\Phlopsi\AccessControl\Propel\SessionTypeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildRolesSessionTypes findOne(ConnectionInterface $con = null) Return the first ChildRolesSessionTypes matching the query
 * @method     ChildRolesSessionTypes findOneOrCreate(ConnectionInterface $con = null) Return the first ChildRolesSessionTypes matching the query, or a new ChildRolesSessionTypes object populated from the query conditions when no match is found
 *
 * @method     ChildRolesSessionTypes findOneByRoleId(int $roles_id) Return the first ChildRolesSessionTypes filtered by the roles_id column
 * @method     ChildRolesSessionTypes findOneBySessionTypeId(int $session_types_id) Return the first ChildRolesSessionTypes filtered by the session_types_id column
 *
 * @method     ChildRolesSessionTypes[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildRolesSessionTypes objects based on current ModelCriteria
 * @method     ChildRolesSessionTypes[]|ObjectCollection findByRoleId(int $roles_id) Return ChildRolesSessionTypes objects filtered by the roles_id column
 * @method     ChildRolesSessionTypes[]|ObjectCollection findBySessionTypeId(int $session_types_id) Return ChildRolesSessionTypes objects filtered by the session_types_id column
 * @method     ChildRolesSessionTypes[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class RolesSessionTypesQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Phlopsi\AccessControl\Propel\Base\RolesSessionTypesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'access_control', $modelName = '\\Phlopsi\\AccessControl\\Propel\\RolesSessionTypes', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRolesSessionTypesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRolesSessionTypesQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildRolesSessionTypesQuery) {
            return $criteria;
        }
        $query = new ChildRolesSessionTypesQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$roles_id, $session_types_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildRolesSessionTypes|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RolesSessionTypesTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RolesSessionTypesTableMap::DATABASE_NAME);
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
     * @return ChildRolesSessionTypes A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT roles_id, session_types_id FROM roles_session_types WHERE roles_id = :p0 AND session_types_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildRolesSessionTypes $obj */
            $obj = new ChildRolesSessionTypes();
            $obj->hydrate($row);
            RolesSessionTypesTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildRolesSessionTypes|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildRolesSessionTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(RolesSessionTypesTableMap::COL_ROLES_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(RolesSessionTypesTableMap::COL_SESSION_TYPES_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildRolesSessionTypesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(RolesSessionTypesTableMap::COL_ROLES_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(RolesSessionTypesTableMap::COL_SESSION_TYPES_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the roles_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRoleId(1234); // WHERE roles_id = 1234
     * $query->filterByRoleId(array(12, 34)); // WHERE roles_id IN (12, 34)
     * $query->filterByRoleId(array('min' => 12)); // WHERE roles_id > 12
     * </code>
     *
     * @see       filterByRole()
     *
     * @param     mixed $roleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRolesSessionTypesQuery The current query, for fluid interface
     */
    public function filterByRoleId($roleId = null, $comparison = null)
    {
        if (is_array($roleId)) {
            $useMinMax = false;
            if (isset($roleId['min'])) {
                $this->addUsingAlias(RolesSessionTypesTableMap::COL_ROLES_ID, $roleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($roleId['max'])) {
                $this->addUsingAlias(RolesSessionTypesTableMap::COL_ROLES_ID, $roleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RolesSessionTypesTableMap::COL_ROLES_ID, $roleId, $comparison);
    }

    /**
     * Filter the query on the session_types_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySessionTypeId(1234); // WHERE session_types_id = 1234
     * $query->filterBySessionTypeId(array(12, 34)); // WHERE session_types_id IN (12, 34)
     * $query->filterBySessionTypeId(array('min' => 12)); // WHERE session_types_id > 12
     * </code>
     *
     * @see       filterBySessionType()
     *
     * @param     mixed $sessionTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRolesSessionTypesQuery The current query, for fluid interface
     */
    public function filterBySessionTypeId($sessionTypeId = null, $comparison = null)
    {
        if (is_array($sessionTypeId)) {
            $useMinMax = false;
            if (isset($sessionTypeId['min'])) {
                $this->addUsingAlias(RolesSessionTypesTableMap::COL_SESSION_TYPES_ID, $sessionTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sessionTypeId['max'])) {
                $this->addUsingAlias(RolesSessionTypesTableMap::COL_SESSION_TYPES_ID, $sessionTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RolesSessionTypesTableMap::COL_SESSION_TYPES_ID, $sessionTypeId, $comparison);
    }

    /**
     * Filter the query by a related \Phlopsi\AccessControl\Propel\Role object
     *
     * @param \Phlopsi\AccessControl\Propel\Role|ObjectCollection $role The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildRolesSessionTypesQuery The current query, for fluid interface
     */
    public function filterByRole($role, $comparison = null)
    {
        if ($role instanceof \Phlopsi\AccessControl\Propel\Role) {
            return $this
                ->addUsingAlias(RolesSessionTypesTableMap::COL_ROLES_ID, $role->getId(), $comparison);
        } elseif ($role instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RolesSessionTypesTableMap::COL_ROLES_ID, $role->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByRole() only accepts arguments of type \Phlopsi\AccessControl\Propel\Role or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Role relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRolesSessionTypesQuery The current query, for fluid interface
     */
    public function joinRole($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Role');

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
            $this->addJoinObject($join, 'Role');
        }

        return $this;
    }

    /**
     * Use the Role relation Role object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Phlopsi\AccessControl\Propel\RoleQuery A secondary query class using the current class as primary query
     */
    public function useRoleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Role', '\Phlopsi\AccessControl\Propel\RoleQuery');
    }

    /**
     * Filter the query by a related \Phlopsi\AccessControl\Propel\SessionType object
     *
     * @param \Phlopsi\AccessControl\Propel\SessionType|ObjectCollection $sessionType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildRolesSessionTypesQuery The current query, for fluid interface
     */
    public function filterBySessionType($sessionType, $comparison = null)
    {
        if ($sessionType instanceof \Phlopsi\AccessControl\Propel\SessionType) {
            return $this
                ->addUsingAlias(RolesSessionTypesTableMap::COL_SESSION_TYPES_ID, $sessionType->getId(), $comparison);
        } elseif ($sessionType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RolesSessionTypesTableMap::COL_SESSION_TYPES_ID, $sessionType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySessionType() only accepts arguments of type \Phlopsi\AccessControl\Propel\SessionType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SessionType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRolesSessionTypesQuery The current query, for fluid interface
     */
    public function joinSessionType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SessionType');

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
            $this->addJoinObject($join, 'SessionType');
        }

        return $this;
    }

    /**
     * Use the SessionType relation SessionType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Phlopsi\AccessControl\Propel\SessionTypeQuery A secondary query class using the current class as primary query
     */
    public function useSessionTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSessionType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SessionType', '\Phlopsi\AccessControl\Propel\SessionTypeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildRolesSessionTypes $rolesSessionTypes Object to remove from the list of results
     *
     * @return $this|ChildRolesSessionTypesQuery The current query, for fluid interface
     */
    public function prune($rolesSessionTypes = null)
    {
        if ($rolesSessionTypes) {
            $this->addCond('pruneCond0', $this->getAliasedColName(RolesSessionTypesTableMap::COL_ROLES_ID), $rolesSessionTypes->getRoleId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(RolesSessionTypesTableMap::COL_SESSION_TYPES_ID), $rolesSessionTypes->getSessionTypeId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the roles_session_types table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RolesSessionTypesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RolesSessionTypesTableMap::clearInstancePool();
            RolesSessionTypesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(RolesSessionTypesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RolesSessionTypesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            RolesSessionTypesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            RolesSessionTypesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // RolesSessionTypesQuery
