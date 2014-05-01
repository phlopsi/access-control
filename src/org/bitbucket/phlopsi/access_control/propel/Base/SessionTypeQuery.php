<?php
namespace org\bitbucket\phlopsi\access_control\propel\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use org\bitbucket\phlopsi\access_control\propel\SessionType as ChildSessionType;
use org\bitbucket\phlopsi\access_control\propel\SessionTypeQuery as ChildSessionTypeQuery;
use org\bitbucket\phlopsi\access_control\propel\Map\SessionTypeTableMap;

/**
 * Base class that represents a query for the 'session_types' table.
 *
 *
 *
 * @method     ChildSessionTypeQuery orderByExternalId($order = Criteria::ASC) Order by the external_id column
 * @method     ChildSessionTypeQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method     ChildSessionTypeQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method     ChildSessionTypeQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 * @method     ChildSessionTypeQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildSessionTypeQuery groupByExternalId() Group by the external_id column
 * @method     ChildSessionTypeQuery groupByTreeLeft() Group by the tree_left column
 * @method     ChildSessionTypeQuery groupByTreeRight() Group by the tree_right column
 * @method     ChildSessionTypeQuery groupByTreeLevel() Group by the tree_level column
 * @method     ChildSessionTypeQuery groupById() Group by the id column
 *
 * @method     ChildSessionTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSessionTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSessionTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSessionTypeQuery leftJoinRolesSessionTypes($relationAlias = null) Adds a LEFT JOIN clause to the query using the RolesSessionTypes relation
 * @method     ChildSessionTypeQuery rightJoinRolesSessionTypes($relationAlias = null) Adds a RIGHT JOIN clause to the query using the RolesSessionTypes relation
 * @method     ChildSessionTypeQuery innerJoinRolesSessionTypes($relationAlias = null) Adds a INNER JOIN clause to the query using the RolesSessionTypes relation
 *
 * @method     \org\bitbucket\phlopsi\access_control\propel\RolesSessionTypesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSessionType findOne(ConnectionInterface $con = null) Return the first ChildSessionType matching the query
 * @method     ChildSessionType findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSessionType matching the query, or a new ChildSessionType object populated from the query conditions when no match is found
 *
 * @method     ChildSessionType findOneByExternalId(string $external_id) Return the first ChildSessionType filtered by the external_id column
 * @method     ChildSessionType findOneByTreeLeft(int $tree_left) Return the first ChildSessionType filtered by the tree_left column
 * @method     ChildSessionType findOneByTreeRight(int $tree_right) Return the first ChildSessionType filtered by the tree_right column
 * @method     ChildSessionType findOneByTreeLevel(int $tree_level) Return the first ChildSessionType filtered by the tree_level column
 * @method     ChildSessionType findOneById(int $id) Return the first ChildSessionType filtered by the id column
 *
 * @method     ChildSessionType[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSessionType objects based on current ModelCriteria
 * @method     ChildSessionType[]|ObjectCollection findByExternalId(string $external_id) Return ChildSessionType objects filtered by the external_id column
 * @method     ChildSessionType[]|ObjectCollection findByTreeLeft(int $tree_left) Return ChildSessionType objects filtered by the tree_left column
 * @method     ChildSessionType[]|ObjectCollection findByTreeRight(int $tree_right) Return ChildSessionType objects filtered by the tree_right column
 * @method     ChildSessionType[]|ObjectCollection findByTreeLevel(int $tree_level) Return ChildSessionType objects filtered by the tree_level column
 * @method     ChildSessionType[]|ObjectCollection findById(int $id) Return ChildSessionType objects filtered by the id column
 * @method     ChildSessionType[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SessionTypeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of \org\bitbucket\phlopsi\access_control\propel\Base\SessionTypeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'access_control',
        $modelName = '\\org\\bitbucket\\phlopsi\\access_control\\propel\\SessionType', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSessionTypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSessionTypeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSessionTypeQuery) {
            return $criteria;
        }
        $query = new ChildSessionTypeQuery();
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
     * @return ChildSessionType|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SessionTypeTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SessionTypeTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select || $this->selectColumns || $this->asColumns || $this->selectModifiers || $this->map || $this->having || $this->joins) {
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
     * @return ChildSessionType A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT EXTERNAL_ID, TREE_LEFT, TREE_RIGHT, TREE_LEVEL, ID FROM session_types WHERE ID = :p0';
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
            /** @var ChildSessionType $obj */
            $obj = new ChildSessionType();
            $obj->hydrate($row);
            SessionTypeTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSessionType|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SessionTypeTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SessionTypeTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildSessionTypeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SessionTypeTableMap::COL_EXTERNAL_ID, $externalId, $comparison);
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
     * @return $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(SessionTypeTableMap::COL_TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(SessionTypeTableMap::COL_TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionTypeTableMap::COL_TREE_LEFT, $treeLeft, $comparison);
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
     * @return $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(SessionTypeTableMap::COL_TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(SessionTypeTableMap::COL_TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionTypeTableMap::COL_TREE_RIGHT, $treeRight, $comparison);
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
     * @return $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(SessionTypeTableMap::COL_TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(SessionTypeTableMap::COL_TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionTypeTableMap::COL_TREE_LEVEL, $treeLevel, $comparison);
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
     * @return $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SessionTypeTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SessionTypeTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SessionTypeTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \org\bitbucket\phlopsi\access_control\propel\RolesSessionTypes object
     *
     * @param \org\bitbucket\phlopsi\access_control\propel\RolesSessionTypes|ObjectCollection $rolesSessionTypes  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionTypeQuery The current query, for fluid interface
     */
    public function filterByRolesSessionTypes($rolesSessionTypes, $comparison = null)
    {
        if ($rolesSessionTypes instanceof \org\bitbucket\phlopsi\access_control\propel\RolesSessionTypes) {
            return $this
                    ->addUsingAlias(SessionTypeTableMap::COL_ID, $rolesSessionTypes->getSessionTypeId(), $comparison);
        } elseif ($rolesSessionTypes instanceof ObjectCollection) {
            return $this
                    ->useRolesSessionTypesQuery()
                    ->filterByPrimaryKeys($rolesSessionTypes->getPrimaryKeys())
                    ->endUse();
        } else {
            throw new PropelException('filterByRolesSessionTypes() only accepts arguments of type \org\bitbucket\phlopsi\access_control\propel\RolesSessionTypes or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the RolesSessionTypes relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function joinRolesSessionTypes($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('RolesSessionTypes');

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
            $this->addJoinObject($join, 'RolesSessionTypes');
        }

        return $this;
    }

    /**
     * Use the RolesSessionTypes relation RolesSessionTypes object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \org\bitbucket\phlopsi\access_control\propel\RolesSessionTypesQuery A secondary query class using the current class as primary query
     */
    public function useRolesSessionTypesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
                ->joinRolesSessionTypes($relationAlias, $joinType)
                ->useQuery($relationAlias ? $relationAlias : 'RolesSessionTypes',
                    '\org\bitbucket\phlopsi\access_control\propel\RolesSessionTypesQuery');
    }

    /**
     * Filter the query by a related Role object
     * using the roles_session_types table as cross reference
     *
     * @param Role $role the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSessionTypeQuery The current query, for fluid interface
     */
    public function filterByRole($role, $comparison = Criteria::EQUAL)
    {
        return $this
                ->useRolesSessionTypesQuery()
                ->filterByRole($role, $comparison)
                ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSessionType $sessionType Object to remove from the list of results
     *
     * @return $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function prune($sessionType = null)
    {
        if ($sessionType) {
            $this->addUsingAlias(SessionTypeTableMap::COL_ID, $sessionType->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the session_types table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SessionTypeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
                $affectedRows = 0; // initialize var to track total num of affected rows
                $affectedRows += parent::doDeleteAll($con);
                // Because this db requires some delete cascade/set null emulation, we have to
                // clear the cached instance *after* the emulation has happened (since
                // instances get re-added by the select statement contained therein).
                SessionTypeTableMap::clearInstancePool();
                SessionTypeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SessionTypeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SessionTypeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
                $affectedRows = 0; // initialize var to track total num of affected rows

                SessionTypeTableMap::removeInstanceFromPool($criteria);

                $affectedRows += ModelCriteria::delete($con);
                SessionTypeTableMap::clearRelatedInstancePool();

                return $affectedRows;
            });
    }

    // nested_set behavior
    /**
     * Filter the query to restrict the result to descendants of an object
     *
     * @param     ChildSessionType $sessionType The object to use for descendant search
     *
     * @return    ChildSessionTypeQuery The current query, for fluid interface
     */
    public function descendantsOf($sessionType)
    {
        return $this
                ->addUsingAlias(ChildSessionType::LEFT_COL, $sessionType->getLeftValue(), Criteria::GREATER_THAN)
                ->addUsingAlias(ChildSessionType::LEFT_COL, $sessionType->getRightValue(), Criteria::LESS_THAN);
    }

    /**
     * Filter the query to restrict the result to the branch of an object.
     * Same as descendantsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     ChildSessionType $sessionType The object to use for branch search
     *
     * @return    ChildSessionTypeQuery The current query, for fluid interface
     */
    public function branchOf($sessionType)
    {
        return $this
                ->addUsingAlias(ChildSessionType::LEFT_COL, $sessionType->getLeftValue(), Criteria::GREATER_EQUAL)
                ->addUsingAlias(ChildSessionType::LEFT_COL, $sessionType->getRightValue(), Criteria::LESS_EQUAL);
    }

    /**
     * Filter the query to restrict the result to children of an object
     *
     * @param     ChildSessionType $sessionType The object to use for child search
     *
     * @return    $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function childrenOf($sessionType)
    {
        return $this
                ->descendantsOf($sessionType)
                ->addUsingAlias(ChildSessionType::LEVEL_COL, $sessionType->getLevel() + 1, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to siblings of an object.
     * The result does not include the object passed as parameter.
     *
     * @param     ChildSessionType $sessionType The object to use for sibling search
     * @param      ConnectionInterface $con Connection to use.
     *
     * @return    $this|ChildSessionTypeQuery The current query, for fluid interface
     */
    public function siblingsOf($sessionType, ConnectionInterface $con = null)
    {
        if ($sessionType->isRoot()) {
            return $this->
                    add(ChildSessionType::LEVEL_COL, '1<>1', Criteria::CUSTOM);
        } else {
            return $this
                    ->childrenOf($sessionType->getParent($con))
                    ->prune($sessionType);
        }
    }

    /**
     * Filter the query to restrict the result to ancestors of an object
     *
     * @param     ChildSessionType $sessionType The object to use for ancestors search
     *
     * @return    ChildSessionTypeQuery The current query, for fluid interface
     */
    public function ancestorsOf($sessionType)
    {
        return $this
                ->addUsingAlias(ChildSessionType::LEFT_COL, $sessionType->getLeftValue(), Criteria::LESS_THAN)
                ->addUsingAlias(ChildSessionType::RIGHT_COL, $sessionType->getRightValue(), Criteria::GREATER_THAN);
    }

    /**
     * Filter the query to restrict the result to roots of an object.
     * Same as ancestorsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     ChildSessionType $sessionType The object to use for roots search
     *
     * @return    ChildSessionTypeQuery The current query, for fluid interface
     */
    public function rootsOf($sessionType)
    {
        return $this
                ->addUsingAlias(ChildSessionType::LEFT_COL, $sessionType->getLeftValue(), Criteria::LESS_EQUAL)
                ->addUsingAlias(ChildSessionType::RIGHT_COL, $sessionType->getRightValue(), Criteria::GREATER_EQUAL);
    }

    /**
     * Order the result by branch, i.e. natural tree order
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    ChildSessionTypeQuery The current query, for fluid interface
     */
    public function orderByBranch($reverse = false)
    {
        if ($reverse) {
            return $this
                    ->addDescendingOrderByColumn(ChildSessionType::LEFT_COL);
        } else {
            return $this
                    ->addAscendingOrderByColumn(ChildSessionType::LEFT_COL);
        }
    }

    /**
     * Order the result by level, the closer to the root first
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    ChildSessionTypeQuery The current query, for fluid interface
     */
    public function orderByLevel($reverse = false)
    {
        if ($reverse) {
            return $this
                    ->addDescendingOrderByColumn(ChildSessionType::LEVEL_COL)
                    ->addDescendingOrderByColumn(ChildSessionType::LEFT_COL);
        } else {
            return $this
                    ->addAscendingOrderByColumn(ChildSessionType::LEVEL_COL)
                    ->addAscendingOrderByColumn(ChildSessionType::LEFT_COL);
        }
    }

    /**
     * Returns the root node for the tree
     *
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     ChildSessionType The tree root object
     */
    public function findRoot($con = null)
    {
        return $this
                ->addUsingAlias(ChildSessionType::LEFT_COL, 1, Criteria::EQUAL)
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
     * @return     ChildSessionType            Propel object for root node
     */
    static public function retrieveRoot(ConnectionInterface $con = null)
    {
        $c = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        $c->add(ChildSessionType::LEFT_COL, 1, Criteria::EQUAL);

        return ChildSessionTypeQuery::create(null, $c)->findOne($con);
    }

    /**
     * Returns the whole tree node for a given scope
     *
     * @param      Criteria $criteria    Optional Criteria to filter the query
     * @param      ConnectionInterface $con    Connection to use.
     * @return     ChildSessionType            Propel object for root node
     */
    static public function retrieveTree(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        if (null === $criteria) {
            $criteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        }
        $criteria->addAscendingOrderByColumn(ChildSessionType::LEFT_COL);

        return ChildSessionTypeQuery::create(null, $criteria)->find($con);
    }

    /**
     * Tests if node is valid
     *
     * @param      ChildSessionType $node    Propel object for src node
     * @return     bool
     */
    static public function isValid(ChildSessionType $node = null)
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

        return SessionTypeTableMap::doDeleteAll($con);
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
            $con = Propel::getServiceContainer()->getWriteConnection(SessionTypeTableMap::DATABASE_NAME);
        }

        // Shift left column values
        $whereCriteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(ChildSessionType::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(ChildSessionType::LEFT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildSessionType::LEFT_COL,
            array('raw' => ChildSessionType::LEFT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);

        // Shift right column values
        $whereCriteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(ChildSessionType::RIGHT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(ChildSessionType::RIGHT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);

        $valuesCriteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildSessionType::RIGHT_COL,
            array('raw' => ChildSessionType::RIGHT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

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
            $con = Propel::getServiceContainer()->getWriteConnection(SessionTypeTableMap::DATABASE_NAME);
        }

        $whereCriteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        $whereCriteria->add(ChildSessionType::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        $whereCriteria->add(ChildSessionType::RIGHT_COL, $last, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildSessionType::LEVEL_COL,
            array('raw' => ChildSessionType::LEVEL_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Reload all already loaded nodes to sync them with updated db
     *
     * @param      ChildSessionType $prune        Object to prune from the update
     * @param      ConnectionInterface $con        Connection to use.
     */
    static public function updateLoadedNodes($prune = null, ConnectionInterface $con = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            $keys = array();
            foreach (SessionTypeTableMap::$instances as $obj) {
                if (!$prune || !$prune->equals($obj)) {
                    $keys[] = $obj->getPrimaryKey();
                }
            }

            if (!empty($keys)) {
                // We don't need to alter the object instance pool; we're just modifying these ones
                // already in the pool.
                $criteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
                $criteria->add(SessionTypeTableMap::COL_ID, $keys, Criteria::IN);
                $dataFetcher = ChildSessionTypeQuery::create(null, $criteria)->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
                while ($row = $dataFetcher->fetch()) {
                    $key = SessionTypeTableMap::getPrimaryKeyHashFromRow($row, 0);
                    if (null !== ($object = SessionTypeTableMap::getInstanceFromPool($key))) {
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
        ChildSessionTypeQuery::shiftRLValues(2, $left, null, $con);

        // Update all loaded nodes
        ChildSessionTypeQuery::updateLoadedNodes($prune, $con);
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      ConnectionInterface $con    Connection to use.
     */
    static public function fixLevels(ConnectionInterface $con = null)
    {
        $c = new Criteria();
        $c->addAscendingOrderByColumn(ChildSessionType::LEFT_COL);
        $dataFetcher = ChildSessionTypeQuery::create(null, $c)->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);

        // set the class once to avoid overhead in the loop
        $cls = SessionTypeTableMap::getOMClass(false);
        $level = null;
        // iterate over the statement
        while ($row = $dataFetcher->fetch()) {

            // hydrate object
            $key = SessionTypeTableMap::getPrimaryKeyHashFromRow($row, 0);
            if (null === ($obj = SessionTypeTableMap::getInstanceFromPool($key))) {
                $obj = new $cls();
                $obj->hydrate($row);
                SessionTypeTableMap::addInstanceToPool($obj, $key);
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
        $whereCriteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        $whereCriteria->add(ChildSessionType::LEFT_COL, 0, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(SessionTypeTableMap::DATABASE_NAME);
        $valuesCriteria->add(ChildSessionType::SCOPE_COL, $scope, Criteria::EQUAL);

        $whereCriteria->doUpdate($valuesCriteria, $con);
    }

}

// SessionTypeQuery
