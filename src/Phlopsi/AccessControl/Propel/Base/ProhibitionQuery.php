<?php

namespace Phlopsi\AccessControl\Propel\Base;

use \Exception;
use \PDO;
use Phlopsi\AccessControl\Propel\Prohibition as ChildProhibition;
use Phlopsi\AccessControl\Propel\ProhibitionQuery as ChildProhibitionQuery;
use Phlopsi\AccessControl\Propel\Map\ProhibitionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;

/**
 * Base class that represents a query for the 'prohibitions' table.
 *
 *
 *
 * @method     ChildProhibitionQuery orderByExternalId($order = Criteria::ASC) Order by the external_id column
 * @method     ChildProhibitionQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method     ChildProhibitionQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method     ChildProhibitionQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 * @method     ChildProhibitionQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildProhibitionQuery groupByExternalId() Group by the external_id column
 * @method     ChildProhibitionQuery groupByTreeLeft() Group by the tree_left column
 * @method     ChildProhibitionQuery groupByTreeRight() Group by the tree_right column
 * @method     ChildProhibitionQuery groupByTreeLevel() Group by the tree_level column
 * @method     ChildProhibitionQuery groupById() Group by the id column
 *
 * @method     ChildProhibitionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildProhibitionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildProhibitionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildProhibitionQuery leftJoinProhibitionsRoles($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProhibitionsRoles relation
 * @method     ChildProhibitionQuery rightJoinProhibitionsRoles($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProhibitionsRoles relation
 * @method     ChildProhibitionQuery innerJoinProhibitionsRoles($relationAlias = null) Adds a INNER JOIN clause to the query using the ProhibitionsRoles relation
 *
 * @method     ChildProhibitionQuery leftJoinProhibitionsUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProhibitionsUsers relation
 * @method     ChildProhibitionQuery rightJoinProhibitionsUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProhibitionsUsers relation
 * @method     ChildProhibitionQuery innerJoinProhibitionsUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the ProhibitionsUsers relation
 *
 * @method     \Phlopsi\AccessControl\Propel\ProhibitionsRolesQuery|\Phlopsi\AccessControl\Propel\ProhibitionsUsersQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildProhibition findOne(ConnectionInterface $con = null) Return the first ChildProhibition matching the query
 * @method     ChildProhibition findOneOrCreate(ConnectionInterface $con = null) Return the first ChildProhibition matching the query, or a new ChildProhibition object populated from the query conditions when no match is found
 *
 * @method     ChildProhibition findOneByExternalId(string $external_id) Return the first ChildProhibition filtered by the external_id column
 * @method     ChildProhibition findOneByTreeLeft(int $tree_left) Return the first ChildProhibition filtered by the tree_left column
 * @method     ChildProhibition findOneByTreeRight(int $tree_right) Return the first ChildProhibition filtered by the tree_right column
 * @method     ChildProhibition findOneByTreeLevel(int $tree_level) Return the first ChildProhibition filtered by the tree_level column
 * @method     ChildProhibition findOneById(int $id) Return the first ChildProhibition filtered by the id column
 *
 * @method     ChildProhibition[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildProhibition objects based on current ModelCriteria
 * @method     ChildProhibition[]|ObjectCollection findByExternalId(string $external_id) Return ChildProhibition objects filtered by the external_id column
 * @method     ChildProhibition[]|ObjectCollection findByTreeLeft(int $tree_left) Return ChildProhibition objects filtered by the tree_left column
 * @method     ChildProhibition[]|ObjectCollection findByTreeRight(int $tree_right) Return ChildProhibition objects filtered by the tree_right column
 * @method     ChildProhibition[]|ObjectCollection findByTreeLevel(int $tree_level) Return ChildProhibition objects filtered by the tree_level column
 * @method     ChildProhibition[]|ObjectCollection findById(int $id) Return ChildProhibition objects filtered by the id column
 * @method     ChildProhibition[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ProhibitionQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Phlopsi\AccessControl\Propel\Base\ProhibitionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'access_control', $modelName = '\\Phlopsi\\AccessControl\\Propel\\Prohibition', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildProhibitionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildProhibitionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildProhibitionQuery) {
            return $criteria;
        }
        $query = new ChildProhibitionQuery();
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
     * @return ChildProhibition|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ProhibitionTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ProhibitionTableMap::DATABASE_NAME);
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
     * @return ChildProhibition A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT external_id, tree_left, tree_right, tree_level, id FROM prohibitions WHERE id = :p0';
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
            /** @var ChildProhibition $obj */
            $obj = new ChildProhibition();
            $obj->hydrate($row);
            ProhibitionTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildProhibition|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ProhibitionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ProhibitionTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ProhibitionTableMap::COL_EXTERNAL_ID, $externalId, $comparison);
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
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(ProhibitionTableMap::COL_TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(ProhibitionTableMap::COL_TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionTableMap::COL_TREE_LEFT, $treeLeft, $comparison);
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
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(ProhibitionTableMap::COL_TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(ProhibitionTableMap::COL_TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionTableMap::COL_TREE_RIGHT, $treeRight, $comparison);
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
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(ProhibitionTableMap::COL_TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(ProhibitionTableMap::COL_TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionTableMap::COL_TREE_LEVEL, $treeLevel, $comparison);
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
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ProhibitionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ProhibitionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ProhibitionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \Phlopsi\AccessControl\Propel\ProhibitionsRoles object
     *
     * @param \Phlopsi\AccessControl\Propel\ProhibitionsRoles|ObjectCollection $prohibitionsRoles  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterByProhibitionsRoles($prohibitionsRoles, $comparison = null)
    {
        if ($prohibitionsRoles instanceof \Phlopsi\AccessControl\Propel\ProhibitionsRoles) {
            return $this
                ->addUsingAlias(ProhibitionTableMap::COL_ID, $prohibitionsRoles->getProhibitionId(), $comparison);
        } elseif ($prohibitionsRoles instanceof ObjectCollection) {
            return $this
                ->useProhibitionsRolesQuery()
                ->filterByPrimaryKeys($prohibitionsRoles->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProhibitionsRoles() only accepts arguments of type \Phlopsi\AccessControl\Propel\ProhibitionsRoles or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProhibitionsRoles relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function joinProhibitionsRoles($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProhibitionsRoles');

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
            $this->addJoinObject($join, 'ProhibitionsRoles');
        }

        return $this;
    }

    /**
     * Use the ProhibitionsRoles relation ProhibitionsRoles object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Phlopsi\AccessControl\Propel\ProhibitionsRolesQuery A secondary query class using the current class as primary query
     */
    public function useProhibitionsRolesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProhibitionsRoles($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProhibitionsRoles', '\Phlopsi\AccessControl\Propel\ProhibitionsRolesQuery');
    }

    /**
     * Filter the query by a related \Phlopsi\AccessControl\Propel\ProhibitionsUsers object
     *
     * @param \Phlopsi\AccessControl\Propel\ProhibitionsUsers|ObjectCollection $prohibitionsUsers  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterByProhibitionsUsers($prohibitionsUsers, $comparison = null)
    {
        if ($prohibitionsUsers instanceof \Phlopsi\AccessControl\Propel\ProhibitionsUsers) {
            return $this
                ->addUsingAlias(ProhibitionTableMap::COL_ID, $prohibitionsUsers->getProhibitionId(), $comparison);
        } elseif ($prohibitionsUsers instanceof ObjectCollection) {
            return $this
                ->useProhibitionsUsersQuery()
                ->filterByPrimaryKeys($prohibitionsUsers->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProhibitionsUsers() only accepts arguments of type \Phlopsi\AccessControl\Propel\ProhibitionsUsers or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProhibitionsUsers relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
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
     * @return \Phlopsi\AccessControl\Propel\ProhibitionsUsersQuery A secondary query class using the current class as primary query
     */
    public function useProhibitionsUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProhibitionsUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProhibitionsUsers', '\Phlopsi\AccessControl\Propel\ProhibitionsUsersQuery');
    }

    /**
     * Filter the query by a related Role object
     * using the prohibitions_roles table as cross reference
     *
     * @param Role $role the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterByRole($role, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useProhibitionsRolesQuery()
            ->filterByRole($role, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the prohibitions_users table as cross reference
     *
     * @param User $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildProhibitionQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useProhibitionsUsersQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildProhibition $prohibition Object to remove from the list of results
     *
     * @return $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function prune($prohibition = null)
    {
        if ($prohibition) {
            $this->addUsingAlias(ProhibitionTableMap::COL_ID, $prohibition->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the prohibitions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ProhibitionTableMap::clearInstancePool();
            ProhibitionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ProhibitionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ProhibitionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ProhibitionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // nested_set behavior

    /**
     * Filter the query to restrict the result to descendants of an object
     *
     * @param     ChildProhibition $prohibition The object to use for descendant search
     *
     * @return    ChildProhibitionQuery The current query, for fluid interface
     */
    public function descendantsOf($prohibition)
    {
        return $this
            ->addUsingAlias(ChildProhibition::LEFT_COL, $prohibition->getLeftValue(), Criteria::GREATER_THAN)
            ->addUsingAlias(ChildProhibition::LEFT_COL, $prohibition->getRightValue(), Criteria::LESS_THAN);
    }

    /**
     * Filter the query to restrict the result to the branch of an object.
     * Same as descendantsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     ChildProhibition $prohibition The object to use for branch search
     *
     * @return    ChildProhibitionQuery The current query, for fluid interface
     */
    public function branchOf($prohibition)
    {
        return $this
            ->addUsingAlias(ChildProhibition::LEFT_COL, $prohibition->getLeftValue(), Criteria::GREATER_EQUAL)
            ->addUsingAlias(ChildProhibition::LEFT_COL, $prohibition->getRightValue(), Criteria::LESS_EQUAL);
    }

    /**
     * Filter the query to restrict the result to children of an object
     *
     * @param     ChildProhibition $prohibition The object to use for child search
     *
     * @return    $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function childrenOf($prohibition)
    {
        return $this
            ->descendantsOf($prohibition)
            ->addUsingAlias(ChildProhibition::LEVEL_COL, $prohibition->getLevel() + 1, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to siblings of an object.
     * The result does not include the object passed as parameter.
     *
     * @param     ChildProhibition $prohibition The object to use for sibling search
     * @param      ConnectionInterface $con Connection to use.
     *
     * @return    $this|ChildProhibitionQuery The current query, for fluid interface
     */
    public function siblingsOf($prohibition, ConnectionInterface $con = null)
    {
        if ($prohibition->isRoot()) {
            return $this->
                add(ChildProhibition::LEVEL_COL, '1<>1', Criteria::CUSTOM);
        } else {
            return $this
                ->childrenOf($prohibition->getParent($con))
                ->prune($prohibition);
        }
    }

    /**
     * Filter the query to restrict the result to ancestors of an object
     *
     * @param     ChildProhibition $prohibition The object to use for ancestors search
     *
     * @return    ChildProhibitionQuery The current query, for fluid interface
     */
    public function ancestorsOf($prohibition)
    {
        return $this
            ->addUsingAlias(ChildProhibition::LEFT_COL, $prohibition->getLeftValue(), Criteria::LESS_THAN)
            ->addUsingAlias(ChildProhibition::RIGHT_COL, $prohibition->getRightValue(), Criteria::GREATER_THAN);
    }

    /**
     * Filter the query to restrict the result to roots of an object.
     * Same as ancestorsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     ChildProhibition $prohibition The object to use for roots search
     *
     * @return    ChildProhibitionQuery The current query, for fluid interface
     */
    public function rootsOf($prohibition)
    {
        return $this
            ->addUsingAlias(ChildProhibition::LEFT_COL, $prohibition->getLeftValue(), Criteria::LESS_EQUAL)
            ->addUsingAlias(ChildProhibition::RIGHT_COL, $prohibition->getRightValue(), Criteria::GREATER_EQUAL);
    }

    /**
     * Order the result by branch, i.e. natural tree order
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    ChildProhibitionQuery The current query, for fluid interface
     */
    public function orderByBranch($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addDescendingOrderByColumn(ChildProhibition::LEFT_COL);
        } else {
            return $this
                ->addAscendingOrderByColumn(ChildProhibition::LEFT_COL);
        }
    }

    /**
     * Order the result by level, the closer to the root first
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    ChildProhibitionQuery The current query, for fluid interface
     */
    public function orderByLevel($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addDescendingOrderByColumn(ChildProhibition::LEVEL_COL)
                ->addDescendingOrderByColumn(ChildProhibition::LEFT_COL);
        } else {
            return $this
                ->addAscendingOrderByColumn(ChildProhibition::LEVEL_COL)
                ->addAscendingOrderByColumn(ChildProhibition::LEFT_COL);
        }
    }

    /**
     * Returns the root node for the tree
     *
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     ChildProhibition The tree root object
     */
    public function findRoot($con = null)
    {
        return $this
            ->addUsingAlias(ChildProhibition::LEFT_COL, 1, Criteria::EQUAL)
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
     * @return     ChildProhibition            Propel object for root node
     */
    public static function retrieveRoot(ConnectionInterface $con = null)
    {
        $c = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        $c->add(ChildProhibition::LEFT_COL, 1, Criteria::EQUAL);

        return ChildProhibitionQuery::create(null, $c)->findOne($con);
    }

    /**
     * Returns the whole tree node for a given scope
     *
     * @param      Criteria $criteria    Optional Criteria to filter the query
     * @param      ConnectionInterface $con    Connection to use.
     * @return     ChildProhibition            Propel object for root node
     */
    public static function retrieveTree(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        if (null === $criteria) {
            $criteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        }
        $criteria->addAscendingOrderByColumn(ChildProhibition::LEFT_COL);

        return ChildProhibitionQuery::create(null, $criteria)->find($con);
    }

    /**
     * Tests if node is valid
     *
     * @param      ChildProhibition $node    Propel object for src node
     * @return     bool
     */
    public static function isValid(ChildProhibition $node = null)
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
    public static function deleteTree(ConnectionInterface $con = null)
    {

        return ProhibitionTableMap::doDeleteAll($con);
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
    public static function shiftRLValues($delta, $first, $last = null, ConnectionInterface $con = null)
    {
        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionTableMap::DATABASE_NAME);
        }

        // Shift left column values
        $whereCriteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(ChildProhibition::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(ChildProhibition::LEFT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildProhibition::LEFT_COL, array('raw' => ChildProhibition::LEFT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);

        // Shift right column values
        $whereCriteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(ChildProhibition::RIGHT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(ChildProhibition::RIGHT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildProhibition::RIGHT_COL, array('raw' => ChildProhibition::RIGHT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

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
    public static function shiftLevel($delta, $first, $last, ConnectionInterface $con = null)
    {
        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionTableMap::DATABASE_NAME);
        }

        $whereCriteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        $whereCriteria->add(ChildProhibition::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        $whereCriteria->add(ChildProhibition::RIGHT_COL, $last, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildProhibition::LEVEL_COL, array('raw' => ChildProhibition::LEVEL_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Reload all already loaded nodes to sync them with updated db
     *
     * @param      ChildProhibition $prune        Object to prune from the update
     * @param      ConnectionInterface $con        Connection to use.
     */
    public static function updateLoadedNodes($prune = null, ConnectionInterface $con = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            $keys = array();
            foreach (ProhibitionTableMap::$instances as $obj) {
                if (!$prune || !$prune->equals($obj)) {
                    $keys[] = $obj->getPrimaryKey();
                }
            }

            if (!empty($keys)) {
                // We don't need to alter the object instance pool; we're just modifying these ones
                // already in the pool.
                $criteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
                $criteria->add(ProhibitionTableMap::COL_ID, $keys, Criteria::IN);
                $dataFetcher = ChildProhibitionQuery::create(null, $criteria)->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
                while ($row = $dataFetcher->fetch()) {
                    $key = ProhibitionTableMap::getPrimaryKeyHashFromRow($row, 0);
                    if (null !== ($object = ProhibitionTableMap::getInstanceFromPool($key))) {
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
    public static function makeRoomForLeaf($left, $prune = null, ConnectionInterface $con = null)
    {
        // Update database nodes
        ChildProhibitionQuery::shiftRLValues(2, $left, null, $con);

        // Update all loaded nodes
        ChildProhibitionQuery::updateLoadedNodes($prune, $con);
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      ConnectionInterface $con    Connection to use.
     */
    public static function fixLevels(ConnectionInterface $con = null)
    {
        $c = new Criteria();
        $c->addAscendingOrderByColumn(ChildProhibition::LEFT_COL);
        $dataFetcher = ChildProhibitionQuery::create(null, $c)->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);

        // set the class once to avoid overhead in the loop
        $cls = ProhibitionTableMap::getOMClass(false);
        $level = null;
        // iterate over the statement
        while ($row = $dataFetcher->fetch()) {
            // hydrate object
            $key = ProhibitionTableMap::getPrimaryKeyHashFromRow($row, 0);
            if (null === ($obj = ProhibitionTableMap::getInstanceFromPool($key))) {
                $obj = new $cls();
                $obj->hydrate($row);
                ProhibitionTableMap::addInstanceToPool($obj, $key);
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
        $whereCriteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        $whereCriteria->add(ChildProhibition::LEFT_COL, 0, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(ProhibitionTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildProhibition::SCOPE_COL, $scope, Criteria::EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
    }
} // ProhibitionQuery
