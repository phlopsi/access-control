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
use org\bitbucket\phlopsi\access_control\propel\User as ChildUser;
use org\bitbucket\phlopsi\access_control\propel\UserQuery as ChildUserQuery;
use org\bitbucket\phlopsi\access_control\propel\Map\UserTableMap;

/**
 * Base class that represents a query for the 'users' table.
 *
 *
 *
 * @method     ChildUserQuery orderByExternalId($order = Criteria::ASC) Order by the external_id column
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildUserQuery groupByExternalId() Group by the external_id column
 * @method     ChildUserQuery groupById() Group by the id column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinProhibitionsUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProhibitionsUsers relation
 * @method     ChildUserQuery rightJoinProhibitionsUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProhibitionsUsers relation
 * @method     ChildUserQuery innerJoinProhibitionsUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the ProhibitionsUsers relation
 *
 * @method     ChildUserQuery leftJoinRolesUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the RolesUsers relation
 * @method     ChildUserQuery rightJoinRolesUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RolesUsers relation
 * @method     ChildUserQuery innerJoinRolesUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the RolesUsers relation
 *
 * @method     ChildUserQuery leftJoinSessions($relationAlias = null) Adds a LEFT JOIN clause to the query using the Sessions relation
 * @method     ChildUserQuery rightJoinSessions($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Sessions relation
 * @method     ChildUserQuery innerJoinSessions($relationAlias = null) Adds a INNER JOIN clause to the query using the Sessions relation
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneByExternalId(string $external_id) Return the first ChildUser filtered by the external_id column
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 *
 * @method     array findByExternalId(string $external_id) Return ChildUser objects filtered by the external_id column
 * @method     array findById(int $id) Return ChildUser objects filtered by the id column
 *
 */
abstract class UserQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \org\bitbucket\phlopsi\access_control\propel\Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'access_control', $modelName = '\\org\\bitbucket\\phlopsi\\access_control\\propel\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \org\bitbucket\phlopsi\access_control\propel\UserQuery) {
            return $criteria;
        }
        $query = new \org\bitbucket\phlopsi\access_control\propel\UserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
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
     * @return   ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT EXTERNAL_ID, ID FROM users WHERE ID = :p0';
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
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(UserTableMap::EXTERNAL_ID, $externalId, $comparison);
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
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsers object
     *
     * @param \org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsers|ObjectCollection $prohibitionsUsers  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByProhibitionsUsers($prohibitionsUsers, $comparison = null)
    {
        if ($prohibitionsUsers instanceof \org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsers) {
            return $this
                ->addUsingAlias(UserTableMap::ID, $prohibitionsUsers->getuserId(), $comparison);
        } elseif ($prohibitionsUsers instanceof ObjectCollection) {
            return $this
                ->useProhibitionsUsersQuery()
                ->filterByPrimaryKeys($prohibitionsUsers->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProhibitionsUsers() only accepts arguments of type \org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsers or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProhibitionsUsers relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function joinProhibitionsUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProhibitionsUsers');

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
            $this->addJoinObject($join, 'ProhibitionsUsers');
        }

        return $this;
    }

    /**
     * Use the ProhibitionsUsers relation ProhibitionsUsers object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsersQuery A secondary query class using the current class as primary query
     */
    public function useProhibitionsUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProhibitionsUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProhibitionsUsers', '\org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsersQuery');
    }

    /**
     * Filter the query by a related \org\bitbucket\phlopsi\access_control\propel\RolesUsers object
     *
     * @param \org\bitbucket\phlopsi\access_control\propel\RolesUsers|ObjectCollection $rolesUsers  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByRolesUsers($rolesUsers, $comparison = null)
    {
        if ($rolesUsers instanceof \org\bitbucket\phlopsi\access_control\propel\RolesUsers) {
            return $this
                ->addUsingAlias(UserTableMap::ID, $rolesUsers->getuserId(), $comparison);
        } elseif ($rolesUsers instanceof ObjectCollection) {
            return $this
                ->useRolesUsersQuery()
                ->filterByPrimaryKeys($rolesUsers->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRolesUsers() only accepts arguments of type \org\bitbucket\phlopsi\access_control\propel\RolesUsers or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RolesUsers relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function joinRolesUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RolesUsers');

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
            $this->addJoinObject($join, 'RolesUsers');
        }

        return $this;
    }

    /**
     * Use the RolesUsers relation RolesUsers object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \org\bitbucket\phlopsi\access_control\propel\RolesUsersQuery A secondary query class using the current class as primary query
     */
    public function useRolesUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinRolesUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'RolesUsers', '\org\bitbucket\phlopsi\access_control\propel\RolesUsersQuery');
    }

    /**
     * Filter the query by a related \org\bitbucket\phlopsi\access_control\propel\Sessions object
     *
     * @param \org\bitbucket\phlopsi\access_control\propel\Sessions|ObjectCollection $sessions  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterBySessions($sessions, $comparison = null)
    {
        if ($sessions instanceof \org\bitbucket\phlopsi\access_control\propel\Sessions) {
            return $this
                ->addUsingAlias(UserTableMap::ID, $sessions->getsimulatedUserId(), $comparison);
        } else {
            throw new PropelException('filterBySessions() only accepts arguments of type \org\bitbucket\phlopsi\access_control\propel\Sessions');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Sessions relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function joinSessions($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Sessions');

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
            $this->addJoinObject($join, 'Sessions');
        }

        return $this;
    }

    /**
     * Use the Sessions relation Sessions object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \org\bitbucket\phlopsi\access_control\propel\SessionsQuery A secondary query class using the current class as primary query
     */
    public function useSessionsQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSessions($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Sessions', '\org\bitbucket\phlopsi\access_control\propel\SessionsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildUser or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildUser object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // UserQuery
