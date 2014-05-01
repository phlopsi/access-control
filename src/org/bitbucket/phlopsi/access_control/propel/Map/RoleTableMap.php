<?php
namespace org\bitbucket\phlopsi\access_control\propel\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use org\bitbucket\phlopsi\access_control\propel\Role;
use org\bitbucket\phlopsi\access_control\propel\RoleQuery;

/**
 * This class defines the structure of the 'roles' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class RoleTableMap extends TableMap
{
    use InstancePoolTrait;
use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'org.bitbucket.phlopsi.access_control.propel.Map.RoleTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'access_control';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'roles';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\org\\bitbucket\\phlopsi\\access_control\\propel\\Role';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'org.bitbucket.phlopsi.access_control.propel.Role';

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
     * the column name for the EXTERNAL_ID field
     */
    const COL_EXTERNAL_ID = 'roles.EXTERNAL_ID';

    /**
     * the column name for the TREE_LEFT field
     */
    const COL_TREE_LEFT = 'roles.TREE_LEFT';

    /**
     * the column name for the TREE_RIGHT field
     */
    const COL_TREE_RIGHT = 'roles.TREE_RIGHT';

    /**
     * the column name for the TREE_LEVEL field
     */
    const COL_TREE_LEVEL = 'roles.TREE_LEVEL';

    /**
     * the column name for the ID field
     */
    const COL_ID = 'roles.ID';

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
    protected static $fieldNames = array(
        self::TYPE_PHPNAME => array('ExternalId', 'TreeLeft', 'TreeRight', 'TreeLevel', 'Id',),
        self::TYPE_STUDLYPHPNAME => array('externalId', 'treeLeft', 'treeRight', 'treeLevel', 'id',),
        self::TYPE_COLNAME => array(RoleTableMap::COL_EXTERNAL_ID, RoleTableMap::COL_TREE_LEFT, RoleTableMap::COL_TREE_RIGHT,
            RoleTableMap::COL_TREE_LEVEL, RoleTableMap::COL_ID,),
        self::TYPE_RAW_COLNAME => array('COL_EXTERNAL_ID', 'COL_TREE_LEFT', 'COL_TREE_RIGHT', 'COL_TREE_LEVEL', 'COL_ID',),
        self::TYPE_FIELDNAME => array('external_id', 'tree_left', 'tree_right', 'tree_level', 'id',),
        self::TYPE_NUM => array(0, 1, 2, 3, 4,)
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array(
        self::TYPE_PHPNAME => array('ExternalId' => 0, 'TreeLeft' => 1, 'TreeRight' => 2, 'TreeLevel' => 3, 'Id' => 4,),
        self::TYPE_STUDLYPHPNAME => array('externalId' => 0, 'treeLeft' => 1, 'treeRight' => 2, 'treeLevel' => 3, 'id' => 4,),
        self::TYPE_COLNAME => array(RoleTableMap::COL_EXTERNAL_ID => 0, RoleTableMap::COL_TREE_LEFT => 1, RoleTableMap::COL_TREE_RIGHT => 2,
            RoleTableMap::COL_TREE_LEVEL => 3, RoleTableMap::COL_ID => 4,),
        self::TYPE_RAW_COLNAME => array('COL_EXTERNAL_ID' => 0, 'COL_TREE_LEFT' => 1, 'COL_TREE_RIGHT' => 2, 'COL_TREE_LEVEL' => 3,
            'COL_ID' => 4,),
        self::TYPE_FIELDNAME => array('external_id' => 0, 'tree_left' => 1, 'tree_right' => 2, 'tree_level' => 3, 'id' => 4,),
        self::TYPE_NUM => array(0, 1, 2, 3, 4,)
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
        $this->setName('roles');
        $this->setPhpName('Role');
        $this->setClassName('\\org\\bitbucket\\phlopsi\\access_control\\propel\\Role');
        $this->setPackage('org.bitbucket.phlopsi.access_control.propel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addColumn('EXTERNAL_ID', 'ExternalId', 'LONGVARCHAR', true, null, null);
        $this->addColumn('TREE_LEFT', 'TreeLeft', 'INTEGER', false, null, null);
        $this->addColumn('TREE_RIGHT', 'TreeRight', 'INTEGER', false, null, null);
        $this->addColumn('TREE_LEVEL', 'TreeLevel', 'INTEGER', false, null, null);
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
    }

// initialize()
    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PermissionsRoles', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\PermissionsRoles',
            RelationMap::ONE_TO_MANY, array('id' => 'roles_id',), null, null, 'PermissionsRoless');
        $this->addRelation('ProhibitionsRoles', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\ProhibitionsRoles',
            RelationMap::ONE_TO_MANY, array('id' => 'roles_id',), null, null, 'ProhibitionsRoless');
        $this->addRelation('RolesSessionTypes', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\RolesSessionTypes',
            RelationMap::ONE_TO_MANY, array('id' => 'roles_id',), null, null, 'RolesSessionTypess');
        $this->addRelation('RolesUsers', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\RolesUsers',
            RelationMap::ONE_TO_MANY, array('id' => 'roles_id',), null, null, 'RolesUserss');
        $this->addRelation('Permission', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\Permission',
            RelationMap::MANY_TO_MANY, array(), null, null, 'Permissions');
        $this->addRelation('Prohibition', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\Prohibition',
            RelationMap::MANY_TO_MANY, array(), null, null, 'Prohibitions');
        $this->addRelation('SessionType', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\SessionType',
            RelationMap::MANY_TO_MANY, array(), null, null, 'SessionTypes');
        $this->addRelation('User', '\\org\\bitbucket\\phlopsi\\access_control\\propel\\User', RelationMap::MANY_TO_MANY,
            array(), null, null, 'Users');
    }

// buildRelations()
    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'nested_set' => array('left_column' => 'tree_left', 'right_column' => 'tree_right', 'level_column' => 'tree_level',
                'use_scope' => 'false', 'scope_column' => 'tree_scope', 'method_proxies' => 'false',),
            'auto_add_pk' => array('name' => 'id', 'autoIncrement' => 'true', 'type' => 'INTEGER',),
        );
    }

// getBehaviors()
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
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 4 + $offset : static::translateFieldName('Id',
                    TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 4 + $offset : static::translateFieldName('Id',
                    TableMap::TYPE_PHPNAME, $indexType)];
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
        return (int) $row[
            $indexType == TableMap::TYPE_NUM ? 4 + $offset : self::translateFieldName('Id', TableMap::TYPE_PHPNAME,
                    $indexType)
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
        return $withPrefix ? RoleTableMap::CLASS_DEFAULT : RoleTableMap::OM_CLASS;
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
     *                         rethrown wrapped into a PropelException.
     * @return array           (Role object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = RoleTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = RoleTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + RoleTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = RoleTableMap::OM_CLASS;
            /** @var Role $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            RoleTableMap::addInstanceToPool($obj, $key);
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
            $key = RoleTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = RoleTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Role $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                RoleTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(RoleTableMap::COL_EXTERNAL_ID);
            $criteria->addSelectColumn(RoleTableMap::COL_TREE_LEFT);
            $criteria->addSelectColumn(RoleTableMap::COL_TREE_RIGHT);
            $criteria->addSelectColumn(RoleTableMap::COL_TREE_LEVEL);
            $criteria->addSelectColumn(RoleTableMap::COL_ID);
        } else {
            $criteria->addSelectColumn($alias . '.EXTERNAL_ID');
            $criteria->addSelectColumn($alias . '.TREE_LEFT');
            $criteria->addSelectColumn($alias . '.TREE_RIGHT');
            $criteria->addSelectColumn($alias . '.TREE_LEVEL');
            $criteria->addSelectColumn($alias . '.ID');
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
        return Propel::getServiceContainer()->getDatabaseMap(RoleTableMap::DATABASE_NAME)->getTable(RoleTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(RoleTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(RoleTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new RoleTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Role or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Role object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \org\bitbucket\phlopsi\access_control\propel\Role) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(RoleTableMap::DATABASE_NAME);
            $criteria->add(RoleTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = RoleQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            RoleTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                RoleTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the roles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return RoleQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Role or Criteria object.
     *
     * @param mixed               $criteria Criteria or Role object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Role object
        }

        if ($criteria->containsKey(RoleTableMap::COL_ID) && $criteria->keyContainsValue(RoleTableMap::COL_ID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . RoleTableMap::COL_ID . ')');
        }


        // Set the correct dbName
        $query = RoleQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
                return $query->doInsert($con);
            });
    }

}

// RoleTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
RoleTableMap::buildTableMap();
