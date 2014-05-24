<?php

namespace Phlopsi\AccessControl\Propel\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Phlopsi\AccessControl\Propel\ProhibitionsUsers as ChildProhibitionsUsers;
use Phlopsi\AccessControl\Propel\ProhibitionsUsersQuery as ChildProhibitionsUsersQuery;
use Phlopsi\AccessControl\Propel\Map\ProhibitionsUsersTableMap;

/**
 * Base class that represents a query for the 'prohibitions_users' table.
 *
 *
 *
 * @method     ChildProhibitionsUsersQuery orderByProhibitionId($order = Criteria::ASC) Order by the prohibitions_id column
 * @method     ChildProhibitionsUsersQuery orderByUserId($order = Criteria::ASC) Order by the users_id column
 * @method     ChildProhibitionsUsersQuery orderByProhibitedUntil($order = Criteria::ASC) Order by the prohibited_until column
 * @method     ChildProhibitionsUsersQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildProhibitionsUsersQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildProhibitionsUsersQuery groupByProhibitionId() Group by the prohibitions_id column
 * @method     ChildProhibitionsUsersQuery groupByUserId() Group by the users_id column
 * @method     ChildProhibitionsUsersQuery groupByProhibitedUntil() Group by the prohibited_until column
 * @method     ChildProhibitionsUsersQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildProhibitionsUsersQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildProhibitionsUsersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildProhibitionsUsersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildProhibitionsUsersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildProhibitionsUsersQuery leftJoinProhibition($relationAlias = null) Adds a LEFT JOIN clause to the query using the Prohibition relation
 * @method     ChildProhibitionsUsersQuery rightJoinProhibition($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Prohibition relation
 * @method     ChildProhibitionsUsersQuery innerJoinProhibition($relationAlias = null) Adds a INNER JOIN clause to the query using the Prohibition relation
 *
 * @method     ChildProhibitionsUsersQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildProhibitionsUsersQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildProhibitionsUsersQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     \Phlopsi\AccessControl\Propel\ProhibitionQuery|\Phlopsi\AccessControl\Propel\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildProhibitionsUsers findOne(ConnectionInterface $con = null) Return the first ChildProhibitionsUsers matching the query
 * @method     ChildProhibitionsUsers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildProhibitionsUsers matching the query, or a new ChildProhibitionsUsers object populated from the query conditions when no match is found
 *
 * @method     ChildProhibitionsUsers findOneByProhibitionId(int $prohibitions_id) Return the first ChildProhibitionsUsers filtered by the prohibitions_id column
 * @method     ChildProhibitionsUsers findOneByUserId(int $users_id) Return the first ChildProhibitionsUsers filtered by the users_id column
 * @method     ChildProhibitionsUsers findOneByProhibitedUntil(string $prohibited_until) Return the first ChildProhibitionsUsers filtered by the prohibited_until column
 * @method     ChildProhibitionsUsers findOneByCreatedAt(string $created_at) Return the first ChildProhibitionsUsers filtered by the created_at column
 * @method     ChildProhibitionsUsers findOneByUpdatedAt(string $updated_at) Return the first ChildProhibitionsUsers filtered by the updated_at column
 *
 * @method     ChildProhibitionsUsers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildProhibitionsUsers objects based on current ModelCriteria
 * @method     ChildProhibitionsUsers[]|ObjectCollection findByProhibitionId(int $prohibitions_id) Return ChildProhibitionsUsers objects filtered by the prohibitions_id column
 * @method     ChildProhibitionsUsers[]|ObjectCollection findByUserId(int $users_id) Return ChildProhibitionsUsers objects filtered by the users_id column
 * @method     ChildProhibitionsUsers[]|ObjectCollection findByProhibitedUntil(string $prohibited_until) Return ChildProhibitionsUsers objects filtered by the prohibited_until column
 * @method     ChildProhibitionsUsers[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildProhibitionsUsers objects filtered by the created_at column
 * @method     ChildProhibitionsUsers[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildProhibitionsUsers objects filtered by the updated_at column
 * @method     ChildProhibitionsUsers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ProhibitionsUsersQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Phlopsi\AccessControl\Propel\Base\ProhibitionsUsersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'access_control', $modelName = '\\phlopsi\\access_control\\propel\\ProhibitionsUsers', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildProhibitionsUsersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildProhibitionsUsersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildProhibitionsUsersQuery) {
            return $criteria;
        }
        $query = new ChildProhibitionsUsersQuery();
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
     * @param array[$prohibitions_id, $users_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildProhibitionsUsers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProhibitionsUsersTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ProhibitionsUsersTableMap::DATABASE_NAME);
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
     * @return ChildProhibitionsUsers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT PROHIBITIONS_ID, USERS_ID, PROHIBITED_UNTIL, CREATED_AT, UPDATED_AT FROM prohibitions_users WHERE PROHIBITIONS_ID = :p0 AND USERS_ID = :p1';
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
            /** @var ChildProhibitionsUsers $obj */
            $obj = new ChildProhibitionsUsers();
            $obj->hydrate($row);
            ProhibitionsUsersTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildProhibitionsUsers|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ProhibitionsUsersTableMap::COL_USERS_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ProhibitionsUsersTableMap::COL_USERS_ID, $key[1], Criteria::EQUAL);
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
     * $query->filterByProhibitionId(1234); // WHERE prohibitions_id = 1234
     * $query->filterByProhibitionId(array(12, 34)); // WHERE prohibitions_id IN (12, 34)
     * $query->filterByProhibitionId(array('min' => 12)); // WHERE prohibitions_id > 12
     * </code>
     *
     * @see       filterByProhibition()
     *
     * @param     mixed $prohibitionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function filterByProhibitionId($prohibitionId = null, $comparison = null)
    {
        if (is_array($prohibitionId)) {
            $useMinMax = false;
            if (isset($prohibitionId['min'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID, $prohibitionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($prohibitionId['max'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID, $prohibitionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID, $prohibitionId, $comparison);
    }

    /**
     * Filter the query on the users_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE users_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE users_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE users_id > 12
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
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_USERS_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_USERS_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionsUsersTableMap::COL_USERS_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the prohibited_until column
     *
     * Example usage:
     * <code>
     * $query->filterByProhibitedUntil('2011-03-14'); // WHERE prohibited_until = '2011-03-14'
     * $query->filterByProhibitedUntil('now'); // WHERE prohibited_until = '2011-03-14'
     * $query->filterByProhibitedUntil(array('max' => 'yesterday')); // WHERE prohibited_until > '2011-03-13'
     * </code>
     *
     * @param     mixed $prohibitedUntil The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function filterByProhibitedUntil($prohibitedUntil = null, $comparison = null)
    {
        if (is_array($prohibitedUntil)) {
            $useMinMax = false;
            if (isset($prohibitedUntil['min'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_PROHIBITED_UNTIL, $prohibitedUntil['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($prohibitedUntil['max'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_PROHIBITED_UNTIL, $prohibitedUntil['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionsUsersTableMap::COL_PROHIBITED_UNTIL, $prohibitedUntil, $comparison);
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
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionsUsersTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ProhibitionsUsersTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionsUsersTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Phlopsi\AccessControl\Propel\Prohibition object
     *
     * @param \Phlopsi\AccessControl\Propel\Prohibition|ObjectCollection $prohibition The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function filterByProhibition($prohibition, $comparison = null)
    {
        if ($prohibition instanceof \Phlopsi\AccessControl\Propel\Prohibition) {
            return $this
                ->addUsingAlias(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID, $prohibition->getId(), $comparison);
        } elseif ($prohibition instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID, $prohibition->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByProhibition() only accepts arguments of type \Phlopsi\AccessControl\Propel\Prohibition or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Prohibition relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function joinProhibition($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Prohibition');

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
            $this->addJoinObject($join, 'Prohibition');
        }

        return $this;
    }

    /**
     * Use the Prohibition relation Prohibition object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Phlopsi\AccessControl\Propel\ProhibitionQuery A secondary query class using the current class as primary query
     */
    public function useProhibitionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProhibition($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Prohibition', '\Phlopsi\AccessControl\Propel\ProhibitionQuery');
    }

    /**
     * Filter the query by a related \Phlopsi\AccessControl\Propel\User object
     *
     * @param \Phlopsi\AccessControl\Propel\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Phlopsi\AccessControl\Propel\User) {
            return $this
                ->addUsingAlias(ProhibitionsUsersTableMap::COL_USERS_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ProhibitionsUsersTableMap::COL_USERS_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Phlopsi\AccessControl\Propel\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
     * @return \Phlopsi\AccessControl\Propel\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Phlopsi\AccessControl\Propel\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildProhibitionsUsers $prohibitionsUsers Object to remove from the list of results
     *
     * @return $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function prune($prohibitionsUsers = null)
    {
        if ($prohibitionsUsers) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID), $prohibitionsUsers->getProhibitionId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ProhibitionsUsersTableMap::COL_USERS_ID), $prohibitionsUsers->getUserId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the prohibitions_users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionsUsersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ProhibitionsUsersTableMap::clearInstancePool();
            ProhibitionsUsersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionsUsersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ProhibitionsUsersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ProhibitionsUsersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ProhibitionsUsersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ProhibitionsUsersTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ProhibitionsUsersTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ProhibitionsUsersTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ProhibitionsUsersTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ProhibitionsUsersTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildProhibitionsUsersQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ProhibitionsUsersTableMap::COL_CREATED_AT);
    }

} // ProhibitionsUsersQuery
