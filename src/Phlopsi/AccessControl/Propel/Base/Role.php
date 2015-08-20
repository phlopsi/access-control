<?php

namespace Phlopsi\AccessControl\Propel\Base;

use \Exception;
use \PDO;
use Phlopsi\AccessControl\Propel\Permission as ChildPermission;
use Phlopsi\AccessControl\Propel\PermissionQuery as ChildPermissionQuery;
use Phlopsi\AccessControl\Propel\PermissionsRoles as ChildPermissionsRoles;
use Phlopsi\AccessControl\Propel\PermissionsRolesQuery as ChildPermissionsRolesQuery;
use Phlopsi\AccessControl\Propel\Prohibition as ChildProhibition;
use Phlopsi\AccessControl\Propel\ProhibitionQuery as ChildProhibitionQuery;
use Phlopsi\AccessControl\Propel\ProhibitionsRoles as ChildProhibitionsRoles;
use Phlopsi\AccessControl\Propel\ProhibitionsRolesQuery as ChildProhibitionsRolesQuery;
use Phlopsi\AccessControl\Propel\Role as ChildRole;
use Phlopsi\AccessControl\Propel\RoleQuery as ChildRoleQuery;
use Phlopsi\AccessControl\Propel\RolesSessionTypes as ChildRolesSessionTypes;
use Phlopsi\AccessControl\Propel\RolesSessionTypesQuery as ChildRolesSessionTypesQuery;
use Phlopsi\AccessControl\Propel\RolesUsers as ChildRolesUsers;
use Phlopsi\AccessControl\Propel\RolesUsersQuery as ChildRolesUsersQuery;
use Phlopsi\AccessControl\Propel\SessionType as ChildSessionType;
use Phlopsi\AccessControl\Propel\SessionTypeQuery as ChildSessionTypeQuery;
use Phlopsi\AccessControl\Propel\User as ChildUser;
use Phlopsi\AccessControl\Propel\UserQuery as ChildUserQuery;
use Phlopsi\AccessControl\Propel\Map\RoleTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\ActiveRecord\NestedSetRecursiveIterator;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'roles' table.
 *
 *
 *
* @package    propel.generator.Phlopsi.AccessControl.Propel.Base
*/
abstract class Role implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Phlopsi\\AccessControl\\Propel\\Map\\RoleTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the external_id field.
     * @var        string
     */
    protected $external_id;

    /**
     * The value for the tree_left field.
     * @var        int
     */
    protected $tree_left;

    /**
     * The value for the tree_right field.
     * @var        int
     */
    protected $tree_right;

    /**
     * The value for the tree_level field.
     * @var        int
     */
    protected $tree_level;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * @var        ObjectCollection|ChildPermissionsRoles[] Collection to store aggregation of ChildPermissionsRoles objects.
     */
    protected $collPermissionsRoless;
    protected $collPermissionsRolessPartial;

    /**
     * @var        ObjectCollection|ChildProhibitionsRoles[] Collection to store aggregation of ChildProhibitionsRoles objects.
     */
    protected $collProhibitionsRoless;
    protected $collProhibitionsRolessPartial;

    /**
     * @var        ObjectCollection|ChildRolesSessionTypes[] Collection to store aggregation of ChildRolesSessionTypes objects.
     */
    protected $collRolesSessionTypess;
    protected $collRolesSessionTypessPartial;

    /**
     * @var        ObjectCollection|ChildRolesUsers[] Collection to store aggregation of ChildRolesUsers objects.
     */
    protected $collRolesUserss;
    protected $collRolesUserssPartial;

    /**
     * @var        ObjectCollection|ChildPermission[] Cross Collection to store aggregation of ChildPermission objects.
     */
    protected $collPermissions;

    /**
     * @var bool
     */
    protected $collPermissionsPartial;

    /**
     * @var        ObjectCollection|ChildProhibition[] Cross Collection to store aggregation of ChildProhibition objects.
     */
    protected $collProhibitions;

    /**
     * @var bool
     */
    protected $collProhibitionsPartial;

    /**
     * @var        ObjectCollection|ChildSessionType[] Cross Collection to store aggregation of ChildSessionType objects.
     */
    protected $collSessionTypes;

    /**
     * @var bool
     */
    protected $collSessionTypesPartial;

    /**
     * @var        ObjectCollection|ChildUser[] Cross Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;

    /**
     * @var bool
     */
    protected $collUsersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // nested_set behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $nestedSetQueries = array();

    /**
     * Internal cache for children nodes
     * @var        null|ObjectCollection
     */
    protected $collNestedSetChildren = null;

    /**
     * Internal cache for parent node
     * @var        null|ChildRole
     */
    protected $aNestedSetParent = null;

    /**
     * Left column for the set
     */
    const LEFT_COL = 'roles.tree_left';

    /**
     * Right column for the set
     */
    const RIGHT_COL = 'roles.tree_right';

    /**
     * Level column for the set
     */
    const LEVEL_COL = 'roles.tree_level';

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPermission[]
     */
    protected $permissionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildProhibition[]
     */
    protected $prohibitionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSessionType[]
     */
    protected $sessionTypesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPermissionsRoles[]
     */
    protected $permissionsRolessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildProhibitionsRoles[]
     */
    protected $prohibitionsRolessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRolesSessionTypes[]
     */
    protected $rolesSessionTypessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRolesUsers[]
     */
    protected $rolesUserssScheduledForDeletion = null;

    /**
     * Initializes internal state of Phlopsi\AccessControl\Propel\Base\Role object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Role</code> instance.  If
     * <code>obj</code> is an instance of <code>Role</code>, delegates to
     * <code>equals(Role)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Role The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [external_id] column value.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->external_id;
    }

    /**
     * Get the [tree_left] column value.
     *
     * @return int
     */
    public function getTreeLeft()
    {
        return $this->tree_left;
    }

    /**
     * Get the [tree_right] column value.
     *
     * @return int
     */
    public function getTreeRight()
    {
        return $this->tree_right;
    }

    /**
     * Get the [tree_level] column value.
     *
     * @return int
     */
    public function getTreeLevel()
    {
        return $this->tree_level;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of [external_id] column.
     *
     * @param  string $v new value
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function setExternalId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->external_id !== $v) {
            $this->external_id = $v;
            $this->modifiedColumns[RoleTableMap::COL_EXTERNAL_ID] = true;
        }

        return $this;
    } // setExternalId()

    /**
     * Set the value of [tree_left] column.
     *
     * @param  int $v new value
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function setTreeLeft($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tree_left !== $v) {
            $this->tree_left = $v;
            $this->modifiedColumns[RoleTableMap::COL_TREE_LEFT] = true;
        }

        return $this;
    } // setTreeLeft()

    /**
     * Set the value of [tree_right] column.
     *
     * @param  int $v new value
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function setTreeRight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tree_right !== $v) {
            $this->tree_right = $v;
            $this->modifiedColumns[RoleTableMap::COL_TREE_RIGHT] = true;
        }

        return $this;
    } // setTreeRight()

    /**
     * Set the value of [tree_level] column.
     *
     * @param  int $v new value
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function setTreeLevel($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tree_level !== $v) {
            $this->tree_level = $v;
            $this->modifiedColumns[RoleTableMap::COL_TREE_LEVEL] = true;
        }

        return $this;
    } // setTreeLevel()

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[RoleTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : RoleTableMap::translateFieldName('ExternalId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->external_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : RoleTableMap::translateFieldName('TreeLeft', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tree_left = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : RoleTableMap::translateFieldName('TreeRight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tree_right = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : RoleTableMap::translateFieldName('TreeLevel', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tree_level = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : RoleTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = RoleTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Phlopsi\\AccessControl\\Propel\\Role'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RoleTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildRoleQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPermissionsRoless = null;

            $this->collProhibitionsRoless = null;

            $this->collRolesSessionTypess = null;

            $this->collRolesUserss = null;

            $this->collPermissions = null;
            $this->collProhibitions = null;
            $this->collSessionTypes = null;
            $this->collUsers = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Role::setDeleted()
     * @see Role::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildRoleQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // nested_set behavior
            if ($this->isRoot()) {
                throw new PropelException('Deletion of a root node is disabled for nested sets. Use ChildRoleQuery::deleteTree() instead to delete an entire tree');
            }

            if ($this->isInTree()) {
                $this->deleteDescendants($con);
            }

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // nested_set behavior
                if ($this->isInTree()) {
                    // fill up the room that was used by the node
                    ChildRoleQuery::shiftRLValues(-2, $this->getRightValue() + 1, null, $con);
                }

                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            // nested_set behavior
            if ($this->isNew() && $this->isRoot()) {
                // check if no other root exist in, the tree
                $nbRoots = ChildRoleQuery::create()
                    ->addUsingAlias(ChildRole::LEFT_COL, 1, Criteria::EQUAL)
                    ->count($con);
                if ($nbRoots > 0) {
                        throw new PropelException('A root node already exists in this tree. To allow multiple root nodes, add the `use_scope` parameter in the nested_set behavior tag.');
                }
            }
            $this->processNestedSetQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                RoleTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->permissionsScheduledForDeletion !== null) {
                if (!$this->permissionsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->permissionsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Phlopsi\AccessControl\Propel\PermissionsRolesQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->permissionsScheduledForDeletion = null;
                }

            }

            if ($this->collPermissions) {
                foreach ($this->collPermissions as $permission) {
                    if (!$permission->isDeleted() && ($permission->isNew() || $permission->isModified())) {
                        $permission->save($con);
                    }
                }
            }


            if ($this->prohibitionsScheduledForDeletion !== null) {
                if (!$this->prohibitionsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->prohibitionsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Phlopsi\AccessControl\Propel\ProhibitionsRolesQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->prohibitionsScheduledForDeletion = null;
                }

            }

            if ($this->collProhibitions) {
                foreach ($this->collProhibitions as $prohibition) {
                    if (!$prohibition->isDeleted() && ($prohibition->isNew() || $prohibition->isModified())) {
                        $prohibition->save($con);
                    }
                }
            }


            if ($this->sessionTypesScheduledForDeletion !== null) {
                if (!$this->sessionTypesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->sessionTypesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Phlopsi\AccessControl\Propel\RolesSessionTypesQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->sessionTypesScheduledForDeletion = null;
                }

            }

            if ($this->collSessionTypes) {
                foreach ($this->collSessionTypes as $sessionType) {
                    if (!$sessionType->isDeleted() && ($sessionType->isNew() || $sessionType->isModified())) {
                        $sessionType->save($con);
                    }
                }
            }


            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->usersScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Phlopsi\AccessControl\Propel\RolesUsersQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->usersScheduledForDeletion = null;
                }

            }

            if ($this->collUsers) {
                foreach ($this->collUsers as $user) {
                    if (!$user->isDeleted() && ($user->isNew() || $user->isModified())) {
                        $user->save($con);
                    }
                }
            }


            if ($this->permissionsRolessScheduledForDeletion !== null) {
                if (!$this->permissionsRolessScheduledForDeletion->isEmpty()) {
                    \Phlopsi\AccessControl\Propel\PermissionsRolesQuery::create()
                        ->filterByPrimaryKeys($this->permissionsRolessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->permissionsRolessScheduledForDeletion = null;
                }
            }

            if ($this->collPermissionsRoless !== null) {
                foreach ($this->collPermissionsRoless as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->prohibitionsRolessScheduledForDeletion !== null) {
                if (!$this->prohibitionsRolessScheduledForDeletion->isEmpty()) {
                    \Phlopsi\AccessControl\Propel\ProhibitionsRolesQuery::create()
                        ->filterByPrimaryKeys($this->prohibitionsRolessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->prohibitionsRolessScheduledForDeletion = null;
                }
            }

            if ($this->collProhibitionsRoless !== null) {
                foreach ($this->collProhibitionsRoless as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->rolesSessionTypessScheduledForDeletion !== null) {
                if (!$this->rolesSessionTypessScheduledForDeletion->isEmpty()) {
                    \Phlopsi\AccessControl\Propel\RolesSessionTypesQuery::create()
                        ->filterByPrimaryKeys($this->rolesSessionTypessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->rolesSessionTypessScheduledForDeletion = null;
                }
            }

            if ($this->collRolesSessionTypess !== null) {
                foreach ($this->collRolesSessionTypess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->rolesUserssScheduledForDeletion !== null) {
                if (!$this->rolesUserssScheduledForDeletion->isEmpty()) {
                    \Phlopsi\AccessControl\Propel\RolesUsersQuery::create()
                        ->filterByPrimaryKeys($this->rolesUserssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->rolesUserssScheduledForDeletion = null;
                }
            }

            if ($this->collRolesUserss !== null) {
                foreach ($this->collRolesUserss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[RoleTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . RoleTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(RoleTableMap::COL_EXTERNAL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'external_id';
        }
        if ($this->isColumnModified(RoleTableMap::COL_TREE_LEFT)) {
            $modifiedColumns[':p' . $index++]  = 'tree_left';
        }
        if ($this->isColumnModified(RoleTableMap::COL_TREE_RIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'tree_right';
        }
        if ($this->isColumnModified(RoleTableMap::COL_TREE_LEVEL)) {
            $modifiedColumns[':p' . $index++]  = 'tree_level';
        }
        if ($this->isColumnModified(RoleTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }

        $sql = sprintf(
            'INSERT INTO roles (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'external_id':
                        $stmt->bindValue($identifier, $this->external_id, PDO::PARAM_STR);
                        break;
                    case 'tree_left':
                        $stmt->bindValue($identifier, $this->tree_left, PDO::PARAM_INT);
                        break;
                    case 'tree_right':
                        $stmt->bindValue($identifier, $this->tree_right, PDO::PARAM_INT);
                        break;
                    case 'tree_level':
                        $stmt->bindValue($identifier, $this->tree_level, PDO::PARAM_INT);
                        break;
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = RoleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getExternalId();
                break;
            case 1:
                return $this->getTreeLeft();
                break;
            case 2:
                return $this->getTreeRight();
                break;
            case 3:
                return $this->getTreeLevel();
                break;
            case 4:
                return $this->getId();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Role'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Role'][$this->hashCode()] = true;
        $keys = RoleTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getExternalId(),
            $keys[1] => $this->getTreeLeft(),
            $keys[2] => $this->getTreeRight(),
            $keys[3] => $this->getTreeLevel(),
            $keys[4] => $this->getId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPermissionsRoless) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'permissionsRoless';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'permissions_roless';
                        break;
                    default:
                        $key = 'PermissionsRoless';
                }

                $result[$key] = $this->collPermissionsRoless->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProhibitionsRoless) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'prohibitionsRoless';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'prohibitions_roless';
                        break;
                    default:
                        $key = 'ProhibitionsRoless';
                }

                $result[$key] = $this->collProhibitionsRoless->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRolesSessionTypess) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'rolesSessionTypess';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'roles_session_typess';
                        break;
                    default:
                        $key = 'RolesSessionTypess';
                }

                $result[$key] = $this->collRolesSessionTypess->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRolesUserss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'rolesUserss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'roles_userss';
                        break;
                    default:
                        $key = 'RolesUserss';
                }

                $result[$key] = $this->collRolesUserss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Phlopsi\AccessControl\Propel\Role
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = RoleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Phlopsi\AccessControl\Propel\Role
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setExternalId($value);
                break;
            case 1:
                $this->setTreeLeft($value);
                break;
            case 2:
                $this->setTreeRight($value);
                break;
            case 3:
                $this->setTreeLevel($value);
                break;
            case 4:
                $this->setId($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = RoleTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setExternalId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTreeLeft($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTreeRight($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTreeLevel($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setId($arr[$keys[4]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(RoleTableMap::DATABASE_NAME);

        if ($this->isColumnModified(RoleTableMap::COL_EXTERNAL_ID)) {
            $criteria->add(RoleTableMap::COL_EXTERNAL_ID, $this->external_id);
        }
        if ($this->isColumnModified(RoleTableMap::COL_TREE_LEFT)) {
            $criteria->add(RoleTableMap::COL_TREE_LEFT, $this->tree_left);
        }
        if ($this->isColumnModified(RoleTableMap::COL_TREE_RIGHT)) {
            $criteria->add(RoleTableMap::COL_TREE_RIGHT, $this->tree_right);
        }
        if ($this->isColumnModified(RoleTableMap::COL_TREE_LEVEL)) {
            $criteria->add(RoleTableMap::COL_TREE_LEVEL, $this->tree_level);
        }
        if ($this->isColumnModified(RoleTableMap::COL_ID)) {
            $criteria->add(RoleTableMap::COL_ID, $this->id);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildRoleQuery::create();
        $criteria->add(RoleTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Phlopsi\AccessControl\Propel\Role (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setExternalId($this->getExternalId());
        $copyObj->setTreeLeft($this->getTreeLeft());
        $copyObj->setTreeRight($this->getTreeRight());
        $copyObj->setTreeLevel($this->getTreeLevel());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPermissionsRoless() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPermissionsRoles($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProhibitionsRoless() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProhibitionsRoles($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRolesSessionTypess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRolesSessionTypes($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRolesUserss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRolesUsers($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Phlopsi\AccessControl\Propel\Role Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PermissionsRoles' == $relationName) {
            return $this->initPermissionsRoless();
        }
        if ('ProhibitionsRoles' == $relationName) {
            return $this->initProhibitionsRoless();
        }
        if ('RolesSessionTypes' == $relationName) {
            return $this->initRolesSessionTypess();
        }
        if ('RolesUsers' == $relationName) {
            return $this->initRolesUserss();
        }
    }

    /**
     * Clears out the collPermissionsRoless collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPermissionsRoless()
     */
    public function clearPermissionsRoless()
    {
        $this->collPermissionsRoless = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPermissionsRoless collection loaded partially.
     */
    public function resetPartialPermissionsRoless($v = true)
    {
        $this->collPermissionsRolessPartial = $v;
    }

    /**
     * Initializes the collPermissionsRoless collection.
     *
     * By default this just sets the collPermissionsRoless collection to an empty array (like clearcollPermissionsRoless());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPermissionsRoless($overrideExisting = true)
    {
        if (null !== $this->collPermissionsRoless && !$overrideExisting) {
            return;
        }
        $this->collPermissionsRoless = new ObjectCollection();
        $this->collPermissionsRoless->setModel('\Phlopsi\AccessControl\Propel\PermissionsRoles');
    }

    /**
     * Gets an array of ChildPermissionsRoles objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPermissionsRoles[] List of ChildPermissionsRoles objects
     * @throws PropelException
     */
    public function getPermissionsRoless(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionsRolessPartial && !$this->isNew();
        if (null === $this->collPermissionsRoless || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPermissionsRoless) {
                // return empty collection
                $this->initPermissionsRoless();
            } else {
                $collPermissionsRoless = ChildPermissionsRolesQuery::create(null, $criteria)
                    ->filterByRole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPermissionsRolessPartial && count($collPermissionsRoless)) {
                        $this->initPermissionsRoless(false);

                        foreach ($collPermissionsRoless as $obj) {
                            if (false == $this->collPermissionsRoless->contains($obj)) {
                                $this->collPermissionsRoless->append($obj);
                            }
                        }

                        $this->collPermissionsRolessPartial = true;
                    }

                    return $collPermissionsRoless;
                }

                if ($partial && $this->collPermissionsRoless) {
                    foreach ($this->collPermissionsRoless as $obj) {
                        if ($obj->isNew()) {
                            $collPermissionsRoless[] = $obj;
                        }
                    }
                }

                $this->collPermissionsRoless = $collPermissionsRoless;
                $this->collPermissionsRolessPartial = false;
            }
        }

        return $this->collPermissionsRoless;
    }

    /**
     * Sets a collection of ChildPermissionsRoles objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $permissionsRoless A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setPermissionsRoless(Collection $permissionsRoless, ConnectionInterface $con = null)
    {
        /** @var ChildPermissionsRoles[] $permissionsRolessToDelete */
        $permissionsRolessToDelete = $this->getPermissionsRoless(new Criteria(), $con)->diff($permissionsRoless);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->permissionsRolessScheduledForDeletion = clone $permissionsRolessToDelete;

        foreach ($permissionsRolessToDelete as $permissionsRolesRemoved) {
            $permissionsRolesRemoved->setRole(null);
        }

        $this->collPermissionsRoless = null;
        foreach ($permissionsRoless as $permissionsRoles) {
            $this->addPermissionsRoles($permissionsRoles);
        }

        $this->collPermissionsRoless = $permissionsRoless;
        $this->collPermissionsRolessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PermissionsRoles objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PermissionsRoles objects.
     * @throws PropelException
     */
    public function countPermissionsRoless(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionsRolessPartial && !$this->isNew();
        if (null === $this->collPermissionsRoless || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPermissionsRoless) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPermissionsRoless());
            }

            $query = ChildPermissionsRolesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRole($this)
                ->count($con);
        }

        return count($this->collPermissionsRoless);
    }

    /**
     * Method called to associate a ChildPermissionsRoles object to this object
     * through the ChildPermissionsRoles foreign key attribute.
     *
     * @param  ChildPermissionsRoles $l ChildPermissionsRoles
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function addPermissionsRoles(ChildPermissionsRoles $l)
    {
        if ($this->collPermissionsRoless === null) {
            $this->initPermissionsRoless();
            $this->collPermissionsRolessPartial = true;
        }

        if (!$this->collPermissionsRoless->contains($l)) {
            $this->doAddPermissionsRoles($l);
        }

        return $this;
    }

    /**
     * @param ChildPermissionsRoles $permissionsRoles The ChildPermissionsRoles object to add.
     */
    protected function doAddPermissionsRoles(ChildPermissionsRoles $permissionsRoles)
    {
        $this->collPermissionsRoless[]= $permissionsRoles;
        $permissionsRoles->setRole($this);
    }

    /**
     * @param  ChildPermissionsRoles $permissionsRoles The ChildPermissionsRoles object to remove.
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function removePermissionsRoles(ChildPermissionsRoles $permissionsRoles)
    {
        if ($this->getPermissionsRoless()->contains($permissionsRoles)) {
            $pos = $this->collPermissionsRoless->search($permissionsRoles);
            $this->collPermissionsRoless->remove($pos);
            if (null === $this->permissionsRolessScheduledForDeletion) {
                $this->permissionsRolessScheduledForDeletion = clone $this->collPermissionsRoless;
                $this->permissionsRolessScheduledForDeletion->clear();
            }
            $this->permissionsRolessScheduledForDeletion[]= clone $permissionsRoles;
            $permissionsRoles->setRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related PermissionsRoless from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPermissionsRoles[] List of ChildPermissionsRoles objects
     */
    public function getPermissionsRolessJoinPermission(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPermissionsRolesQuery::create(null, $criteria);
        $query->joinWith('Permission', $joinBehavior);

        return $this->getPermissionsRoless($query, $con);
    }

    /**
     * Clears out the collProhibitionsRoless collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addProhibitionsRoless()
     */
    public function clearProhibitionsRoless()
    {
        $this->collProhibitionsRoless = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collProhibitionsRoless collection loaded partially.
     */
    public function resetPartialProhibitionsRoless($v = true)
    {
        $this->collProhibitionsRolessPartial = $v;
    }

    /**
     * Initializes the collProhibitionsRoless collection.
     *
     * By default this just sets the collProhibitionsRoless collection to an empty array (like clearcollProhibitionsRoless());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProhibitionsRoless($overrideExisting = true)
    {
        if (null !== $this->collProhibitionsRoless && !$overrideExisting) {
            return;
        }
        $this->collProhibitionsRoless = new ObjectCollection();
        $this->collProhibitionsRoless->setModel('\Phlopsi\AccessControl\Propel\ProhibitionsRoles');
    }

    /**
     * Gets an array of ChildProhibitionsRoles objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildProhibitionsRoles[] List of ChildProhibitionsRoles objects
     * @throws PropelException
     */
    public function getProhibitionsRoless(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collProhibitionsRolessPartial && !$this->isNew();
        if (null === $this->collProhibitionsRoless || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProhibitionsRoless) {
                // return empty collection
                $this->initProhibitionsRoless();
            } else {
                $collProhibitionsRoless = ChildProhibitionsRolesQuery::create(null, $criteria)
                    ->filterByRole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collProhibitionsRolessPartial && count($collProhibitionsRoless)) {
                        $this->initProhibitionsRoless(false);

                        foreach ($collProhibitionsRoless as $obj) {
                            if (false == $this->collProhibitionsRoless->contains($obj)) {
                                $this->collProhibitionsRoless->append($obj);
                            }
                        }

                        $this->collProhibitionsRolessPartial = true;
                    }

                    return $collProhibitionsRoless;
                }

                if ($partial && $this->collProhibitionsRoless) {
                    foreach ($this->collProhibitionsRoless as $obj) {
                        if ($obj->isNew()) {
                            $collProhibitionsRoless[] = $obj;
                        }
                    }
                }

                $this->collProhibitionsRoless = $collProhibitionsRoless;
                $this->collProhibitionsRolessPartial = false;
            }
        }

        return $this->collProhibitionsRoless;
    }

    /**
     * Sets a collection of ChildProhibitionsRoles objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $prohibitionsRoless A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setProhibitionsRoless(Collection $prohibitionsRoless, ConnectionInterface $con = null)
    {
        /** @var ChildProhibitionsRoles[] $prohibitionsRolessToDelete */
        $prohibitionsRolessToDelete = $this->getProhibitionsRoless(new Criteria(), $con)->diff($prohibitionsRoless);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->prohibitionsRolessScheduledForDeletion = clone $prohibitionsRolessToDelete;

        foreach ($prohibitionsRolessToDelete as $prohibitionsRolesRemoved) {
            $prohibitionsRolesRemoved->setRole(null);
        }

        $this->collProhibitionsRoless = null;
        foreach ($prohibitionsRoless as $prohibitionsRoles) {
            $this->addProhibitionsRoles($prohibitionsRoles);
        }

        $this->collProhibitionsRoless = $prohibitionsRoless;
        $this->collProhibitionsRolessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProhibitionsRoles objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ProhibitionsRoles objects.
     * @throws PropelException
     */
    public function countProhibitionsRoless(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collProhibitionsRolessPartial && !$this->isNew();
        if (null === $this->collProhibitionsRoless || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProhibitionsRoless) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProhibitionsRoless());
            }

            $query = ChildProhibitionsRolesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRole($this)
                ->count($con);
        }

        return count($this->collProhibitionsRoless);
    }

    /**
     * Method called to associate a ChildProhibitionsRoles object to this object
     * through the ChildProhibitionsRoles foreign key attribute.
     *
     * @param  ChildProhibitionsRoles $l ChildProhibitionsRoles
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function addProhibitionsRoles(ChildProhibitionsRoles $l)
    {
        if ($this->collProhibitionsRoless === null) {
            $this->initProhibitionsRoless();
            $this->collProhibitionsRolessPartial = true;
        }

        if (!$this->collProhibitionsRoless->contains($l)) {
            $this->doAddProhibitionsRoles($l);
        }

        return $this;
    }

    /**
     * @param ChildProhibitionsRoles $prohibitionsRoles The ChildProhibitionsRoles object to add.
     */
    protected function doAddProhibitionsRoles(ChildProhibitionsRoles $prohibitionsRoles)
    {
        $this->collProhibitionsRoless[]= $prohibitionsRoles;
        $prohibitionsRoles->setRole($this);
    }

    /**
     * @param  ChildProhibitionsRoles $prohibitionsRoles The ChildProhibitionsRoles object to remove.
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function removeProhibitionsRoles(ChildProhibitionsRoles $prohibitionsRoles)
    {
        if ($this->getProhibitionsRoless()->contains($prohibitionsRoles)) {
            $pos = $this->collProhibitionsRoless->search($prohibitionsRoles);
            $this->collProhibitionsRoless->remove($pos);
            if (null === $this->prohibitionsRolessScheduledForDeletion) {
                $this->prohibitionsRolessScheduledForDeletion = clone $this->collProhibitionsRoless;
                $this->prohibitionsRolessScheduledForDeletion->clear();
            }
            $this->prohibitionsRolessScheduledForDeletion[]= clone $prohibitionsRoles;
            $prohibitionsRoles->setRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related ProhibitionsRoless from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildProhibitionsRoles[] List of ChildProhibitionsRoles objects
     */
    public function getProhibitionsRolessJoinProhibition(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildProhibitionsRolesQuery::create(null, $criteria);
        $query->joinWith('Prohibition', $joinBehavior);

        return $this->getProhibitionsRoless($query, $con);
    }

    /**
     * Clears out the collRolesSessionTypess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRolesSessionTypess()
     */
    public function clearRolesSessionTypess()
    {
        $this->collRolesSessionTypess = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRolesSessionTypess collection loaded partially.
     */
    public function resetPartialRolesSessionTypess($v = true)
    {
        $this->collRolesSessionTypessPartial = $v;
    }

    /**
     * Initializes the collRolesSessionTypess collection.
     *
     * By default this just sets the collRolesSessionTypess collection to an empty array (like clearcollRolesSessionTypess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRolesSessionTypess($overrideExisting = true)
    {
        if (null !== $this->collRolesSessionTypess && !$overrideExisting) {
            return;
        }
        $this->collRolesSessionTypess = new ObjectCollection();
        $this->collRolesSessionTypess->setModel('\Phlopsi\AccessControl\Propel\RolesSessionTypes');
    }

    /**
     * Gets an array of ChildRolesSessionTypes objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRolesSessionTypes[] List of ChildRolesSessionTypes objects
     * @throws PropelException
     */
    public function getRolesSessionTypess(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collRolesSessionTypessPartial && !$this->isNew();
        if (null === $this->collRolesSessionTypess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRolesSessionTypess) {
                // return empty collection
                $this->initRolesSessionTypess();
            } else {
                $collRolesSessionTypess = ChildRolesSessionTypesQuery::create(null, $criteria)
                    ->filterByRole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRolesSessionTypessPartial && count($collRolesSessionTypess)) {
                        $this->initRolesSessionTypess(false);

                        foreach ($collRolesSessionTypess as $obj) {
                            if (false == $this->collRolesSessionTypess->contains($obj)) {
                                $this->collRolesSessionTypess->append($obj);
                            }
                        }

                        $this->collRolesSessionTypessPartial = true;
                    }

                    return $collRolesSessionTypess;
                }

                if ($partial && $this->collRolesSessionTypess) {
                    foreach ($this->collRolesSessionTypess as $obj) {
                        if ($obj->isNew()) {
                            $collRolesSessionTypess[] = $obj;
                        }
                    }
                }

                $this->collRolesSessionTypess = $collRolesSessionTypess;
                $this->collRolesSessionTypessPartial = false;
            }
        }

        return $this->collRolesSessionTypess;
    }

    /**
     * Sets a collection of ChildRolesSessionTypes objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $rolesSessionTypess A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setRolesSessionTypess(Collection $rolesSessionTypess, ConnectionInterface $con = null)
    {
        /** @var ChildRolesSessionTypes[] $rolesSessionTypessToDelete */
        $rolesSessionTypessToDelete = $this->getRolesSessionTypess(new Criteria(), $con)->diff($rolesSessionTypess);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->rolesSessionTypessScheduledForDeletion = clone $rolesSessionTypessToDelete;

        foreach ($rolesSessionTypessToDelete as $rolesSessionTypesRemoved) {
            $rolesSessionTypesRemoved->setRole(null);
        }

        $this->collRolesSessionTypess = null;
        foreach ($rolesSessionTypess as $rolesSessionTypes) {
            $this->addRolesSessionTypes($rolesSessionTypes);
        }

        $this->collRolesSessionTypess = $rolesSessionTypess;
        $this->collRolesSessionTypessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related RolesSessionTypes objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related RolesSessionTypes objects.
     * @throws PropelException
     */
    public function countRolesSessionTypess(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collRolesSessionTypessPartial && !$this->isNew();
        if (null === $this->collRolesSessionTypess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRolesSessionTypess) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRolesSessionTypess());
            }

            $query = ChildRolesSessionTypesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRole($this)
                ->count($con);
        }

        return count($this->collRolesSessionTypess);
    }

    /**
     * Method called to associate a ChildRolesSessionTypes object to this object
     * through the ChildRolesSessionTypes foreign key attribute.
     *
     * @param  ChildRolesSessionTypes $l ChildRolesSessionTypes
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function addRolesSessionTypes(ChildRolesSessionTypes $l)
    {
        if ($this->collRolesSessionTypess === null) {
            $this->initRolesSessionTypess();
            $this->collRolesSessionTypessPartial = true;
        }

        if (!$this->collRolesSessionTypess->contains($l)) {
            $this->doAddRolesSessionTypes($l);
        }

        return $this;
    }

    /**
     * @param ChildRolesSessionTypes $rolesSessionTypes The ChildRolesSessionTypes object to add.
     */
    protected function doAddRolesSessionTypes(ChildRolesSessionTypes $rolesSessionTypes)
    {
        $this->collRolesSessionTypess[]= $rolesSessionTypes;
        $rolesSessionTypes->setRole($this);
    }

    /**
     * @param  ChildRolesSessionTypes $rolesSessionTypes The ChildRolesSessionTypes object to remove.
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function removeRolesSessionTypes(ChildRolesSessionTypes $rolesSessionTypes)
    {
        if ($this->getRolesSessionTypess()->contains($rolesSessionTypes)) {
            $pos = $this->collRolesSessionTypess->search($rolesSessionTypes);
            $this->collRolesSessionTypess->remove($pos);
            if (null === $this->rolesSessionTypessScheduledForDeletion) {
                $this->rolesSessionTypessScheduledForDeletion = clone $this->collRolesSessionTypess;
                $this->rolesSessionTypessScheduledForDeletion->clear();
            }
            $this->rolesSessionTypessScheduledForDeletion[]= clone $rolesSessionTypes;
            $rolesSessionTypes->setRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related RolesSessionTypess from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRolesSessionTypes[] List of ChildRolesSessionTypes objects
     */
    public function getRolesSessionTypessJoinSessionType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRolesSessionTypesQuery::create(null, $criteria);
        $query->joinWith('SessionType', $joinBehavior);

        return $this->getRolesSessionTypess($query, $con);
    }

    /**
     * Clears out the collRolesUserss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRolesUserss()
     */
    public function clearRolesUserss()
    {
        $this->collRolesUserss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRolesUserss collection loaded partially.
     */
    public function resetPartialRolesUserss($v = true)
    {
        $this->collRolesUserssPartial = $v;
    }

    /**
     * Initializes the collRolesUserss collection.
     *
     * By default this just sets the collRolesUserss collection to an empty array (like clearcollRolesUserss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRolesUserss($overrideExisting = true)
    {
        if (null !== $this->collRolesUserss && !$overrideExisting) {
            return;
        }
        $this->collRolesUserss = new ObjectCollection();
        $this->collRolesUserss->setModel('\Phlopsi\AccessControl\Propel\RolesUsers');
    }

    /**
     * Gets an array of ChildRolesUsers objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRolesUsers[] List of ChildRolesUsers objects
     * @throws PropelException
     */
    public function getRolesUserss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collRolesUserssPartial && !$this->isNew();
        if (null === $this->collRolesUserss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRolesUserss) {
                // return empty collection
                $this->initRolesUserss();
            } else {
                $collRolesUserss = ChildRolesUsersQuery::create(null, $criteria)
                    ->filterByRole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRolesUserssPartial && count($collRolesUserss)) {
                        $this->initRolesUserss(false);

                        foreach ($collRolesUserss as $obj) {
                            if (false == $this->collRolesUserss->contains($obj)) {
                                $this->collRolesUserss->append($obj);
                            }
                        }

                        $this->collRolesUserssPartial = true;
                    }

                    return $collRolesUserss;
                }

                if ($partial && $this->collRolesUserss) {
                    foreach ($this->collRolesUserss as $obj) {
                        if ($obj->isNew()) {
                            $collRolesUserss[] = $obj;
                        }
                    }
                }

                $this->collRolesUserss = $collRolesUserss;
                $this->collRolesUserssPartial = false;
            }
        }

        return $this->collRolesUserss;
    }

    /**
     * Sets a collection of ChildRolesUsers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $rolesUserss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setRolesUserss(Collection $rolesUserss, ConnectionInterface $con = null)
    {
        /** @var ChildRolesUsers[] $rolesUserssToDelete */
        $rolesUserssToDelete = $this->getRolesUserss(new Criteria(), $con)->diff($rolesUserss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->rolesUserssScheduledForDeletion = clone $rolesUserssToDelete;

        foreach ($rolesUserssToDelete as $rolesUsersRemoved) {
            $rolesUsersRemoved->setRole(null);
        }

        $this->collRolesUserss = null;
        foreach ($rolesUserss as $rolesUsers) {
            $this->addRolesUsers($rolesUsers);
        }

        $this->collRolesUserss = $rolesUserss;
        $this->collRolesUserssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related RolesUsers objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related RolesUsers objects.
     * @throws PropelException
     */
    public function countRolesUserss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collRolesUserssPartial && !$this->isNew();
        if (null === $this->collRolesUserss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRolesUserss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRolesUserss());
            }

            $query = ChildRolesUsersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRole($this)
                ->count($con);
        }

        return count($this->collRolesUserss);
    }

    /**
     * Method called to associate a ChildRolesUsers object to this object
     * through the ChildRolesUsers foreign key attribute.
     *
     * @param  ChildRolesUsers $l ChildRolesUsers
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function addRolesUsers(ChildRolesUsers $l)
    {
        if ($this->collRolesUserss === null) {
            $this->initRolesUserss();
            $this->collRolesUserssPartial = true;
        }

        if (!$this->collRolesUserss->contains($l)) {
            $this->doAddRolesUsers($l);
        }

        return $this;
    }

    /**
     * @param ChildRolesUsers $rolesUsers The ChildRolesUsers object to add.
     */
    protected function doAddRolesUsers(ChildRolesUsers $rolesUsers)
    {
        $this->collRolesUserss[]= $rolesUsers;
        $rolesUsers->setRole($this);
    }

    /**
     * @param  ChildRolesUsers $rolesUsers The ChildRolesUsers object to remove.
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function removeRolesUsers(ChildRolesUsers $rolesUsers)
    {
        if ($this->getRolesUserss()->contains($rolesUsers)) {
            $pos = $this->collRolesUserss->search($rolesUsers);
            $this->collRolesUserss->remove($pos);
            if (null === $this->rolesUserssScheduledForDeletion) {
                $this->rolesUserssScheduledForDeletion = clone $this->collRolesUserss;
                $this->rolesUserssScheduledForDeletion->clear();
            }
            $this->rolesUserssScheduledForDeletion[]= clone $rolesUsers;
            $rolesUsers->setRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related RolesUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRolesUsers[] List of ChildRolesUsers objects
     */
    public function getRolesUserssJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRolesUsersQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getRolesUserss($query, $con);
    }

    /**
     * Clears out the collPermissions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPermissions()
     */
    public function clearPermissions()
    {
        $this->collPermissions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collPermissions crossRef collection.
     *
     * By default this just sets the collPermissions collection to an empty collection (like clearPermissions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initPermissions()
    {
        $this->collPermissions = new ObjectCollection();
        $this->collPermissionsPartial = true;

        $this->collPermissions->setModel('\Phlopsi\AccessControl\Propel\Permission');
    }

    /**
     * Checks if the collPermissions collection is loaded.
     *
     * @return bool
     */
    public function isPermissionsLoaded()
    {
        return null !== $this->collPermissions;
    }

    /**
     * Gets a collection of ChildPermission objects related by a many-to-many relationship
     * to the current object by way of the permissions_roles cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildPermission[] List of ChildPermission objects
     */
    public function getPermissions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionsPartial && !$this->isNew();
        if (null === $this->collPermissions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPermissions) {
                    $this->initPermissions();
                }
            } else {

                $query = ChildPermissionQuery::create(null, $criteria)
                    ->filterByRole($this);
                $collPermissions = $query->find($con);
                if (null !== $criteria) {
                    return $collPermissions;
                }

                if ($partial && $this->collPermissions) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collPermissions as $obj) {
                        if (!$collPermissions->contains($obj)) {
                            $collPermissions[] = $obj;
                        }
                    }
                }

                $this->collPermissions = $collPermissions;
                $this->collPermissionsPartial = false;
            }
        }

        return $this->collPermissions;
    }

    /**
     * Sets a collection of Permission objects related by a many-to-many relationship
     * to the current object by way of the permissions_roles cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $permissions A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setPermissions(Collection $permissions, ConnectionInterface $con = null)
    {
        $this->clearPermissions();
        $currentPermissions = $this->getPermissions();

        $permissionsScheduledForDeletion = $currentPermissions->diff($permissions);

        foreach ($permissionsScheduledForDeletion as $toDelete) {
            $this->removePermission($toDelete);
        }

        foreach ($permissions as $permission) {
            if (!$currentPermissions->contains($permission)) {
                $this->doAddPermission($permission);
            }
        }

        $this->collPermissionsPartial = false;
        $this->collPermissions = $permissions;

        return $this;
    }

    /**
     * Gets the number of Permission objects related by a many-to-many relationship
     * to the current object by way of the permissions_roles cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Permission objects
     */
    public function countPermissions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionsPartial && !$this->isNew();
        if (null === $this->collPermissions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPermissions) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getPermissions());
                }

                $query = ChildPermissionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByRole($this)
                    ->count($con);
            }
        } else {
            return count($this->collPermissions);
        }
    }

    /**
     * Associate a ChildPermission to this object
     * through the permissions_roles cross reference table.
     *
     * @param ChildPermission $permission
     * @return ChildRole The current object (for fluent API support)
     */
    public function addPermission(ChildPermission $permission)
    {
        if ($this->collPermissions === null) {
            $this->initPermissions();
        }

        if (!$this->getPermissions()->contains($permission)) {
            // only add it if the **same** object is not already associated
            $this->collPermissions->push($permission);
            $this->doAddPermission($permission);
        }

        return $this;
    }

    /**
     *
     * @param ChildPermission $permission
     */
    protected function doAddPermission(ChildPermission $permission)
    {
        $permissionsRoles = new ChildPermissionsRoles();

        $permissionsRoles->setPermission($permission);

        $permissionsRoles->setRole($this);

        $this->addPermissionsRoles($permissionsRoles);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$permission->isRolesLoaded()) {
            $permission->initRoles();
            $permission->getRoles()->push($this);
        } elseif (!$permission->getRoles()->contains($this)) {
            $permission->getRoles()->push($this);
        }

    }

    /**
     * Remove permission of this object
     * through the permissions_roles cross reference table.
     *
     * @param ChildPermission $permission
     * @return ChildRole The current object (for fluent API support)
     */
    public function removePermission(ChildPermission $permission)
    {
        if ($this->getPermissions()->contains($permission)) { $permissionsRoles = new ChildPermissionsRoles();

            $permissionsRoles->setPermission($permission);
            if ($permission->isRolesLoaded()) {
                //remove the back reference if available
                $permission->getRoles()->removeObject($this);
            }

            $permissionsRoles->setRole($this);
            $this->removePermissionsRoles(clone $permissionsRoles);
            $permissionsRoles->clear();

            $this->collPermissions->remove($this->collPermissions->search($permission));

            if (null === $this->permissionsScheduledForDeletion) {
                $this->permissionsScheduledForDeletion = clone $this->collPermissions;
                $this->permissionsScheduledForDeletion->clear();
            }

            $this->permissionsScheduledForDeletion->push($permission);
        }


        return $this;
    }

    /**
     * Clears out the collProhibitions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addProhibitions()
     */
    public function clearProhibitions()
    {
        $this->collProhibitions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collProhibitions crossRef collection.
     *
     * By default this just sets the collProhibitions collection to an empty collection (like clearProhibitions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initProhibitions()
    {
        $this->collProhibitions = new ObjectCollection();
        $this->collProhibitionsPartial = true;

        $this->collProhibitions->setModel('\Phlopsi\AccessControl\Propel\Prohibition');
    }

    /**
     * Checks if the collProhibitions collection is loaded.
     *
     * @return bool
     */
    public function isProhibitionsLoaded()
    {
        return null !== $this->collProhibitions;
    }

    /**
     * Gets a collection of ChildProhibition objects related by a many-to-many relationship
     * to the current object by way of the prohibitions_roles cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildProhibition[] List of ChildProhibition objects
     */
    public function getProhibitions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collProhibitionsPartial && !$this->isNew();
        if (null === $this->collProhibitions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collProhibitions) {
                    $this->initProhibitions();
                }
            } else {

                $query = ChildProhibitionQuery::create(null, $criteria)
                    ->filterByRole($this);
                $collProhibitions = $query->find($con);
                if (null !== $criteria) {
                    return $collProhibitions;
                }

                if ($partial && $this->collProhibitions) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collProhibitions as $obj) {
                        if (!$collProhibitions->contains($obj)) {
                            $collProhibitions[] = $obj;
                        }
                    }
                }

                $this->collProhibitions = $collProhibitions;
                $this->collProhibitionsPartial = false;
            }
        }

        return $this->collProhibitions;
    }

    /**
     * Sets a collection of Prohibition objects related by a many-to-many relationship
     * to the current object by way of the prohibitions_roles cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $prohibitions A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setProhibitions(Collection $prohibitions, ConnectionInterface $con = null)
    {
        $this->clearProhibitions();
        $currentProhibitions = $this->getProhibitions();

        $prohibitionsScheduledForDeletion = $currentProhibitions->diff($prohibitions);

        foreach ($prohibitionsScheduledForDeletion as $toDelete) {
            $this->removeProhibition($toDelete);
        }

        foreach ($prohibitions as $prohibition) {
            if (!$currentProhibitions->contains($prohibition)) {
                $this->doAddProhibition($prohibition);
            }
        }

        $this->collProhibitionsPartial = false;
        $this->collProhibitions = $prohibitions;

        return $this;
    }

    /**
     * Gets the number of Prohibition objects related by a many-to-many relationship
     * to the current object by way of the prohibitions_roles cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Prohibition objects
     */
    public function countProhibitions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collProhibitionsPartial && !$this->isNew();
        if (null === $this->collProhibitions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProhibitions) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getProhibitions());
                }

                $query = ChildProhibitionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByRole($this)
                    ->count($con);
            }
        } else {
            return count($this->collProhibitions);
        }
    }

    /**
     * Associate a ChildProhibition to this object
     * through the prohibitions_roles cross reference table.
     *
     * @param ChildProhibition $prohibition
     * @return ChildRole The current object (for fluent API support)
     */
    public function addProhibition(ChildProhibition $prohibition)
    {
        if ($this->collProhibitions === null) {
            $this->initProhibitions();
        }

        if (!$this->getProhibitions()->contains($prohibition)) {
            // only add it if the **same** object is not already associated
            $this->collProhibitions->push($prohibition);
            $this->doAddProhibition($prohibition);
        }

        return $this;
    }

    /**
     *
     * @param ChildProhibition $prohibition
     */
    protected function doAddProhibition(ChildProhibition $prohibition)
    {
        $prohibitionsRoles = new ChildProhibitionsRoles();

        $prohibitionsRoles->setProhibition($prohibition);

        $prohibitionsRoles->setRole($this);

        $this->addProhibitionsRoles($prohibitionsRoles);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$prohibition->isRolesLoaded()) {
            $prohibition->initRoles();
            $prohibition->getRoles()->push($this);
        } elseif (!$prohibition->getRoles()->contains($this)) {
            $prohibition->getRoles()->push($this);
        }

    }

    /**
     * Remove prohibition of this object
     * through the prohibitions_roles cross reference table.
     *
     * @param ChildProhibition $prohibition
     * @return ChildRole The current object (for fluent API support)
     */
    public function removeProhibition(ChildProhibition $prohibition)
    {
        if ($this->getProhibitions()->contains($prohibition)) { $prohibitionsRoles = new ChildProhibitionsRoles();

            $prohibitionsRoles->setProhibition($prohibition);
            if ($prohibition->isRolesLoaded()) {
                //remove the back reference if available
                $prohibition->getRoles()->removeObject($this);
            }

            $prohibitionsRoles->setRole($this);
            $this->removeProhibitionsRoles(clone $prohibitionsRoles);
            $prohibitionsRoles->clear();

            $this->collProhibitions->remove($this->collProhibitions->search($prohibition));

            if (null === $this->prohibitionsScheduledForDeletion) {
                $this->prohibitionsScheduledForDeletion = clone $this->collProhibitions;
                $this->prohibitionsScheduledForDeletion->clear();
            }

            $this->prohibitionsScheduledForDeletion->push($prohibition);
        }


        return $this;
    }

    /**
     * Clears out the collSessionTypes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSessionTypes()
     */
    public function clearSessionTypes()
    {
        $this->collSessionTypes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collSessionTypes crossRef collection.
     *
     * By default this just sets the collSessionTypes collection to an empty collection (like clearSessionTypes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initSessionTypes()
    {
        $this->collSessionTypes = new ObjectCollection();
        $this->collSessionTypesPartial = true;

        $this->collSessionTypes->setModel('\Phlopsi\AccessControl\Propel\SessionType');
    }

    /**
     * Checks if the collSessionTypes collection is loaded.
     *
     * @return bool
     */
    public function isSessionTypesLoaded()
    {
        return null !== $this->collSessionTypes;
    }

    /**
     * Gets a collection of ChildSessionType objects related by a many-to-many relationship
     * to the current object by way of the roles_session_types cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildSessionType[] List of ChildSessionType objects
     */
    public function getSessionTypes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSessionTypesPartial && !$this->isNew();
        if (null === $this->collSessionTypes || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSessionTypes) {
                    $this->initSessionTypes();
                }
            } else {

                $query = ChildSessionTypeQuery::create(null, $criteria)
                    ->filterByRole($this);
                $collSessionTypes = $query->find($con);
                if (null !== $criteria) {
                    return $collSessionTypes;
                }

                if ($partial && $this->collSessionTypes) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collSessionTypes as $obj) {
                        if (!$collSessionTypes->contains($obj)) {
                            $collSessionTypes[] = $obj;
                        }
                    }
                }

                $this->collSessionTypes = $collSessionTypes;
                $this->collSessionTypesPartial = false;
            }
        }

        return $this->collSessionTypes;
    }

    /**
     * Sets a collection of SessionType objects related by a many-to-many relationship
     * to the current object by way of the roles_session_types cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $sessionTypes A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setSessionTypes(Collection $sessionTypes, ConnectionInterface $con = null)
    {
        $this->clearSessionTypes();
        $currentSessionTypes = $this->getSessionTypes();

        $sessionTypesScheduledForDeletion = $currentSessionTypes->diff($sessionTypes);

        foreach ($sessionTypesScheduledForDeletion as $toDelete) {
            $this->removeSessionType($toDelete);
        }

        foreach ($sessionTypes as $sessionType) {
            if (!$currentSessionTypes->contains($sessionType)) {
                $this->doAddSessionType($sessionType);
            }
        }

        $this->collSessionTypesPartial = false;
        $this->collSessionTypes = $sessionTypes;

        return $this;
    }

    /**
     * Gets the number of SessionType objects related by a many-to-many relationship
     * to the current object by way of the roles_session_types cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related SessionType objects
     */
    public function countSessionTypes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSessionTypesPartial && !$this->isNew();
        if (null === $this->collSessionTypes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSessionTypes) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getSessionTypes());
                }

                $query = ChildSessionTypeQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByRole($this)
                    ->count($con);
            }
        } else {
            return count($this->collSessionTypes);
        }
    }

    /**
     * Associate a ChildSessionType to this object
     * through the roles_session_types cross reference table.
     *
     * @param ChildSessionType $sessionType
     * @return ChildRole The current object (for fluent API support)
     */
    public function addSessionType(ChildSessionType $sessionType)
    {
        if ($this->collSessionTypes === null) {
            $this->initSessionTypes();
        }

        if (!$this->getSessionTypes()->contains($sessionType)) {
            // only add it if the **same** object is not already associated
            $this->collSessionTypes->push($sessionType);
            $this->doAddSessionType($sessionType);
        }

        return $this;
    }

    /**
     *
     * @param ChildSessionType $sessionType
     */
    protected function doAddSessionType(ChildSessionType $sessionType)
    {
        $rolesSessionTypes = new ChildRolesSessionTypes();

        $rolesSessionTypes->setSessionType($sessionType);

        $rolesSessionTypes->setRole($this);

        $this->addRolesSessionTypes($rolesSessionTypes);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$sessionType->isRolesLoaded()) {
            $sessionType->initRoles();
            $sessionType->getRoles()->push($this);
        } elseif (!$sessionType->getRoles()->contains($this)) {
            $sessionType->getRoles()->push($this);
        }

    }

    /**
     * Remove sessionType of this object
     * through the roles_session_types cross reference table.
     *
     * @param ChildSessionType $sessionType
     * @return ChildRole The current object (for fluent API support)
     */
    public function removeSessionType(ChildSessionType $sessionType)
    {
        if ($this->getSessionTypes()->contains($sessionType)) { $rolesSessionTypes = new ChildRolesSessionTypes();

            $rolesSessionTypes->setSessionType($sessionType);
            if ($sessionType->isRolesLoaded()) {
                //remove the back reference if available
                $sessionType->getRoles()->removeObject($this);
            }

            $rolesSessionTypes->setRole($this);
            $this->removeRolesSessionTypes(clone $rolesSessionTypes);
            $rolesSessionTypes->clear();

            $this->collSessionTypes->remove($this->collSessionTypes->search($sessionType));

            if (null === $this->sessionTypesScheduledForDeletion) {
                $this->sessionTypesScheduledForDeletion = clone $this->collSessionTypes;
                $this->sessionTypesScheduledForDeletion->clear();
            }

            $this->sessionTypesScheduledForDeletion->push($sessionType);
        }


        return $this;
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collUsers crossRef collection.
     *
     * By default this just sets the collUsers collection to an empty collection (like clearUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUsers()
    {
        $this->collUsers = new ObjectCollection();
        $this->collUsersPartial = true;

        $this->collUsers->setModel('\Phlopsi\AccessControl\Propel\User');
    }

    /**
     * Checks if the collUsers collection is loaded.
     *
     * @return bool
     */
    public function isUsersLoaded()
    {
        return null !== $this->collUsers;
    }

    /**
     * Gets a collection of ChildUser objects related by a many-to-many relationship
     * to the current object by way of the roles_users cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collUsers) {
                    $this->initUsers();
                }
            } else {

                $query = ChildUserQuery::create(null, $criteria)
                    ->filterByRole($this);
                $collUsers = $query->find($con);
                if (null !== $criteria) {
                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collUsers as $obj) {
                        if (!$collUsers->contains($obj)) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the roles_users cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $users A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        $this->clearUsers();
        $currentUsers = $this->getUsers();

        $usersScheduledForDeletion = $currentUsers->diff($users);

        foreach ($usersScheduledForDeletion as $toDelete) {
            $this->removeUser($toDelete);
        }

        foreach ($users as $user) {
            if (!$currentUsers->contains($user)) {
                $this->doAddUser($user);
            }
        }

        $this->collUsersPartial = false;
        $this->collUsers = $users;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the roles_users cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getUsers());
                }

                $query = ChildUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByRole($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a ChildUser to this object
     * through the roles_users cross reference table.
     *
     * @param ChildUser $user
     * @return ChildRole The current object (for fluent API support)
     */
    public function addUser(ChildUser $user)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
        }

        if (!$this->getUsers()->contains($user)) {
            // only add it if the **same** object is not already associated
            $this->collUsers->push($user);
            $this->doAddUser($user);
        }

        return $this;
    }

    /**
     *
     * @param ChildUser $user
     */
    protected function doAddUser(ChildUser $user)
    {
        $rolesUsers = new ChildRolesUsers();

        $rolesUsers->setUser($user);

        $rolesUsers->setRole($this);

        $this->addRolesUsers($rolesUsers);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$user->isRolesLoaded()) {
            $user->initRoles();
            $user->getRoles()->push($this);
        } elseif (!$user->getRoles()->contains($this)) {
            $user->getRoles()->push($this);
        }

    }

    /**
     * Remove user of this object
     * through the roles_users cross reference table.
     *
     * @param ChildUser $user
     * @return ChildRole The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) { $rolesUsers = new ChildRolesUsers();

            $rolesUsers->setUser($user);
            if ($user->isRolesLoaded()) {
                //remove the back reference if available
                $user->getRoles()->removeObject($this);
            }

            $rolesUsers->setRole($this);
            $this->removeRolesUsers(clone $rolesUsers);
            $rolesUsers->clear();

            $this->collUsers->remove($this->collUsers->search($user));

            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }

            $this->usersScheduledForDeletion->push($user);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->external_id = null;
        $this->tree_left = null;
        $this->tree_right = null;
        $this->tree_level = null;
        $this->id = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collPermissionsRoless) {
                foreach ($this->collPermissionsRoless as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProhibitionsRoless) {
                foreach ($this->collProhibitionsRoless as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRolesSessionTypess) {
                foreach ($this->collRolesSessionTypess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRolesUserss) {
                foreach ($this->collRolesUserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPermissions) {
                foreach ($this->collPermissions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProhibitions) {
                foreach ($this->collProhibitions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSessionTypes) {
                foreach ($this->collSessionTypes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        // nested_set behavior
        $this->collNestedSetChildren = null;
        $this->aNestedSetParent = null;
        $this->collPermissionsRoless = null;
        $this->collProhibitionsRoless = null;
        $this->collRolesSessionTypess = null;
        $this->collRolesUserss = null;
        $this->collPermissions = null;
        $this->collProhibitions = null;
        $this->collSessionTypes = null;
        $this->collUsers = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(RoleTableMap::DEFAULT_STRING_FORMAT);
    }

    // nested_set behavior

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processNestedSetQueries($con)
    {
        foreach ($this->nestedSetQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->nestedSetQueries = array();
    }

    /**
     * Proxy getter method for the left value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set left value
     */
    public function getLeftValue()
    {
        return $this->tree_left;
    }

    /**
     * Proxy getter method for the right value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set right value
     */
    public function getRightValue()
    {
        return $this->tree_right;
    }

    /**
     * Proxy getter method for the level value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set level value
     */
    public function getLevel()
    {
        return $this->tree_level;
    }

    /**
     * Proxy setter method for the left value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param  int $v The nested set left value
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setLeftValue($v)
    {
        return $this->setTreeLeft($v);
    }

    /**
     * Proxy setter method for the right value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set right value
     * @return     $this|ChildRole The current object (for fluent API support)
     */
    public function setRightValue($v)
    {
        return $this->setTreeRight($v);
    }

    /**
     * Proxy setter method for the level value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set level value
     * @return     $this|ChildRole The current object (for fluent API support)
     */
    public function setLevel($v)
    {
        return $this->setTreeLevel($v);
    }

    /**
     * Creates the supplied node as the root node.
     *
     * @return     $this|ChildRole The current object (for fluent API support)
     * @throws     PropelException
     */
    public function makeRoot()
    {
        if ($this->getLeftValue() || $this->getRightValue()) {
            throw new PropelException('Cannot turn an existing node into a root node.');
        }

        $this->setLeftValue(1);
        $this->setRightValue(2);
        $this->setLevel(0);

        return $this;
    }

    /**
     * Tests if object is a node, i.e. if it is inserted in the tree
     *
     * @return     bool
     */
    public function isInTree()
    {
        return $this->getLeftValue() > 0 && $this->getRightValue() > $this->getLeftValue();
    }

    /**
     * Tests if node is a root
     *
     * @return     bool
     */
    public function isRoot()
    {
        return $this->isInTree() && $this->getLeftValue() == 1;
    }

    /**
     * Tests if node is a leaf
     *
     * @return     bool
     */
    public function isLeaf()
    {
        return $this->isInTree() &&  ($this->getRightValue() - $this->getLeftValue()) == 1;
    }

    /**
     * Tests if node is a descendant of another node
     *
     * @param      ChildRole $node Propel node object
     * @return     bool
     */
    public function isDescendantOf($parent)
    {

        return $this->isInTree() && $this->getLeftValue() > $parent->getLeftValue() && $this->getRightValue() < $parent->getRightValue();
    }

    /**
     * Tests if node is a ancestor of another node
     *
     * @param      ChildRole $node Propel node object
     * @return     bool
     */
    public function isAncestorOf($child)
    {
        return $child->isDescendantOf($this);
    }

    /**
     * Tests if object has an ancestor
     *
     * @return boolean
     */
    public function hasParent()
    {
        return $this->getLevel() > 0;
    }

    /**
     * Sets the cache for parent node of the current object.
     * Warning: this does not move the current object in the tree.
     * Use moveTofirstChildOf() or moveToLastChildOf() for that purpose
     *
     * @param      ChildRole $parent
     * @return     $this|ChildRole The current object, for fluid interface
     */
    public function setParent($parent = null)
    {
        $this->aNestedSetParent = $parent;

        return $this;
    }

    /**
     * Gets parent node for the current object if it exists
     * The result is cached so further calls to the same method don't issue any queries
     *
     * @param  ConnectionInterface $con Connection to use.
     * @return self|boolean Propel object if exists else false
     */
    public function getParent(ConnectionInterface $con = null)
    {
        if (null === $this->aNestedSetParent && $this->hasParent()) {
            $this->aNestedSetParent = ChildRoleQuery::create()
                ->ancestorsOf($this)
                ->orderByLevel(true)
                ->findOne($con);
        }

        return $this->aNestedSetParent;
    }

    /**
     * Determines if the node has previous sibling
     *
     * @param      ConnectionInterface $con Connection to use.
     * @return     bool
     */
    public function hasPrevSibling(ConnectionInterface $con = null)
    {
        if (!ChildRoleQuery::isValid($this)) {
            return false;
        }

        return ChildRoleQuery::create()
            ->filterByTreeRight($this->getLeftValue() - 1)
            ->count($con) > 0;
    }

    /**
     * Gets previous sibling for the given node if it exists
     *
     * @param      ConnectionInterface $con Connection to use.
     * @return     mixed         Propel object if exists else false
     */
    public function getPrevSibling(ConnectionInterface $con = null)
    {
        return ChildRoleQuery::create()
            ->filterByTreeRight($this->getLeftValue() - 1)
            ->findOne($con);
    }

    /**
     * Determines if the node has next sibling
     *
     * @param      ConnectionInterface $con Connection to use.
     * @return     bool
     */
    public function hasNextSibling(ConnectionInterface $con = null)
    {
        if (!ChildRoleQuery::isValid($this)) {
            return false;
        }

        return ChildRoleQuery::create()
            ->filterByTreeLeft($this->getRightValue() + 1)
            ->count($con) > 0;
    }

    /**
     * Gets next sibling for the given node if it exists
     *
     * @param      ConnectionInterface $con Connection to use.
     * @return     mixed         Propel object if exists else false
     */
    public function getNextSibling(ConnectionInterface $con = null)
    {
        return ChildRoleQuery::create()
            ->filterByTreeLeft($this->getRightValue() + 1)
            ->findOne($con);
    }

    /**
     * Clears out the $collNestedSetChildren collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return     void
     */
    public function clearNestedSetChildren()
    {
        $this->collNestedSetChildren = null;
    }

    /**
     * Initializes the $collNestedSetChildren collection.
     *
     * @return     void
     */
    public function initNestedSetChildren()
    {
        $this->collNestedSetChildren = new ObjectCollection();
        $this->collNestedSetChildren->setModel('\Phlopsi\AccessControl\Propel\Role');
    }

    /**
     * Adds an element to the internal $collNestedSetChildren collection.
     * Beware that this doesn't insert a node in the tree.
     * This method is only used to facilitate children hydration.
     *
     * @param      ChildRole $role
     *
     * @return     void
     */
    public function addNestedSetChild($role)
    {
        if (null === $this->collNestedSetChildren) {
            $this->initNestedSetChildren();
        }
        if (!in_array($role, $this->collNestedSetChildren->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->collNestedSetChildren[]= $role;
            $role->setParent($this);
        }
    }

    /**
     * Tests if node has children
     *
     * @return     bool
     */
    public function hasChildren()
    {
        return ($this->getRightValue() - $this->getLeftValue()) > 1;
    }

    /**
     * Gets the children of the given node
     *
     * @param      Criteria  $criteria Criteria to filter results.
     * @param      ConnectionInterface $con Connection to use.
     * @return     array     List of ChildRole objects
     */
    public function getChildren($criteria = null, ConnectionInterface $con = null)
    {
        if (null === $this->collNestedSetChildren || null !== $criteria) {
            if ($this->isLeaf() || ($this->isNew() && null === $this->collNestedSetChildren)) {
                // return empty collection
                $this->initNestedSetChildren();
            } else {
                $collNestedSetChildren = ChildRoleQuery::create(null, $criteria)
                  ->childrenOf($this)
                  ->orderByBranch()
                    ->find($con);
                if (null !== $criteria) {
                    return $collNestedSetChildren;
                }
                $this->collNestedSetChildren = $collNestedSetChildren;
            }
        }

        return $this->collNestedSetChildren;
    }

    /**
     * Gets number of children for the given node
     *
     * @param      Criteria  $criteria Criteria to filter results.
     * @param      ConnectionInterface $con Connection to use.
     * @return     int       Number of children
     */
    public function countChildren($criteria = null, ConnectionInterface $con = null)
    {
        if (null === $this->collNestedSetChildren || null !== $criteria) {
            if ($this->isLeaf() || ($this->isNew() && null === $this->collNestedSetChildren)) {
                return 0;
            } else {
                return ChildRoleQuery::create(null, $criteria)
                    ->childrenOf($this)
                    ->count($con);
            }
        } else {
            return count($this->collNestedSetChildren);
        }
    }

    /**
     * Gets the first child of the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      ConnectionInterface $con Connection to use.
     * @return     array         List of ChildRole objects
     */
    public function getFirstChild($query = null, ConnectionInterface $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return ChildRoleQuery::create(null, $query)
                ->childrenOf($this)
                ->orderByBranch()
                ->findOne($con);
        }
    }

    /**
     * Gets the last child of the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      ConnectionInterface $con Connection to use.
     * @return     array         List of ChildRole objects
     */
    public function getLastChild($query = null, ConnectionInterface $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return ChildRoleQuery::create(null, $query)
                ->childrenOf($this)
                ->orderByBranch(true)
                ->findOne($con);
        }
    }

    /**
     * Gets the siblings of the given node
     *
     * @param boolean             $includeNode Whether to include the current node or not
     * @param Criteria            $query Criteria to filter results.
     * @param ConnectionInterface $con Connection to use.
     *
     * @return array List of ChildRole objects
     */
    public function getSiblings($includeNode = false, $query = null, ConnectionInterface $con = null)
    {
        if ($this->isRoot()) {
            return array();
        } else {
             $query = ChildRoleQuery::create(null, $query)
                    ->childrenOf($this->getParent($con))
                    ->orderByBranch();
            if (!$includeNode) {
                $query->prune($this);
            }

            return $query->find($con);
        }
    }

    /**
     * Gets descendants for the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      ConnectionInterface $con Connection to use.
     * @return     array         List of ChildRole objects
     */
    public function getDescendants($query = null, ConnectionInterface $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return ChildRoleQuery::create(null, $query)
                ->descendantsOf($this)
                ->orderByBranch()
                ->find($con);
        }
    }

    /**
     * Gets number of descendants for the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      ConnectionInterface $con Connection to use.
     * @return     int         Number of descendants
     */
    public function countDescendants($query = null, ConnectionInterface $con = null)
    {
        if ($this->isLeaf()) {
            // save one query
            return 0;
        } else {
            return ChildRoleQuery::create(null, $query)
                ->descendantsOf($this)
                ->count($con);
        }
    }

    /**
     * Gets descendants for the given node, plus the current node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      ConnectionInterface $con Connection to use.
     * @return     array         List of ChildRole objects
     */
    public function getBranch($query = null, ConnectionInterface $con = null)
    {
        return ChildRoleQuery::create(null, $query)
            ->branchOf($this)
            ->orderByBranch()
            ->find($con);
    }

    /**
     * Gets ancestors for the given node, starting with the root node
     * Use it for breadcrumb paths for instance
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      ConnectionInterface $con Connection to use.
     * @return     array         List of ChildRole objects
     */
    public function getAncestors($query = null, ConnectionInterface $con = null)
    {
        if ($this->isRoot()) {
            // save one query
            return array();
        } else {
            return ChildRoleQuery::create(null, $query)
                ->ancestorsOf($this)
                ->orderByBranch()
                ->find($con);
        }
    }

    /**
     * Inserts the given $child node as first child of current
     * The modifications in the current object and the tree
     * are not persisted until the child object is saved.
     *
     * @param      ChildRole $child    Propel object for child node
     *
     * @return     $this|ChildRole The current Propel object
     */
    public function addChild(ChildRole $child)
    {
        if ($this->isNew()) {
            throw new PropelException('A ChildRole object must not be new to accept children.');
        }
        $child->insertAsFirstChildOf($this);

        return $this;
    }

    /**
     * Inserts the current node as first child of given $parent node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      ChildRole $parent    Propel object for parent node
     *
     * @return     $this|ChildRole The current Propel object
     */
    public function insertAsFirstChildOf($parent)
    {
        if ($this->isInTree()) {
            throw new PropelException('A ChildRole object must not already be in the tree to be inserted. Use the moveToFirstChildOf() instead.');
        }
        $left = $parent->getLeftValue() + 1;
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($parent->getLevel() + 1);
        // update the children collection of the parent
        $parent->addNestedSetChild($this);

        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\Phlopsi\AccessControl\Propel\RoleQuery', 'makeRoomForLeaf'),
            'arguments' => array($left, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as last child of given $parent node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param  ChildRole $parent Propel object for parent node
     * @return $this|ChildRole The current Propel object
     */
    public function insertAsLastChildOf($parent)
    {
        if ($this->isInTree()) {
           throw new PropelException(
                'A ChildRole object must not already be in the tree to be inserted. Use the moveToLastChildOf() instead.'
            );
        }

        $left = $parent->getRightValue();
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($parent->getLevel() + 1);

        // update the children collection of the parent
        $parent->addNestedSetChild($this);

        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\Phlopsi\AccessControl\Propel\RoleQuery', 'makeRoomForLeaf'),
            'arguments' => array($left, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as prev sibling given $sibling node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      ChildRole $sibling    Propel object for parent node
     *
     * @return     $this|ChildRole The current Propel object
     */
    public function insertAsPrevSiblingOf($sibling)
    {
        if ($this->isInTree()) {
            throw new PropelException('A ChildRole object must not already be in the tree to be inserted. Use the moveToPrevSiblingOf() instead.');
        }
        $left = $sibling->getLeftValue();
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($sibling->getLevel());
        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\Phlopsi\AccessControl\Propel\RoleQuery', 'makeRoomForLeaf'),
            'arguments' => array($left, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as next sibling given $sibling node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      ChildRole $sibling    Propel object for parent node
     *
     * @return     $this|ChildRole The current Propel object
     */
    public function insertAsNextSiblingOf($sibling)
    {
        if ($this->isInTree()) {
            throw new PropelException('A ChildRole object must not already be in the tree to be inserted. Use the moveToNextSiblingOf() instead.');
        }
        $left = $sibling->getRightValue() + 1;
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($sibling->getLevel());
        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\Phlopsi\AccessControl\Propel\RoleQuery', 'makeRoomForLeaf'),
            'arguments' => array($left, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Moves current node and its subtree to be the first child of $parent
     * The modifications in the current object and the tree are immediate
     *
     * @param      ChildRole $parent    Propel object for parent node
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     $this|ChildRole The current Propel object
     */
    public function moveToFirstChildOf($parent, ConnectionInterface $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A ChildRole object must be already in the tree to be moved. Use the insertAsFirstChildOf() instead.');
        }
        if ($parent->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as child of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($parent->getLeftValue() + 1, $parent->getLevel() - $this->getLevel() + 1, $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the last child of $parent
     * The modifications in the current object and the tree are immediate
     *
     * @param      ChildRole $parent    Propel object for parent node
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     $this|ChildRole The current Propel object
     */
    public function moveToLastChildOf($parent, ConnectionInterface $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A ChildRole object must be already in the tree to be moved. Use the insertAsLastChildOf() instead.');
        }
        if ($parent->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as child of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($parent->getRightValue(), $parent->getLevel() - $this->getLevel() + 1, $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the previous sibling of $sibling
     * The modifications in the current object and the tree are immediate
     *
     * @param      ChildRole $sibling    Propel object for sibling node
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     $this|ChildRole The current Propel object
     */
    public function moveToPrevSiblingOf($sibling, ConnectionInterface $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A ChildRole object must be already in the tree to be moved. Use the insertAsPrevSiblingOf() instead.');
        }
        if ($sibling->isRoot()) {
            throw new PropelException('Cannot move to previous sibling of a root node.');
        }
        if ($sibling->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as sibling of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($sibling->getLeftValue(), $sibling->getLevel() - $this->getLevel(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the next sibling of $sibling
     * The modifications in the current object and the tree are immediate
     *
     * @param      ChildRole $sibling    Propel object for sibling node
     * @param      ConnectionInterface $con    Connection to use.
     *
     * @return     $this|ChildRole The current Propel object
     */
    public function moveToNextSiblingOf($sibling, ConnectionInterface $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A ChildRole object must be already in the tree to be moved. Use the insertAsNextSiblingOf() instead.');
        }
        if ($sibling->isRoot()) {
            throw new PropelException('Cannot move to next sibling of a root node.');
        }
        if ($sibling->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as sibling of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($sibling->getRightValue() + 1, $sibling->getLevel() - $this->getLevel(), $con);

        return $this;
    }

    /**
     * Move current node and its children to location $destLeft and updates rest of tree
     *
     * @param      int    $destLeft Destination left value
     * @param      int    $levelDelta Delta to add to the levels
     * @param      ConnectionInterface $con        Connection to use.
     */
    protected function moveSubtreeTo($destLeft, $levelDelta, PropelPDO $con = null)
    {
        $left  = $this->getLeftValue();
        $right = $this->getRightValue();


        $treeSize = $right - $left +1;

        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RoleTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con, $treeSize, $destLeft, $left, $right, $levelDelta) {
            $preventDefault = false;

            // make room next to the target for the subtree
            ChildRoleQuery::shiftRLValues($treeSize, $destLeft, null, $con);



            if (!$preventDefault) {


                if ($left >= $destLeft) { // src was shifted too?
                    $left += $treeSize;
                    $right += $treeSize;
                }

                if ($levelDelta) {
                    // update the levels of the subtree
                    ChildRoleQuery::shiftLevel($levelDelta, $left, $right, $con);
                }

                // move the subtree to the target
                ChildRoleQuery::shiftRLValues($destLeft - $left, $left, $right, $con);
            }

            // remove the empty room at the previous location of the subtree
            ChildRoleQuery::shiftRLValues(-$treeSize, $right + 1, null, $con);

            // update all loaded nodes
            ChildRoleQuery::updateLoadedNodes(null, $con);
        });
    }

    /**
     * Deletes all descendants for the given node
     * Instance pooling is wiped out by this command,
     * so existing ChildRole instances are probably invalid (except for the current one)
     *
     * @param      ConnectionInterface $con Connection to use.
     *
     * @return     int         number of deleted nodes
     */
    public function deleteDescendants(ConnectionInterface $con = null)
    {
        if ($this->isLeaf()) {
            // save one query
            return;
        }
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection(RoleTableMap::DATABASE_NAME);
        }
        $left = $this->getLeftValue();
        $right = $this->getRightValue();

        return $con->transaction(function () use ($con, $left, $right) {
            // delete descendant nodes (will empty the instance pool)
            $ret = ChildRoleQuery::create()
                ->descendantsOf($this)
                ->delete($con);

            // fill up the room that was used by descendants
            ChildRoleQuery::shiftRLValues($left - $right + 1, $right, null, $con);

            // fix the right value for the current node, which is now a leaf
            $this->setRightValue($left + 1);

            return $ret;
        });
    }

    /**
     * Returns a pre-order iterator for this node and its children.
     *
     * @return RecursiveIterator
     */
    public function getIterator()
    {
        return new NestedSetRecursiveIterator($this);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
