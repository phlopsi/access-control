<?php

namespace org\bitbucket\phlopsi\access_control\propel\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use org\bitbucket\phlopsi\access_control\propel\Sessions;
use org\bitbucket\phlopsi\access_control\propel\SessionsQuery;


/**
 * This class defines the structure of the 'sessions' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SessionsTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'org.bitbucket.phlopsi.access_control.propel.Map.SessionsTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'access_control';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'sessions';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\org\\bitbucket\\phlopsi\\access_control\\propel\\Sessions';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'org.bitbucket.phlopsi.access_control.propel.Sessions';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the SESSION_TYPES_ID field
     */
    const SESSION_TYPES_ID = 'sessions.SESSION_TYPES_ID';

    /**
     * the column name for the USERS_ID field
     */
    const USERS_ID = 'sessions.USERS_ID';

    /**
     * the column name for the KEY field
     */
    const KEY = 'sessions.KEY';

    /**
     * the column name for the SESSION_DURATION field
     */
    const SESSION_DURATION = 'sessions.SESSION_DURATION';

    /**
     * the column name for the SIMULATED_USERS_ID field
     */
    const SIMULATED_USERS_ID = 'sessions.SIMULATED_USERS_ID';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'sessions.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'sessions.UPDATED_AT';

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
        self::TYPE_PHPNAME       => array('sessionTypeId', 'userId', 'Key', 'SessionDuration', 'simulatedUserId', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_STUDLYPHPNAME => array('sessionTypeId', 'userId', 'key', 'sessionDuration', 'simulatedUserId', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(SessionsTableMap::SESSION_TYPES_ID, SessionsTableMap::USERS_ID, SessionsTableMap::KEY, SessionsTableMap::SESSION_DURATION, SessionsTableMap::SIMULATED_USERS_ID, SessionsTableMap::CREATED_AT, SessionsTableMap::UPDATED_AT, ),
        self::TYPE_RAW_COLNAME   => array('SESSION_TYPES_ID', 'USERS_ID', 'KEY', 'SESSION_DURATION', 'SIMULATED_USERS_ID', 'CREATED_AT', 'UPDATED_AT', ),
        self::TYPE_FIELDNAME     => array('session_types_id', 'users_id', 'key', 'session_duration', 'simulated_users_id', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('sessionTypeId' => 0, 'userId' => 1, 'Key' => 2, 'SessionDuration' => 3, 'simulatedUserId' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, ),
        self::TYPE_STUDLYPHPNAME => array('sessionTypeId' => 0, 'userId' => 1, 'key' => 2, 'sessionDuration' => 3, 'simulatedUserId' => 4, 'createdAt' => 5, 'updatedAt' => 6, ),
        self::TYPE_COLNAME       => array(SessionsTableMap::SESSION_TYPES_ID => 0, SessionsTableMap::USERS_ID => 1, SessionsTableMap::KEY => 2, SessionsTableMap::SESSION_DURATION => 3, SessionsTableMap::SIMULATED_USERS_ID => 4, SessionsTableMap::CREATED_AT => 5, SessionsTableMap::UPDATED_AT => 6, ),
        self::TYPE_RAW_COLNAME   => array('SESSION_TYPES_ID' => 0, 'USERS_ID' => 1, 'KEY' => 2, 'SESSION_DURATION' => 3, 'SIMULATED_USERS_ID' => 4, 'CREATED_AT' => 5, 'UPDATED_AT' => 6, ),
        self::TYPE_FIELDNAME     => array('session_types_id' => 0, 'users_id' => 1, 'key' => 2, 'session_duration' => 3, 'simulated_users_id' => 4, 'created_at' => 5, 'updated_at' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('sessions');
        $this->setPhpName('Sessions');
        $this->setClassName('\\org\\bitbucket\\phlopsi\\access_control\\propel\\Sessions');
        $this->setPackage('org.bitbucket.phlopsi.access_control.propel');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignKey('SESSION_TYPES_ID', 'sessionTypeId', 'INTEGER', 'session_types', 'ID', true, null, null);
        $this->addForeignKey('USERS_ID', 'userId', 'INTEGER', 'users', 'ID', true, null, null);
        $this->addPrimaryKey('KEY', 'Key', 'VARCHAR', true, 127, null);
        $this->addColumn('SESSION_DURATION', 'SessionDuration', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('SIMULATED_USERS_ID', 'simulatedUserId', 'INTEGER', 'users', 'ID', false, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SessionType', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\SessionType', RelationMap::MANY_TO_ONE, array('session_types_id' => 'id', ), null, null);
        $this->addRelation('User', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\User', RelationMap::MANY_TO_ONE, array('users_id' => 'id', 'simulated_users_id' => 'id', ), null, null);
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
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Key', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Key', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (string) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 2 + $offset
                            : self::translateFieldName('Key', TableMap::TYPE_PHPNAME, $indexType)
                        ];
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
        return $withPrefix ? SessionsTableMap::CLASS_DEFAULT : SessionsTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (Sessions object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SessionsTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SessionsTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SessionsTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SessionsTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SessionsTableMap::addInstanceToPool($obj, $key);
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
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = SessionsTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SessionsTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SessionsTableMap::addInstanceToPool($obj, $key);
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
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(SessionsTableMap::SESSION_TYPES_ID);
            $criteria->addSelectColumn(SessionsTableMap::USERS_ID);
            $criteria->addSelectColumn(SessionsTableMap::KEY);
            $criteria->addSelectColumn(SessionsTableMap::SESSION_DURATION);
            $criteria->addSelectColumn(SessionsTableMap::SIMULATED_USERS_ID);
            $criteria->addSelectColumn(SessionsTableMap::CREATED_AT);
            $criteria->addSelectColumn(SessionsTableMap::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.SESSION_TYPES_ID');
            $criteria->addSelectColumn($alias . '.USERS_ID');
            $criteria->addSelectColumn($alias . '.KEY');
            $criteria->addSelectColumn($alias . '.SESSION_DURATION');
            $criteria->addSelectColumn($alias . '.SIMULATED_USERS_ID');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(SessionsTableMap::DATABASE_NAME)->getTable(SessionsTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(SessionsTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(SessionsTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new SessionsTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a Sessions or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Sessions object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SessionsTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \org\bitbucket\phlopsi\access_control\propel\Sessions) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SessionsTableMap::DATABASE_NAME);
            $criteria->add(SessionsTableMap::KEY, (array) $values, Criteria::IN);
        }

        $query = SessionsQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { SessionsTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { SessionsTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the sessions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SessionsQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Sessions or Criteria object.
     *
     * @param mixed               $criteria Criteria or Sessions object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SessionsTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Sessions object
        }


        // Set the correct dbName
        $query = SessionsQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // SessionsTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SessionsTableMap::buildTableMap();
