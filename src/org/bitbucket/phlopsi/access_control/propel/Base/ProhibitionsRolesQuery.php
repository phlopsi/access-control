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
use org\bitbucket\phlopsi\access_control\propel\ProhibitionsRoles as ChildProhibitionsRoles;
use org\bitbucket\phlopsi\access_control\propel\ProhibitionsRolesQuery as ChildProhibitionsRolesQuery;
use org\bitbucket\phlopsi\access_control\propel\Map\ProhibitionsRolesTableMap;

/**
 * Base class that represents a query for the 'prohibitions_roles' table.
 *
 *
 *
 * @method     ChildProhibitionsRolesQuery orderByprohibitionId($order = Criteria::ASC) Order by the prohibitions_id column
 * @method     ChildProhibitionsRolesQuery orderByroleId($order = Criteria::ASC) Order by the roles_id column
 *
 * @method     ChildProhibitionsRolesQuery groupByprohibitionId() Group by the prohibitions_id column
 * @method     ChildProhibitionsRolesQuery groupByroleId() Group by the roles_id column
 *
 * @method     ChildProhibitionsRolesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildProhibitionsRolesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildProhibitionsRolesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildProhibitionsRolesQuery leftJoinPermission($relationAlias = null) Adds a LEFT JOIN clause to the query using the Permission relation
 * @method     ChildProhibitionsRolesQuery rightJoinPermission($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Permission relation
 * @method     ChildProhibitionsRolesQuery innerJoinPermission($relationAlias = null) Adds a INNER JOIN clause to the query using the Permission relation
 *
 * @method     ChildProhibitionsRolesQuery leftJoinRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the Role relation
 * @method     ChildProhibitionsRolesQuery rightJoinRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Role relation
 * @method     ChildProhibitionsRolesQuery innerJoinRole($relationAlias = null) Adds a INNER JOIN clause to the query using the Role relation
 *
 * @method     ChildProhibitionsRoles findOne(ConnectionInterface $con = null) Return the first ChildProhibitionsRoles matching the query
 * @method     ChildProhibitionsRoles findOneOrCreate(ConnectionInterface $con = null) Return the first ChildProhibitionsRoles matching the query, or a new ChildProhibitionsRoles object populated from the query conditions when no match is found
 *
 * @method     ChildProhibitionsRoles findOneByprohibitionId(int $prohibitions_id) Return the first ChildProhibitionsRoles filtered by the prohibitions_id column
 * @method     ChildProhibitionsRoles findOneByroleId(int $roles_id) Return the first ChildProhibitionsRoles filtered by the roles_id column
 *
 * @method     array findByprohibitionId(int $prohibitions_id) Return ChildProhibitionsRoles objects filtered by the prohibitions_id column
 * @method     array findByroleId(int $roles_id) Return ChildProhibitionsRoles objects filtered by the roles_id column
 *
 */
abstract class ProhibitionsRolesQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \org\bitbucket\phlopsi\access_control\propel\Base\ProhibitionsRolesQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'access_control', $modelName = '\\org\\bitbucket\\phlopsi\\access_control\\propel\\ProhibitionsRoles', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildProhibitionsRolesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildProhibitionsRolesQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \org\bitbucket\phlopsi\access_control\propel\ProhibitionsRolesQuery) {
            return $criteria;
        }
        $query = new \org\bitbucket\phlopsi\access_control\propel\ProhibitionsRolesQuery();
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
     * @param array[$prohibitions_id, $roles_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildProhibitionsRoles|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProhibitionsRolesTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ProhibitionsRolesTableMap::DATABASE_NAME);
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
     * @return   ChildProhibitionsRoles A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT PROHIBITIONS_ID, ROLES_ID FROM prohibitions_roles WHERE PROHIBITIONS_ID = :p0 AND ROLES_ID = :p1';
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
            $obj = new ChildProhibitionsRoles();
            $obj->hydrate($row);
            ProhibitionsRolesTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildProhibitionsRoles|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return ChildProhibitionsRolesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ProhibitionsRolesTableMap::PROHIBITIONS_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ProhibitionsRolesTableMap::ROLES_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildProhibitionsRolesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ProhibitionsRolesTableMap::PROHIBITIONS_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ProhibitionsRolesTableMap::ROLES_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the prohibitions_id column
     *
     * Example usage:
     * <code>
     * $query->filterByprohibitionId(1234); // WHERE prohibitions_id = 1234
     * $query->filterByprohibitionId(array(12, 34)); // WHERE prohibitions_id IN (12, 34)
     * $query->filterByprohibitionId(array('min' => 12)); // WHERE prohibitions_id > 12
     * </code>
     *
     * @see       filterByPermission()
     *
     * @param     mixed $prohibitionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProhibitionsRolesQuery The current query, for fluid interface
     */
    public function filterByprohibitionId($prohibitionId = null, $comparison = null)
    {
        if (is_array($prohibitionId)) {
            $useMinMax = false;
            if (isset($prohibitionId['min'])) {
                $this->addUsingAlias(ProhibitionsRolesTableMap::PROHIBITIONS_ID, $prohibitionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($prohibitionId['max'])) {
                $this->addUsingAlias(ProhibitionsRolesTableMap::PROHIBITIONS_ID, $prohibitionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionsRolesTableMap::PROHIBITIONS_ID, $prohibitionId, $comparison);
    }

    /**
     * Filter the query on the roles_id column
     *
     * Example usage:
     * <code>
     * $query->filterByroleId(1234); // WHERE roles_id = 1234
     * $query->filterByroleId(array(12, 34)); // WHERE roles_id IN (12, 34)
     * $query->filterByroleId(array('min' => 12)); // WHERE roles_id > 12
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
     * @return ChildProhibitionsRolesQuery The current query, for fluid interface
     */
    public function filterByroleId($roleId = null, $comparison = null)
    {
        if (is_array($roleId)) {
            $useMinMax = false;
            if (isset($roleId['min'])) {
                $this->addUsingAlias(ProhibitionsRolesTableMap::ROLES_ID, $roleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($roleId['max'])) {
                $this->addUsingAlias(ProhibitionsRolesTableMap::ROLES_ID, $roleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionsRolesTableMap::ROLES_ID, $roleId, $comparison);
    }

    /**
     * Filter the query by a related \org\bitbucket\phlopsi\access_control\propel\Permission object
     *
     * @param \org\bitbucket\phlopsi\access_control\propel\Permission|ObjectCollection $permission The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProhibitionsRolesQuery The current query, for fluid interface
     */
    public function filterByPermission($permission, $comparison = null)
    {
        if ($permission instanceof \org\bitbucket\phlopsi\access_control\propel\Permission) {
            return $this
                ->addUsingAlias(ProhibitionsRolesTableMap::PROHIBITIONS_ID, $permission->getId(), $comparison);
        } elseif ($permission instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProhibitionsRolesTableMap::PROHIBITIONS_ID, $permission->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPermission() only accepts arguments of type \org\bitbucket\phlopsi\access_control\propel\Permission or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Permission relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildProhibitionsRolesQuery The current query, for fluid interface
     */
    public function joinPermission($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Permission');

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
            $this->addJoinObject($join, 'Permission');
        }

        return $this;
    }

    /**
     * Use the Permission relation Permission object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \org\bitbucket\phlopsi\access_control\propel\PermissionQuery A secondary query class using the current class as primary query
     */
    public function usePermissionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPermission($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Permission', '\org\bitbucket\phlopsi\access_control\propel\PermissionQuery');
    }

    /**
     * Filter the query by a related \org\bitbucket\phlopsi\access_control\propel\Role object
     *
     * @param \org\bitbucket\phlopsi\access_control\propel\Role|ObjectCollection $role The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProhibitionsRolesQuery The current query, for fluid interface
     */
    public function filterByRole($role, $comparison = null)
    {
        if ($role instanceof \org\bitbucket\phlopsi\access_control\propel\Role) {
            return $this
                ->addUsingAlias(ProhibitionsRolesTableMap::ROLES_ID, $role->getId(), $comparison);
        } elseif ($role instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProhibitionsRolesTableMap::ROLES_ID, $role->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByRole() only accepts arguments of type \org\bitbucket\phlopsi\access_control\propel\Role or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Role relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildProhibitionsRolesQuery The current query, for fluid interface
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
     * @return   \org\bitbucket\phlopsi\access_control\propel\RoleQuery A secondary query class using the current class as primary query
     */
    public function useRoleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Role', '\org\bitbucket\phlopsi\access_control\propel\RoleQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildProhibitionsRoles $prohibitionsRoles Object to remove from the list of results
     *
     * @return ChildProhibitionsRolesQuery The current query, for fluid interface
     */
    public function prune($prohibitionsRoles = null)
    {
        if ($prohibitionsRoles) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ProhibitionsRolesTableMap::PROHIBITIONS_ID), $prohibitionsRoles->getprohibitionId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ProhibitionsRolesTableMap::ROLES_ID), $prohibitionsRoles->getroleId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the prohibitions_roles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionsRolesTableMap::DATABASE_NAME);
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
            ProhibitionsRolesTableMap::clearInstancePool();
            ProhibitionsRolesTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildProhibitionsRoles or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildProhibitionsRoles object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionsRolesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ProhibitionsRolesTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        ProhibitionsRolesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ProhibitionsRolesTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // ProhibitionsRolesQuery
