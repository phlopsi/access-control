<?php
namespace org\bitbucket\phlopsi\access_control\propel\Base;

use \Exception;
use \PDO;
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
use org\bitbucket\phlopsi\access_control\propel\Prohibition as ChildProhibition;
use org\bitbucket\phlopsi\access_control\propel\ProhibitionQuery as ChildProhibitionQuery;
use org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsers as ChildProhibitionsUsers;
use org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsersQuery as ChildProhibitionsUsersQuery;
use org\bitbucket\phlopsi\access_control\propel\Role as ChildRole;
use org\bitbucket\phlopsi\access_control\propel\RoleQuery as ChildRoleQuery;
use org\bitbucket\phlopsi\access_control\propel\RolesUsers as ChildRolesUsers;
use org\bitbucket\phlopsi\access_control\propel\RolesUsersQuery as ChildRolesUsersQuery;
use org\bitbucket\phlopsi\access_control\propel\User as ChildUser;
use org\bitbucket\phlopsi\access_control\propel\UserQuery as ChildUserQuery;
use org\bitbucket\phlopsi\access_control\propel\Map\UserTableMap;

abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\org\\bitbucket\\phlopsi\\access_control\\propel\\Map\\UserTableMap';

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
     * @var        ObjectCollection|ChildProhibitionsUsers[] Collection to store aggregation of ChildProhibitionsUsers objects.
     */
    protected $collProhibitionsUserss;
    protected $collProhibitionsUserssPartial;

    /**
     * @var        ObjectCollection|ChildRolesUsers[] Collection to store aggregation of ChildRolesUsers objects.
     */
    protected $collRolesUserss;
    protected $collRolesUserssPartial;

    /**
     * @var        ObjectCollection|ChildProhibition[] Cross Collection to store aggregation of ChildProhibition objects.
     */
    protected $collProhibitions;

    /**
     * @var bool
     */
    protected $collProhibitionsPartial;

    /**
     * @var        ObjectCollection|ChildRole[] Cross Collection to store aggregation of ChildRole objects.
     */
    protected $collRoles;

    /**
     * @var bool
     */
    protected $collRolesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildProhibition[]
     */
    protected $prohibitionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRole[]
     */
    protected $rolesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildProhibitionsUsers[]
     */
    protected $prohibitionsUserssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRolesUsers[]
     */
    protected $rolesUserssScheduledForDeletion = null;

    /**
     * Initializes internal state of org\bitbucket\phlopsi\access_control\propel\Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
    }

// hasOnlyDefaultValues()
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
      One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('ExternalId',
                        TableMap::TYPE_PHPNAME, $indexType)];
            $this->external_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Id',
                        TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = UserTableMap::NUM_HYDRATE_COLUMNS.
        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object',
                '\\org\\bitbucket\\phlopsi\\access_control\\propel\\User'), 0, $e);
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
        
    }

// ensureConsistency
    /**
     * Set the value of [external_id] column.
     *
     * @param  string $v new value
     * @return $this|\org\bitbucket\phlopsi\access_control\propel\User The current object (for fluent API support)
     */
    public function setExternalId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->external_id !== $v) {
            $this->external_id = $v;
            $this->modifiedColumns[UserTableMap::COL_EXTERNAL_ID] = true;
        }

        return $this;
    }

// setExternalId()
    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\org\bitbucket\phlopsi\access_control\propel\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    }

// setId()
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?
            $this->collProhibitionsUserss = null;

            $this->collRolesUserss = null;

            $this->collProhibitions = null;
            $this->collRoles = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
                $isInsert = $this->isNew();
                $ret = $this->preSave($con);
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
                    UserTableMap::addInstanceToPool($this);
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

            if ($this->prohibitionsScheduledForDeletion !== null) {
                if (!$this->prohibitionsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->prohibitionsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsersQuery::create()
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


            if ($this->rolesScheduledForDeletion !== null) {
                if (!$this->rolesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->rolesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \org\bitbucket\phlopsi\access_control\propel\RolesUsersQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->rolesScheduledForDeletion = null;
                }
            }

            if ($this->collRoles) {
                foreach ($this->collRoles as $role) {
                    if (!$role->isDeleted() && ($role->isNew() || $role->isModified())) {
                        $role->save($con);
                    }
                }
            }


            if ($this->prohibitionsUserssScheduledForDeletion !== null) {
                if (!$this->prohibitionsUserssScheduledForDeletion->isEmpty()) {
                    \org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsersQuery::create()
                        ->filterByPrimaryKeys($this->prohibitionsUserssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->prohibitionsUserssScheduledForDeletion = null;
                }
            }

            if ($this->collProhibitionsUserss !== null) {
                foreach ($this->collProhibitionsUserss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->rolesUserssScheduledForDeletion !== null) {
                if (!$this->rolesUserssScheduledForDeletion->isEmpty()) {
                    \org\bitbucket\phlopsi\access_control\propel\RolesUsersQuery::create()
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
    }

// doSave()
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

        // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_EXTERNAL_ID)) {
            $modifiedColumns[':p' . $index++] = 'EXTERNAL_ID';
        }
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++] = 'ID';
        }

        $sql = sprintf(
            'INSERT INTO users (%s) VALUES (%s)', implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'EXTERNAL_ID':
                        $stmt->bindValue($identifier, $this->external_id, PDO::PARAM_STR);
                        break;
                    case 'ID':
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
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true,
        $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['User'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->getPrimaryKey()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getExternalId(),
            $keys[1] => $this->getId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collProhibitionsUserss) {
                $result['ProhibitionsUserss'] = $this->collProhibitionsUserss->toArray(null, true, $keyType,
                    $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRolesUserss) {
                $result['RolesUserss'] = $this->collRolesUserss->toArray(null, true, $keyType, $includeLazyLoadColumns,
                    $alreadyDumpedObjects);
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
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\org\bitbucket\phlopsi\access_control\propel\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\org\bitbucket\phlopsi\access_control\propel\User
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
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = UserTableMap::getFieldNames($keyType);

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
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return $this|\org\bitbucket\phlopsi\access_control\propel\User The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_EXTERNAL_ID)) {
            $criteria->add(UserTableMap::COL_EXTERNAL_ID, $this->external_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \org\bitbucket\phlopsi\access_control\propel\User (or compatible) type.
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

            foreach ($this->getProhibitionsUserss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProhibitionsUsers($relObj->copy($deepCopy));
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
     * @return \org\bitbucket\phlopsi\access_control\propel\User Clone of current object.
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
        if ('ProhibitionsUsers' == $relationName) {
            return $this->initProhibitionsUserss();
        }
        if ('RolesUsers' == $relationName) {
            return $this->initRolesUserss();
        }
    }

    /**
     * Clears out the collProhibitionsUserss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addProhibitionsUserss()
     */
    public function clearProhibitionsUserss()
    {
        $this->collProhibitionsUserss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collProhibitionsUserss collection loaded partially.
     */
    public function resetPartialProhibitionsUserss($v = true)
    {
        $this->collProhibitionsUserssPartial = $v;
    }

    /**
     * Initializes the collProhibitionsUserss collection.
     *
     * By default this just sets the collProhibitionsUserss collection to an empty array (like clearcollProhibitionsUserss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProhibitionsUserss($overrideExisting = true)
    {
        if (null !== $this->collProhibitionsUserss && !$overrideExisting) {
            return;
        }
        $this->collProhibitionsUserss = new ObjectCollection();
        $this->collProhibitionsUserss->setModel('\org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsers');
    }

    /**
     * Gets an array of ChildProhibitionsUsers objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildProhibitionsUsers[] List of ChildProhibitionsUsers objects
     * @throws PropelException
     */
    public function getProhibitionsUserss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collProhibitionsUserssPartial && !$this->isNew();
        if (null === $this->collProhibitionsUserss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProhibitionsUserss) {
                // return empty collection
                $this->initProhibitionsUserss();
            } else {
                $collProhibitionsUserss = ChildProhibitionsUsersQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collProhibitionsUserssPartial && count($collProhibitionsUserss)) {
                        $this->initProhibitionsUserss(false);

                        foreach ($collProhibitionsUserss as $obj) {
                            if (false == $this->collProhibitionsUserss->contains($obj)) {
                                $this->collProhibitionsUserss->append($obj);
                            }
                        }

                        $this->collProhibitionsUserssPartial = true;
                    }

                    return $collProhibitionsUserss;
                }

                if ($partial && $this->collProhibitionsUserss) {
                    foreach ($this->collProhibitionsUserss as $obj) {
                        if ($obj->isNew()) {
                            $collProhibitionsUserss[] = $obj;
                        }
                    }
                }

                $this->collProhibitionsUserss = $collProhibitionsUserss;
                $this->collProhibitionsUserssPartial = false;
            }
        }

        return $this->collProhibitionsUserss;
    }

    /**
     * Sets a collection of ChildProhibitionsUsers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $prohibitionsUserss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setProhibitionsUserss(Collection $prohibitionsUserss, ConnectionInterface $con = null)
    {
        /** @var ChildProhibitionsUsers[] $prohibitionsUserssToDelete */
        $prohibitionsUserssToDelete = $this->getProhibitionsUserss(new Criteria(), $con)->diff($prohibitionsUserss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->prohibitionsUserssScheduledForDeletion = clone $prohibitionsUserssToDelete;

        foreach ($prohibitionsUserssToDelete as $prohibitionsUsersRemoved) {
            $prohibitionsUsersRemoved->setUser(null);
        }

        $this->collProhibitionsUserss = null;
        foreach ($prohibitionsUserss as $prohibitionsUsers) {
            $this->addProhibitionsUsers($prohibitionsUsers);
        }

        $this->collProhibitionsUserss = $prohibitionsUserss;
        $this->collProhibitionsUserssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ProhibitionsUsers objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ProhibitionsUsers objects.
     * @throws PropelException
     */
    public function countProhibitionsUserss(Criteria $criteria = null, $distinct = false,
        ConnectionInterface $con = null)
    {
        $partial = $this->collProhibitionsUserssPartial && !$this->isNew();
        if (null === $this->collProhibitionsUserss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProhibitionsUserss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProhibitionsUserss());
            }

            $query = ChildProhibitionsUsersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                    ->filterByUser($this)
                    ->count($con);
        }

        return count($this->collProhibitionsUserss);
    }

    /**
     * Method called to associate a ChildProhibitionsUsers object to this object
     * through the ChildProhibitionsUsers foreign key attribute.
     *
     * @param  ChildProhibitionsUsers $l ChildProhibitionsUsers
     * @return $this|\org\bitbucket\phlopsi\access_control\propel\User The current object (for fluent API support)
     */
    public function addProhibitionsUsers(ChildProhibitionsUsers $l)
    {
        if ($this->collProhibitionsUserss === null) {
            $this->initProhibitionsUserss();
            $this->collProhibitionsUserssPartial = true;
        }

        if (!$this->collProhibitionsUserss->contains($l)) {
            $this->doAddProhibitionsUsers($l);
        }

        return $this;
    }

    /**
     * @param ChildProhibitionsUsers $prohibitionsUsers The ChildProhibitionsUsers object to add.
     */
    protected function doAddProhibitionsUsers(ChildProhibitionsUsers $prohibitionsUsers)
    {
        $this->collProhibitionsUserss[] = $prohibitionsUsers;
        $prohibitionsUsers->setUser($this);
    }

    /**
     * @param  ChildProhibitionsUsers $prohibitionsUsers The ChildProhibitionsUsers object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeProhibitionsUsers(ChildProhibitionsUsers $prohibitionsUsers)
    {
        if ($this->getProhibitionsUserss()->contains($prohibitionsUsers)) {
            $pos = $this->collProhibitionsUserss->search($prohibitionsUsers);
            $this->collProhibitionsUserss->remove($pos);
            if (null === $this->prohibitionsUserssScheduledForDeletion) {
                $this->prohibitionsUserssScheduledForDeletion = clone $this->collProhibitionsUserss;
                $this->prohibitionsUserssScheduledForDeletion->clear();
            }
            $this->prohibitionsUserssScheduledForDeletion[] = clone $prohibitionsUsers;
            $prohibitionsUsers->setUser(null);
        }

        return $this;
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ProhibitionsUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildProhibitionsUsers[] List of ChildProhibitionsUsers objects
     */
    public function getProhibitionsUserssJoinProhibition(Criteria $criteria = null, ConnectionInterface $con = null,
        $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildProhibitionsUsersQuery::create(null, $criteria);
        $query->joinWith('Prohibition', $joinBehavior);

        return $this->getProhibitionsUserss($query, $con);
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
        $this->collRolesUserss->setModel('\org\bitbucket\phlopsi\access_control\propel\RolesUsers');
    }

    /**
     * Gets an array of ChildRolesUsers objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
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
        if (null === $this->collRolesUserss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRolesUserss) {
                // return empty collection
                $this->initRolesUserss();
            } else {
                $collRolesUserss = ChildRolesUsersQuery::create(null, $criteria)
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $rolesUsersRemoved->setUser(null);
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
                    ->filterByUser($this)
                    ->count($con);
        }

        return count($this->collRolesUserss);
    }

    /**
     * Method called to associate a ChildRolesUsers object to this object
     * through the ChildRolesUsers foreign key attribute.
     *
     * @param  ChildRolesUsers $l ChildRolesUsers
     * @return $this|\org\bitbucket\phlopsi\access_control\propel\User The current object (for fluent API support)
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
        $this->collRolesUserss[] = $rolesUsers;
        $rolesUsers->setUser($this);
    }

    /**
     * @param  ChildRolesUsers $rolesUsers The ChildRolesUsers object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $this->rolesUserssScheduledForDeletion[] = clone $rolesUsers;
            $rolesUsers->setUser(null);
        }

        return $this;
    }

    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related RolesUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRolesUsers[] List of ChildRolesUsers objects
     */
    public function getRolesUserssJoinRole(Criteria $criteria = null, ConnectionInterface $con = null,
        $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRolesUsersQuery::create(null, $criteria);
        $query->joinWith('Role', $joinBehavior);

        return $this->getRolesUserss($query, $con);
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
     * Initializes the collProhibitions collection.
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

        $this->collProhibitions->setModel('\org\bitbucket\phlopsi\access_control\propel\Prohibition');
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
     * to the current object by way of the prohibitions_users cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this);
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
     * to the current object by way of the prohibitions_users cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $prohibitions A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
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
     * to the current object by way of the prohibitions_users cross-reference table.
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
                        ->filterByUser($this)
                        ->count($con);
            }
        } else {
            return count($this->collProhibitions);
        }
    }

    /**
     * Associate a ChildProhibition to this object
     * through the prohibitions_users cross reference table.
     *
     * @param ChildProhibition $prohibition
     * @return ChildUser The current object (for fluent API support)
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
        $prohibitionsUsers = new ChildProhibitionsUsers();

        $prohibitionsUsers->setProhibition($prohibition);

        $prohibitionsUsers->setUser($this);

        $this->addProhibitionsUsers($prohibitionsUsers);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$prohibition->isUsersLoaded()) {
            $prohibition->initUsers();
            $prohibition->getUsers()->push($this);
        } elseif (!$prohibition->getUsers()->contains($this)) {
            $prohibition->getUsers()->push($this);
        }
    }

    /**
     * Remove prohibition of this object
     * through the prohibitions_users cross reference table.
     *
     * @param ChildProhibition $prohibition
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeProhibition(ChildProhibition $prohibition)
    {
        if ($this->getProhibitions()->contains($prohibition)) {
            $prohibitionsUsers = new ChildProhibitionsUsers();

            $prohibitionsUsers->setProhibition($prohibition);
            if ($prohibition->isUsersLoaded()) {
                //remove the back reference if available
                $prohibition->getUsers()->removeObject($this);
            }

            $prohibitionsUsers->setUser($this);
            $this->removeProhibitionsUsers(clone $prohibitionsUsers);
            $prohibitionsUsers->clear();

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
     * Clears out the collRoles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRoles()
     */
    public function clearRoles()
    {
        $this->collRoles = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collRoles collection.
     *
     * By default this just sets the collRoles collection to an empty collection (like clearRoles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initRoles()
    {
        $this->collRoles = new ObjectCollection();
        $this->collRolesPartial = true;

        $this->collRoles->setModel('\org\bitbucket\phlopsi\access_control\propel\Role');
    }

    /**
     * Checks if the collRoles collection is loaded.
     *
     * @return bool
     */
    public function isRolesLoaded()
    {
        return null !== $this->collRoles;
    }

    /**
     * Gets a collection of ChildRole objects related by a many-to-many relationship
     * to the current object by way of the roles_users cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildRole[] List of ChildRole objects
     */
    public function getRoles(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collRolesPartial && !$this->isNew();
        if (null === $this->collRoles || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collRoles) {
                    $this->initRoles();
                }
            } else {

                $query = ChildRoleQuery::create(null, $criteria)
                    ->filterByUser($this);
                $collRoles = $query->find($con);
                if (null !== $criteria) {
                    return $collRoles;
                }

                if ($partial && $this->collRoles) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collRoles as $obj) {
                        if (!$collRoles->contains($obj)) {
                            $collRoles[] = $obj;
                        }
                    }
                }

                $this->collRoles = $collRoles;
                $this->collRolesPartial = false;
            }
        }

        return $this->collRoles;
    }

    /**
     * Sets a collection of Role objects related by a many-to-many relationship
     * to the current object by way of the roles_users cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $roles A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setRoles(Collection $roles, ConnectionInterface $con = null)
    {
        $this->clearRoles();
        $currentRoles = $this->getRoles();

        $rolesScheduledForDeletion = $currentRoles->diff($roles);

        foreach ($rolesScheduledForDeletion as $toDelete) {
            $this->removeRole($toDelete);
        }

        foreach ($roles as $role) {
            if (!$currentRoles->contains($role)) {
                $this->doAddRole($role);
            }
        }

        $this->collRolesPartial = false;
        $this->collRoles = $roles;

        return $this;
    }

    /**
     * Gets the number of Role objects related by a many-to-many relationship
     * to the current object by way of the roles_users cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Role objects
     */
    public function countRoles(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collRolesPartial && !$this->isNew();
        if (null === $this->collRoles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRoles) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getRoles());
                }

                $query = ChildRoleQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                        ->filterByUser($this)
                        ->count($con);
            }
        } else {
            return count($this->collRoles);
        }
    }

    /**
     * Associate a ChildRole to this object
     * through the roles_users cross reference table.
     *
     * @param ChildRole $role
     * @return ChildUser The current object (for fluent API support)
     */
    public function addRole(ChildRole $role)
    {
        if ($this->collRoles === null) {
            $this->initRoles();
        }

        if (!$this->getRoles()->contains($role)) {
            // only add it if the **same** object is not already associated
            $this->collRoles->push($role);
            $this->doAddRole($role);
        }

        return $this;
    }

    /**
     *
     * @param ChildRole $role
     */
    protected function doAddRole(ChildRole $role)
    {
        $rolesUsers = new ChildRolesUsers();

        $rolesUsers->setRole($role);

        $rolesUsers->setUser($this);

        $this->addRolesUsers($rolesUsers);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$role->isUsersLoaded()) {
            $role->initUsers();
            $role->getUsers()->push($this);
        } elseif (!$role->getUsers()->contains($this)) {
            $role->getUsers()->push($this);
        }
    }

    /**
     * Remove role of this object
     * through the roles_users cross reference table.
     *
     * @param ChildRole $role
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeRole(ChildRole $role)
    {
        if ($this->getRoles()->contains($role)) {
            $rolesUsers = new ChildRolesUsers();

            $rolesUsers->setRole($role);
            if ($role->isUsersLoaded()) {
                //remove the back reference if available
                $role->getUsers()->removeObject($this);
            }

            $rolesUsers->setUser($this);
            $this->removeRolesUsers(clone $rolesUsers);
            $rolesUsers->clear();

            $this->collRoles->remove($this->collRoles->search($role));

            if (null === $this->rolesScheduledForDeletion) {
                $this->rolesScheduledForDeletion = clone $this->collRoles;
                $this->rolesScheduledForDeletion->clear();
            }

            $this->rolesScheduledForDeletion->push($role);
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
            if ($this->collProhibitionsUserss) {
                foreach ($this->collProhibitionsUserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRolesUserss) {
                foreach ($this->collRolesUserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProhibitions) {
                foreach ($this->collProhibitions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRoles) {
                foreach ($this->collRoles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collProhibitionsUserss = null;
        $this->collRolesUserss = null;
        $this->collProhibitions = null;
        $this->collRoles = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
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
