<?php

namespace Phlopsi\AccessControl\Propel\Base;

use \Exception;
use \PDO;
use Phlopsi\AccessControl\Propel\Permission as ChildPermission;
use Phlopsi\AccessControl\Propel\PermissionQuery as ChildPermissionQuery;
use Phlopsi\AccessControl\Propel\PermissionToRole as ChildPermissionToRole;
use Phlopsi\AccessControl\Propel\PermissionToRoleQuery as ChildPermissionToRoleQuery;
use Phlopsi\AccessControl\Propel\Role as ChildRole;
use Phlopsi\AccessControl\Propel\RoleQuery as ChildRoleQuery;
use Phlopsi\AccessControl\Propel\RoleToSessionType as ChildRoleToSessionType;
use Phlopsi\AccessControl\Propel\RoleToSessionTypeQuery as ChildRoleToSessionTypeQuery;
use Phlopsi\AccessControl\Propel\RoleToUser as ChildRoleToUser;
use Phlopsi\AccessControl\Propel\RoleToUserQuery as ChildRoleToUserQuery;
use Phlopsi\AccessControl\Propel\SessionType as ChildSessionType;
use Phlopsi\AccessControl\Propel\SessionTypeQuery as ChildSessionTypeQuery;
use Phlopsi\AccessControl\Propel\User as ChildUser;
use Phlopsi\AccessControl\Propel\UserQuery as ChildUserQuery;
use Phlopsi\AccessControl\Propel\Map\RoleTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
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
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * @var        ObjectCollection|ChildPermissionToRole[] Collection to store aggregation of ChildPermissionToRole objects.
     */
    protected $collPermissionToRoles;
    protected $collPermissionToRolesPartial;

    /**
     * @var        ObjectCollection|ChildRoleToSessionType[] Collection to store aggregation of ChildRoleToSessionType objects.
     */
    protected $collRoleToSessionTypes;
    protected $collRoleToSessionTypesPartial;

    /**
     * @var        ObjectCollection|ChildRoleToUser[] Collection to store aggregation of ChildRoleToUser objects.
     */
    protected $collRoleToUsers;
    protected $collRoleToUsersPartial;

    /**
     * @var        ObjectCollection|ChildPermission[] Cross Collection to store aggregation of ChildPermission objects.
     */
    protected $collPermissions;

    /**
     * @var bool
     */
    protected $collPermissionsPartial;

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

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPermission[]
     */
    protected $permissionsScheduledForDeletion = null;

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
     * @var ObjectCollection|ChildPermissionToRole[]
     */
    protected $permissionToRolesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRoleToSessionType[]
     */
    protected $roleToSessionTypesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRoleToUser[]
     */
    protected $roleToUsersScheduledForDeletion = null;

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
     * @param string $v new value
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
     * Set the value of [id] column.
     *
     * @param int $v new value
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : RoleTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = RoleTableMap::NUM_HYDRATE_COLUMNS.

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

        if ($deep) {
            $this->collPermissionToRoles = null;

            $this->collRoleToSessionTypes = null;

            $this->collRoleToUsers = null;

            $this->collPermissions = null;
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
            $deleteQuery->delete($con);
            $this->setDeleted(true);
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
            $affectedRows = $this->doSave($con);
            RoleTableMap::addInstanceToPool($this);

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
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
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

                    \Phlopsi\AccessControl\Propel\PermissionToRoleQuery::create()
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


            if ($this->sessionTypesScheduledForDeletion !== null) {
                if (!$this->sessionTypesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->sessionTypesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Phlopsi\AccessControl\Propel\RoleToSessionTypeQuery::create()
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

                    \Phlopsi\AccessControl\Propel\RoleToUserQuery::create()
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


            if ($this->permissionToRolesScheduledForDeletion !== null) {
                if (!$this->permissionToRolesScheduledForDeletion->isEmpty()) {
                    \Phlopsi\AccessControl\Propel\PermissionToRoleQuery::create()
                        ->filterByPrimaryKeys($this->permissionToRolesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->permissionToRolesScheduledForDeletion = null;
                }
            }

            if ($this->collPermissionToRoles !== null) {
                foreach ($this->collPermissionToRoles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->roleToSessionTypesScheduledForDeletion !== null) {
                if (!$this->roleToSessionTypesScheduledForDeletion->isEmpty()) {
                    \Phlopsi\AccessControl\Propel\RoleToSessionTypeQuery::create()
                        ->filterByPrimaryKeys($this->roleToSessionTypesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->roleToSessionTypesScheduledForDeletion = null;
                }
            }

            if ($this->collRoleToSessionTypes !== null) {
                foreach ($this->collRoleToSessionTypes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->roleToUsersScheduledForDeletion !== null) {
                if (!$this->roleToUsersScheduledForDeletion->isEmpty()) {
                    \Phlopsi\AccessControl\Propel\RoleToUserQuery::create()
                        ->filterByPrimaryKeys($this->roleToUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->roleToUsersScheduledForDeletion = null;
                }
            }

            if ($this->collRoleToUsers !== null) {
                foreach ($this->collRoleToUsers as $referrerFK) {
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
            $this->setId($arr[$keys[1]]);
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

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPermissionToRoles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPermissionToRole($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRoleToSessionTypes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRoleToSessionType($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRoleToUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRoleToUser($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(null); // this is a auto-increment column, so set to default value
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
        if ('PermissionToRole' == $relationName) {
            return $this->initPermissionToRoles();
        }
        if ('RoleToSessionType' == $relationName) {
            return $this->initRoleToSessionTypes();
        }
        if ('RoleToUser' == $relationName) {
            return $this->initRoleToUsers();
        }
    }

    /**
     * Clears out the collPermissionToRoles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPermissionToRoles()
     */
    public function clearPermissionToRoles()
    {
        $this->collPermissionToRoles = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPermissionToRoles collection loaded partially.
     */
    public function resetPartialPermissionToRoles($v = true)
    {
        $this->collPermissionToRolesPartial = $v;
    }

    /**
     * Initializes the collPermissionToRoles collection.
     *
     * By default this just sets the collPermissionToRoles collection to an empty array (like clearcollPermissionToRoles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPermissionToRoles($overrideExisting = true)
    {
        if (null !== $this->collPermissionToRoles && !$overrideExisting) {
            return;
        }
        $this->collPermissionToRoles = new ObjectCollection();
        $this->collPermissionToRoles->setModel('\Phlopsi\AccessControl\Propel\PermissionToRole');
    }

    /**
     * Gets an array of ChildPermissionToRole objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPermissionToRole[] List of ChildPermissionToRole objects
     * @throws PropelException
     */
    public function getPermissionToRoles(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionToRolesPartial && !$this->isNew();
        if (null === $this->collPermissionToRoles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPermissionToRoles) {
                // return empty collection
                $this->initPermissionToRoles();
            } else {
                $collPermissionToRoles = ChildPermissionToRoleQuery::create(null, $criteria)
                    ->filterByRole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPermissionToRolesPartial && count($collPermissionToRoles)) {
                        $this->initPermissionToRoles(false);

                        foreach ($collPermissionToRoles as $obj) {
                            if (false == $this->collPermissionToRoles->contains($obj)) {
                                $this->collPermissionToRoles->append($obj);
                            }
                        }

                        $this->collPermissionToRolesPartial = true;
                    }

                    return $collPermissionToRoles;
                }

                if ($partial && $this->collPermissionToRoles) {
                    foreach ($this->collPermissionToRoles as $obj) {
                        if ($obj->isNew()) {
                            $collPermissionToRoles[] = $obj;
                        }
                    }
                }

                $this->collPermissionToRoles = $collPermissionToRoles;
                $this->collPermissionToRolesPartial = false;
            }
        }

        return $this->collPermissionToRoles;
    }

    /**
     * Sets a collection of ChildPermissionToRole objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $permissionToRoles A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setPermissionToRoles(Collection $permissionToRoles, ConnectionInterface $con = null)
    {
        /** @var ChildPermissionToRole[] $permissionToRolesToDelete */
        $permissionToRolesToDelete = $this->getPermissionToRoles(new Criteria(), $con)->diff($permissionToRoles);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->permissionToRolesScheduledForDeletion = clone $permissionToRolesToDelete;

        foreach ($permissionToRolesToDelete as $permissionToRoleRemoved) {
            $permissionToRoleRemoved->setRole(null);
        }

        $this->collPermissionToRoles = null;
        foreach ($permissionToRoles as $permissionToRole) {
            $this->addPermissionToRole($permissionToRole);
        }

        $this->collPermissionToRoles = $permissionToRoles;
        $this->collPermissionToRolesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PermissionToRole objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PermissionToRole objects.
     * @throws PropelException
     */
    public function countPermissionToRoles(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionToRolesPartial && !$this->isNew();
        if (null === $this->collPermissionToRoles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPermissionToRoles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPermissionToRoles());
            }

            $query = ChildPermissionToRoleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRole($this)
                ->count($con);
        }

        return count($this->collPermissionToRoles);
    }

    /**
     * Method called to associate a ChildPermissionToRole object to this object
     * through the ChildPermissionToRole foreign key attribute.
     *
     * @param  ChildPermissionToRole $l ChildPermissionToRole
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function addPermissionToRole(ChildPermissionToRole $l)
    {
        if ($this->collPermissionToRoles === null) {
            $this->initPermissionToRoles();
            $this->collPermissionToRolesPartial = true;
        }

        if (!$this->collPermissionToRoles->contains($l)) {
            $this->doAddPermissionToRole($l);
        }

        return $this;
    }

    /**
     * @param ChildPermissionToRole $permissionToRole The ChildPermissionToRole object to add.
     */
    protected function doAddPermissionToRole(ChildPermissionToRole $permissionToRole)
    {
        $this->collPermissionToRoles[]= $permissionToRole;
        $permissionToRole->setRole($this);
    }

    /**
     * @param  ChildPermissionToRole $permissionToRole The ChildPermissionToRole object to remove.
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function removePermissionToRole(ChildPermissionToRole $permissionToRole)
    {
        if ($this->getPermissionToRoles()->contains($permissionToRole)) {
            $pos = $this->collPermissionToRoles->search($permissionToRole);
            $this->collPermissionToRoles->remove($pos);
            if (null === $this->permissionToRolesScheduledForDeletion) {
                $this->permissionToRolesScheduledForDeletion = clone $this->collPermissionToRoles;
                $this->permissionToRolesScheduledForDeletion->clear();
            }
            $this->permissionToRolesScheduledForDeletion[]= clone $permissionToRole;
            $permissionToRole->setRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related PermissionToRoles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPermissionToRole[] List of ChildPermissionToRole objects
     */
    public function getPermissionToRolesJoinPermission(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPermissionToRoleQuery::create(null, $criteria);
        $query->joinWith('Permission', $joinBehavior);

        return $this->getPermissionToRoles($query, $con);
    }

    /**
     * Clears out the collRoleToSessionTypes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRoleToSessionTypes()
     */
    public function clearRoleToSessionTypes()
    {
        $this->collRoleToSessionTypes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRoleToSessionTypes collection loaded partially.
     */
    public function resetPartialRoleToSessionTypes($v = true)
    {
        $this->collRoleToSessionTypesPartial = $v;
    }

    /**
     * Initializes the collRoleToSessionTypes collection.
     *
     * By default this just sets the collRoleToSessionTypes collection to an empty array (like clearcollRoleToSessionTypes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRoleToSessionTypes($overrideExisting = true)
    {
        if (null !== $this->collRoleToSessionTypes && !$overrideExisting) {
            return;
        }
        $this->collRoleToSessionTypes = new ObjectCollection();
        $this->collRoleToSessionTypes->setModel('\Phlopsi\AccessControl\Propel\RoleToSessionType');
    }

    /**
     * Gets an array of ChildRoleToSessionType objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRoleToSessionType[] List of ChildRoleToSessionType objects
     * @throws PropelException
     */
    public function getRoleToSessionTypes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collRoleToSessionTypesPartial && !$this->isNew();
        if (null === $this->collRoleToSessionTypes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRoleToSessionTypes) {
                // return empty collection
                $this->initRoleToSessionTypes();
            } else {
                $collRoleToSessionTypes = ChildRoleToSessionTypeQuery::create(null, $criteria)
                    ->filterByRole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRoleToSessionTypesPartial && count($collRoleToSessionTypes)) {
                        $this->initRoleToSessionTypes(false);

                        foreach ($collRoleToSessionTypes as $obj) {
                            if (false == $this->collRoleToSessionTypes->contains($obj)) {
                                $this->collRoleToSessionTypes->append($obj);
                            }
                        }

                        $this->collRoleToSessionTypesPartial = true;
                    }

                    return $collRoleToSessionTypes;
                }

                if ($partial && $this->collRoleToSessionTypes) {
                    foreach ($this->collRoleToSessionTypes as $obj) {
                        if ($obj->isNew()) {
                            $collRoleToSessionTypes[] = $obj;
                        }
                    }
                }

                $this->collRoleToSessionTypes = $collRoleToSessionTypes;
                $this->collRoleToSessionTypesPartial = false;
            }
        }

        return $this->collRoleToSessionTypes;
    }

    /**
     * Sets a collection of ChildRoleToSessionType objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $roleToSessionTypes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setRoleToSessionTypes(Collection $roleToSessionTypes, ConnectionInterface $con = null)
    {
        /** @var ChildRoleToSessionType[] $roleToSessionTypesToDelete */
        $roleToSessionTypesToDelete = $this->getRoleToSessionTypes(new Criteria(), $con)->diff($roleToSessionTypes);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->roleToSessionTypesScheduledForDeletion = clone $roleToSessionTypesToDelete;

        foreach ($roleToSessionTypesToDelete as $roleToSessionTypeRemoved) {
            $roleToSessionTypeRemoved->setRole(null);
        }

        $this->collRoleToSessionTypes = null;
        foreach ($roleToSessionTypes as $roleToSessionType) {
            $this->addRoleToSessionType($roleToSessionType);
        }

        $this->collRoleToSessionTypes = $roleToSessionTypes;
        $this->collRoleToSessionTypesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related RoleToSessionType objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related RoleToSessionType objects.
     * @throws PropelException
     */
    public function countRoleToSessionTypes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collRoleToSessionTypesPartial && !$this->isNew();
        if (null === $this->collRoleToSessionTypes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRoleToSessionTypes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRoleToSessionTypes());
            }

            $query = ChildRoleToSessionTypeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRole($this)
                ->count($con);
        }

        return count($this->collRoleToSessionTypes);
    }

    /**
     * Method called to associate a ChildRoleToSessionType object to this object
     * through the ChildRoleToSessionType foreign key attribute.
     *
     * @param  ChildRoleToSessionType $l ChildRoleToSessionType
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function addRoleToSessionType(ChildRoleToSessionType $l)
    {
        if ($this->collRoleToSessionTypes === null) {
            $this->initRoleToSessionTypes();
            $this->collRoleToSessionTypesPartial = true;
        }

        if (!$this->collRoleToSessionTypes->contains($l)) {
            $this->doAddRoleToSessionType($l);
        }

        return $this;
    }

    /**
     * @param ChildRoleToSessionType $roleToSessionType The ChildRoleToSessionType object to add.
     */
    protected function doAddRoleToSessionType(ChildRoleToSessionType $roleToSessionType)
    {
        $this->collRoleToSessionTypes[]= $roleToSessionType;
        $roleToSessionType->setRole($this);
    }

    /**
     * @param  ChildRoleToSessionType $roleToSessionType The ChildRoleToSessionType object to remove.
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function removeRoleToSessionType(ChildRoleToSessionType $roleToSessionType)
    {
        if ($this->getRoleToSessionTypes()->contains($roleToSessionType)) {
            $pos = $this->collRoleToSessionTypes->search($roleToSessionType);
            $this->collRoleToSessionTypes->remove($pos);
            if (null === $this->roleToSessionTypesScheduledForDeletion) {
                $this->roleToSessionTypesScheduledForDeletion = clone $this->collRoleToSessionTypes;
                $this->roleToSessionTypesScheduledForDeletion->clear();
            }
            $this->roleToSessionTypesScheduledForDeletion[]= clone $roleToSessionType;
            $roleToSessionType->setRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related RoleToSessionTypes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRoleToSessionType[] List of ChildRoleToSessionType objects
     */
    public function getRoleToSessionTypesJoinSessionType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRoleToSessionTypeQuery::create(null, $criteria);
        $query->joinWith('SessionType', $joinBehavior);

        return $this->getRoleToSessionTypes($query, $con);
    }

    /**
     * Clears out the collRoleToUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRoleToUsers()
     */
    public function clearRoleToUsers()
    {
        $this->collRoleToUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRoleToUsers collection loaded partially.
     */
    public function resetPartialRoleToUsers($v = true)
    {
        $this->collRoleToUsersPartial = $v;
    }

    /**
     * Initializes the collRoleToUsers collection.
     *
     * By default this just sets the collRoleToUsers collection to an empty array (like clearcollRoleToUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRoleToUsers($overrideExisting = true)
    {
        if (null !== $this->collRoleToUsers && !$overrideExisting) {
            return;
        }
        $this->collRoleToUsers = new ObjectCollection();
        $this->collRoleToUsers->setModel('\Phlopsi\AccessControl\Propel\RoleToUser');
    }

    /**
     * Gets an array of ChildRoleToUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildRole is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRoleToUser[] List of ChildRoleToUser objects
     * @throws PropelException
     */
    public function getRoleToUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collRoleToUsersPartial && !$this->isNew();
        if (null === $this->collRoleToUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRoleToUsers) {
                // return empty collection
                $this->initRoleToUsers();
            } else {
                $collRoleToUsers = ChildRoleToUserQuery::create(null, $criteria)
                    ->filterByRole($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRoleToUsersPartial && count($collRoleToUsers)) {
                        $this->initRoleToUsers(false);

                        foreach ($collRoleToUsers as $obj) {
                            if (false == $this->collRoleToUsers->contains($obj)) {
                                $this->collRoleToUsers->append($obj);
                            }
                        }

                        $this->collRoleToUsersPartial = true;
                    }

                    return $collRoleToUsers;
                }

                if ($partial && $this->collRoleToUsers) {
                    foreach ($this->collRoleToUsers as $obj) {
                        if ($obj->isNew()) {
                            $collRoleToUsers[] = $obj;
                        }
                    }
                }

                $this->collRoleToUsers = $collRoleToUsers;
                $this->collRoleToUsersPartial = false;
            }
        }

        return $this->collRoleToUsers;
    }

    /**
     * Sets a collection of ChildRoleToUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $roleToUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function setRoleToUsers(Collection $roleToUsers, ConnectionInterface $con = null)
    {
        /** @var ChildRoleToUser[] $roleToUsersToDelete */
        $roleToUsersToDelete = $this->getRoleToUsers(new Criteria(), $con)->diff($roleToUsers);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->roleToUsersScheduledForDeletion = clone $roleToUsersToDelete;

        foreach ($roleToUsersToDelete as $roleToUserRemoved) {
            $roleToUserRemoved->setRole(null);
        }

        $this->collRoleToUsers = null;
        foreach ($roleToUsers as $roleToUser) {
            $this->addRoleToUser($roleToUser);
        }

        $this->collRoleToUsers = $roleToUsers;
        $this->collRoleToUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related RoleToUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related RoleToUser objects.
     * @throws PropelException
     */
    public function countRoleToUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collRoleToUsersPartial && !$this->isNew();
        if (null === $this->collRoleToUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRoleToUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRoleToUsers());
            }

            $query = ChildRoleToUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByRole($this)
                ->count($con);
        }

        return count($this->collRoleToUsers);
    }

    /**
     * Method called to associate a ChildRoleToUser object to this object
     * through the ChildRoleToUser foreign key attribute.
     *
     * @param  ChildRoleToUser $l ChildRoleToUser
     * @return $this|\Phlopsi\AccessControl\Propel\Role The current object (for fluent API support)
     */
    public function addRoleToUser(ChildRoleToUser $l)
    {
        if ($this->collRoleToUsers === null) {
            $this->initRoleToUsers();
            $this->collRoleToUsersPartial = true;
        }

        if (!$this->collRoleToUsers->contains($l)) {
            $this->doAddRoleToUser($l);
        }

        return $this;
    }

    /**
     * @param ChildRoleToUser $roleToUser The ChildRoleToUser object to add.
     */
    protected function doAddRoleToUser(ChildRoleToUser $roleToUser)
    {
        $this->collRoleToUsers[]= $roleToUser;
        $roleToUser->setRole($this);
    }

    /**
     * @param  ChildRoleToUser $roleToUser The ChildRoleToUser object to remove.
     * @return $this|ChildRole The current object (for fluent API support)
     */
    public function removeRoleToUser(ChildRoleToUser $roleToUser)
    {
        if ($this->getRoleToUsers()->contains($roleToUser)) {
            $pos = $this->collRoleToUsers->search($roleToUser);
            $this->collRoleToUsers->remove($pos);
            if (null === $this->roleToUsersScheduledForDeletion) {
                $this->roleToUsersScheduledForDeletion = clone $this->collRoleToUsers;
                $this->roleToUsersScheduledForDeletion->clear();
            }
            $this->roleToUsersScheduledForDeletion[]= clone $roleToUser;
            $roleToUser->setRole(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Role is new, it will return
     * an empty collection; or if this Role has previously
     * been saved, it will retrieve related RoleToUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Role.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRoleToUser[] List of ChildRoleToUser objects
     */
    public function getRoleToUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRoleToUserQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getRoleToUsers($query, $con);
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
        $permissionToRole = new ChildPermissionToRole();

        $permissionToRole->setPermission($permission);

        $permissionToRole->setRole($this);

        $this->addPermissionToRole($permissionToRole);

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
        if ($this->getPermissions()->contains($permission)) {
            $permissionToRole = new ChildPermissionToRole();

            $permissionToRole->setPermission($permission);
            if ($permission->isRolesLoaded()) {
                //remove the back reference if available
                $permission->getRoles()->removeObject($this);
            }

            $permissionToRole->setRole($this);
            $this->removePermissionToRole(clone $permissionToRole);
            $permissionToRole->clear();

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
        $roleToSessionType = new ChildRoleToSessionType();

        $roleToSessionType->setSessionType($sessionType);

        $roleToSessionType->setRole($this);

        $this->addRoleToSessionType($roleToSessionType);

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
        if ($this->getSessionTypes()->contains($sessionType)) {
            $roleToSessionType = new ChildRoleToSessionType();

            $roleToSessionType->setSessionType($sessionType);
            if ($sessionType->isRolesLoaded()) {
                //remove the back reference if available
                $sessionType->getRoles()->removeObject($this);
            }

            $roleToSessionType->setRole($this);
            $this->removeRoleToSessionType(clone $roleToSessionType);
            $roleToSessionType->clear();

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
        $roleToUser = new ChildRoleToUser();

        $roleToUser->setUser($user);

        $roleToUser->setRole($this);

        $this->addRoleToUser($roleToUser);

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
        if ($this->getUsers()->contains($user)) {
            $roleToUser = new ChildRoleToUser();

            $roleToUser->setUser($user);
            if ($user->isRolesLoaded()) {
                //remove the back reference if available
                $user->getRoles()->removeObject($this);
            }

            $roleToUser->setRole($this);
            $this->removeRoleToUser(clone $roleToUser);
            $roleToUser->clear();

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
            if ($this->collPermissionToRoles) {
                foreach ($this->collPermissionToRoles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRoleToSessionTypes) {
                foreach ($this->collRoleToSessionTypes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRoleToUsers) {
                foreach ($this->collRoleToUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPermissions) {
                foreach ($this->collPermissions as $o) {
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

        $this->collPermissionToRoles = null;
        $this->collRoleToSessionTypes = null;
        $this->collRoleToUsers = null;
        $this->collPermissions = null;
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
