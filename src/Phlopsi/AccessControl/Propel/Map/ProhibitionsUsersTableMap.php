<?php

namespace Phlopsi\AccessControl\Propel\Map;

use Phlopsi\AccessControl\Propel\ProhibitionsUsers;
use Phlopsi\AccessControl\Propel\ProhibitionsUsersQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'prohibitions_users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ProhibitionsUsersTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Phlopsi.AccessControl.Propel.Map.ProhibitionsUsersTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'access_control';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'prohibitions_users';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Phlopsi\\AccessControl\\Propel\\ProhibitionsUsers';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Phlopsi.AccessControl.Propel.ProhibitionsUsers';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the prohibitions_id field
     */
    const COL_PROHIBITIONS_ID = 'prohibitions_users.prohibitions_id';

    /**
     * the column name for the users_id field
     */
    const COL_USERS_ID = 'prohibitions_users.users_id';

    /**
     * the column name for the prohibited_until field
     */
    const COL_PROHIBITED_UNTIL = 'prohibitions_users.prohibited_until';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'prohibitions_users.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'prohibitions_users.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('ProhibitionId', 'UserId', 'ProhibitedUntil', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('prohibitionId', 'userId', 'prohibitedUntil', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID, ProhibitionsUsersTableMap::COL_USERS_ID, ProhibitionsUsersTableMap::COL_PROHIBITED_UNTIL, ProhibitionsUsersTableMap::COL_CREATED_AT, ProhibitionsUsersTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('prohibitions_id', 'users_id', 'prohibited_until', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('ProhibitionId' => 0, 'UserId' => 1, 'ProhibitedUntil' => 2, 'CreatedAt' => 3, 'UpdatedAt' => 4, ),
        self::TYPE_CAMELNAME     => array('prohibitionId' => 0, 'userId' => 1, 'prohibitedUntil' => 2, 'createdAt' => 3, 'updatedAt' => 4, ),
        self::TYPE_COLNAME       => array(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID => 0, ProhibitionsUsersTableMap::COL_USERS_ID => 1, ProhibitionsUsersTableMap::COL_PROHIBITED_UNTIL => 2, ProhibitionsUsersTableMap::COL_CREATED_AT => 3, ProhibitionsUsersTableMap::COL_UPDATED_AT => 4, ),
        self::TYPE_FIELDNAME     => array('prohibitions_id' => 0, 'users_id' => 1, 'prohibited_until' => 2, 'created_at' => 3, 'updated_at' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('prohibitions_users');
        $this->setPhpName('ProhibitionsUsers');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Phlopsi\\AccessControl\\Propel\\ProhibitionsUsers');
        $this->setPackage('Phlopsi.AccessControl.Propel');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('prohibitions_id', 'ProhibitionId', 'INTEGER' , 'prohibitions', 'id', true, null, null);
        $this->addForeignPrimaryKey('users_id', 'UserId', 'INTEGER' , 'users', 'id', true, null, null);
        $this->addColumn('prohibited_until', 'ProhibitedUntil', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Prohibition', '\\Phlopsi\\AccessControl\\Propel\\Prohibition', RelationMap::MANY_TO_ONE, array('prohibitions_id' => 'id', ), null, null);
        $this->addRelation('User', '\\Phlopsi\\AccessControl\\Propel\\User', RelationMap::MANY_TO_ONE, array('users_id' => 'id', ), null, null);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Phlopsi\AccessControl\Propel\ProhibitionsUsers $obj A \Phlopsi\AccessControl\Propel\ProhibitionsUsers object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getProhibitionId(), (string) $obj->getUserId()));
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \Phlopsi\AccessControl\Propel\ProhibitionsUsers object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Phlopsi\AccessControl\Propel\ProhibitionsUsers) {
                $key = serialize(array((string) $value->getProhibitionId(), (string) $value->getUserId()));

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Phlopsi\AccessControl\Propel\ProhibitionsUsers object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('ProhibitionId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('ProhibitionId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)]));
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
            $pks = [];

        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('ProhibitionId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? ProhibitionsUsersTableMap::CLASS_DEFAULT : ProhibitionsUsersTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (ProhibitionsUsers object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ProhibitionsUsersTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ProhibitionsUsersTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ProhibitionsUsersTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ProhibitionsUsersTableMap::OM_CLASS;
            /** @var ProhibitionsUsers $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ProhibitionsUsersTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = ProhibitionsUsersTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ProhibitionsUsersTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ProhibitionsUsers $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ProhibitionsUsersTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID);
            $criteria->addSelectColumn(ProhibitionsUsersTableMap::COL_USERS_ID);
            $criteria->addSelectColumn(ProhibitionsUsersTableMap::COL_PROHIBITED_UNTIL);
            $criteria->addSelectColumn(ProhibitionsUsersTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(ProhibitionsUsersTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.prohibitions_id');
            $criteria->addSelectColumn($alias . '.users_id');
            $criteria->addSelectColumn($alias . '.prohibited_until');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(ProhibitionsUsersTableMap::DATABASE_NAME)->getTable(ProhibitionsUsersTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ProhibitionsUsersTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ProhibitionsUsersTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ProhibitionsUsersTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a ProhibitionsUsers or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ProhibitionsUsers object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionsUsersTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Phlopsi\AccessControl\Propel\ProhibitionsUsers) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ProhibitionsUsersTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(ProhibitionsUsersTableMap::COL_PROHIBITIONS_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(ProhibitionsUsersTableMap::COL_USERS_ID, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = ProhibitionsUsersQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ProhibitionsUsersTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ProhibitionsUsersTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the prohibitions_users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ProhibitionsUsersQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ProhibitionsUsers or Criteria object.
     *
     * @param mixed               $criteria Criteria or ProhibitionsUsers object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ProhibitionsUsersTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ProhibitionsUsers object
        }


        // Set the correct dbName
        $query = ProhibitionsUsersQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ProhibitionsUsersTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ProhibitionsUsersTableMap::buildTableMap();
