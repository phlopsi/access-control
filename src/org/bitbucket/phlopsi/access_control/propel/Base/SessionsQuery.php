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
use org\bitbucket\phlopsi\access_control\propel\Sessions as ChildSessions;
use org\bitbucket\phlopsi\access_control\propel\SessionsQuery as ChildSessionsQuery;
use org\bitbucket\phlopsi\access_control\propel\Map\SessionsTableMap;

/**
 * Base class that represents a query for the 'sessions' table.
 *
 *
 *
 * @method     ChildSessionsQuery orderBysessionTypeId($order = Criteria::ASC) Order by the session_types_id column
 * @method     ChildSessionsQuery orderByuserId($order = Criteria::ASC) Order by the users_id column
 * @method     ChildSessionsQuery orderByKey($order = Criteria::ASC) Order by the key column
 * @method     ChildSessionsQuery orderBySessionDuration($order = Criteria::ASC) Order by the session_duration column
 * @method     ChildSessionsQuery orderBysimulatedUserId($order = Criteria::ASC) Order by the simulated_users_id column
 * @method     ChildSessionsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildSessionsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildSessionsQuery groupBysessionTypeId() Group by the session_types_id column
 * @method     ChildSessionsQuery groupByuserId() Group by the users_id column
 * @method     ChildSessionsQuery groupByKey() Group by the key column
 * @method     ChildSessionsQuery groupBySessionDuration() Group by the session_duration column
 * @method     ChildSessionsQuery groupBysimulatedUserId() Group by the simulated_users_id column
 * @method     ChildSessionsQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildSessionsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildSessionsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSessionsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSessionsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSessionsQuery leftJoinSessionType($relationAlias = null) Adds a LEFT JOIN clause to the query using the SessionType relation
 * @method     ChildSessionsQuery rightJoinSessionType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SessionType relation
 * @method     ChildSessionsQuery innerJoinSessionType($relationAlias = null) Adds a INNER JOIN clause to the query using the SessionType relation
 *
 * @method     ChildSessionsQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildSessionsQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildSessionsQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildSessions findOne(ConnectionInterface $con = null) Return the first ChildSessions matching the query
 * @method     ChildSessions findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSessions matching the query, or a new ChildSessions object populated from the query conditions when no match is found
 *
 * @method     ChildSessions findOneBysessionTypeId(int $session_types_id) Return the first ChildSessions filtered by the session_types_id column
 * @method     ChildSessions findOneByuserId(int $users_id) Return the first ChildSessions filtered by the users_id column
 * @method     ChildSessions findOneByKey(string $key) Return the first ChildSessions filtered by the key column
 * @method     ChildSessions findOneBySessionDuration(string $session_duration) Return the first ChildSessions filtered by the session_duration column
 * @method     ChildSessions findOneBysimulatedUserId(int $simulated_users_id) Return the first ChildSessions filtered by the simulated_users_id column
 * @method     ChildSessions findOneByCreatedAt(string $created_at) Return the first ChildSessions filtered by the created_at column
 * @method     ChildSessions findOneByUpdatedAt(string $updated_at) Return the first ChildSessions filtered by the updated_at column
 *
 * @method     array findBysessionTypeId(int $session_types_id) Return ChildSessions objects filtered by the session_types_id column
 * @method     array findByuserId(int $users_id) Return ChildSessions objects filtered by the users_id column
 * @method     array findByKey(string $key) Return ChildSessions objects filtered by the key column
 * @method     array findBySessionDuration(string $session_duration) Return ChildSessions objects filtered by the session_duration column
 * @method     array findBysimulatedUserId(int $simulated_users_id) Return ChildSessions objects filtered by the simulated_users_id column
 * @method     array findByCreatedAt(string $created_at) Return ChildSessions objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildSessions objects filtered by the updated_at column
 *
 */
abstract class SessionsQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \org\bitbucket\phlopsi\access_control\propel\Base\SessionsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'access_control', $modelName = '\\org\\bitbucket\\phlopsi\\access_control\\propel\\Sessions', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSessionsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSessionsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \org\bitbucket\phlopsi\access_control\propel\SessionsQuery) {
            return $criteria;
        }
        $query = new \org\bitbucket\phlopsi\access_control\propel\SessionsQuery();
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
     * @return ChildSessions|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SessionsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SessionsTableMap::DATABASE_NAME);
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
     * @return   ChildSessions A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT SESSION_TYPES_ID, USERS_ID, KEY, SESSION_DURATION, SIMULATED_USERS_ID, CREATED_AT, UPDATED_AT FROM sessions WHERE KEY = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildSessions();
            $obj->hydrate($row);
            SessionsTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSessions|array|mixed the result, formatted by the current formatter
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
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SessionsTableMap::KEY, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SessionsTableMap::KEY, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the session_types_id column
     *
     * Example usage:
     * <code>
     * $query->filterBysessionTypeId(1234); // WHERE session_types_id = 1234
     * $query->filterBysessionTypeId(array(12, 34)); // WHERE session_types_id IN (12, 34)
     * $query->filterBysessionTypeId(array('min' => 12)); // WHERE session_types_id > 12
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
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterBysessionTypeId($sessionTypeId = null, $comparison = null)
    {
        if (is_array($sessionTypeId)) {
            $useMinMax = false;
            if (isset($sessionTypeId['min'])) {
                $this->addUsingAlias(SessionsTableMap::SESSION_TYPES_ID, $sessionTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sessionTypeId['max'])) {
                $this->addUsingAlias(SessionsTableMap::SESSION_TYPES_ID, $sessionTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionsTableMap::SESSION_TYPES_ID, $sessionTypeId, $comparison);
    }

    /**
     * Filter the query on the users_id column
     *
     * Example usage:
     * <code>
     * $query->filterByuserId(1234); // WHERE users_id = 1234
     * $query->filterByuserId(array(12, 34)); // WHERE users_id IN (12, 34)
     * $query->filterByuserId(array('min' => 12)); // WHERE users_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterByuserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(SessionsTableMap::USERS_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(SessionsTableMap::USERS_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionsTableMap::USERS_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the key column
     *
     * Example usage:
     * <code>
     * $query->filterByKey('fooValue');   // WHERE key = 'fooValue'
     * $query->filterByKey('%fooValue%'); // WHERE key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $key The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterByKey($key = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($key)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $key)) {
                $key = str_replace('*', '%', $key);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SessionsTableMap::KEY, $key, $comparison);
    }

    /**
     * Filter the query on the session_duration column
     *
     * Example usage:
     * <code>
     * $query->filterBySessionDuration('2011-03-14'); // WHERE session_duration = '2011-03-14'
     * $query->filterBySessionDuration('now'); // WHERE session_duration = '2011-03-14'
     * $query->filterBySessionDuration(array('max' => 'yesterday')); // WHERE session_duration > '2011-03-13'
     * </code>
     *
     * @param     mixed $sessionDuration The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterBySessionDuration($sessionDuration = null, $comparison = null)
    {
        if (is_array($sessionDuration)) {
            $useMinMax = false;
            if (isset($sessionDuration['min'])) {
                $this->addUsingAlias(SessionsTableMap::SESSION_DURATION, $sessionDuration['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sessionDuration['max'])) {
                $this->addUsingAlias(SessionsTableMap::SESSION_DURATION, $sessionDuration['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionsTableMap::SESSION_DURATION, $sessionDuration, $comparison);
    }

    /**
     * Filter the query on the simulated_users_id column
     *
     * Example usage:
     * <code>
     * $query->filterBysimulatedUserId(1234); // WHERE simulated_users_id = 1234
     * $query->filterBysimulatedUserId(array(12, 34)); // WHERE simulated_users_id IN (12, 34)
     * $query->filterBysimulatedUserId(array('min' => 12)); // WHERE simulated_users_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $simulatedUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterBysimulatedUserId($simulatedUserId = null, $comparison = null)
    {
        if (is_array($simulatedUserId)) {
            $useMinMax = false;
            if (isset($simulatedUserId['min'])) {
                $this->addUsingAlias(SessionsTableMap::SIMULATED_USERS_ID, $simulatedUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($simulatedUserId['max'])) {
                $this->addUsingAlias(SessionsTableMap::SIMULATED_USERS_ID, $simulatedUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionsTableMap::SIMULATED_USERS_ID, $simulatedUserId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SessionsTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SessionsTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionsTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SessionsTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SessionsTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionsTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \org\bitbucket\phlopsi\access_control\propel\SessionType object
     *
     * @param \org\bitbucket\phlopsi\access_control\propel\SessionType|ObjectCollection $sessionType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterBySessionType($sessionType, $comparison = null)
    {
        if ($sessionType instanceof \org\bitbucket\phlopsi\access_control\propel\SessionType) {
            return $this
                ->addUsingAlias(SessionsTableMap::SESSION_TYPES_ID, $sessionType->getId(), $comparison);
        } elseif ($sessionType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SessionsTableMap::SESSION_TYPES_ID, $sessionType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySessionType() only accepts arguments of type \org\bitbucket\phlopsi\access_control\propel\SessionType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SessionType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSessionsQuery The current query, for fluid interface
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
     * @return   \org\bitbucket\phlopsi\access_control\propel\SessionTypeQuery A secondary query class using the current class as primary query
     */
    public function useSessionTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSessionType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SessionType', '\org\bitbucket\phlopsi\access_control\propel\SessionTypeQuery');
    }

    /**
     * Filter the query by a related \org\bitbucket\phlopsi\access_control\propel\User object
     *
     * @param \org\bitbucket\phlopsi\access_control\propel\User $user The related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \org\bitbucket\phlopsi\access_control\propel\User) {
            return $this
                ->addUsingAlias(SessionsTableMap::USERS_ID, $user->getId(), $comparison)
                ->addUsingAlias(SessionsTableMap::SIMULATED_USERS_ID, $user->getId(), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \org\bitbucket\phlopsi\access_control\propel\User');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \org\bitbucket\phlopsi\access_control\propel\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\org\bitbucket\phlopsi\access_control\propel\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSessions $sessions Object to remove from the list of results
     *
     * @return ChildSessionsQuery The current query, for fluid interface
     */
    public function prune($sessions = null)
    {
        if ($sessions) {
            $this->addUsingAlias(SessionsTableMap::KEY, $sessions->getKey(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the sessions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SessionsTableMap::DATABASE_NAME);
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
            SessionsTableMap::clearInstancePool();
            SessionsTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildSessions or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildSessions object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SessionsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SessionsTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        SessionsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SessionsTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildSessionsQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SessionsTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildSessionsQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SessionsTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildSessionsQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SessionsTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildSessionsQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SessionsTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildSessionsQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SessionsTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildSessionsQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SessionsTableMap::CREATED_AT);
    }

} // SessionsQuery
