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
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsers as ChildProhibitionsUsers;
use org\bitbucket\phlopsi\access_control\propel\ProhibitionsUsersQuery as ChildProhibitionsUsersQuery;
use org\bitbucket\phlopsi\access_control\propel\RolesUsers as ChildRolesUsers;
use org\bitbucket\phlopsi\access_control\propel\RolesUsersQuery as ChildRolesUsersQuery;
use org\bitbucket\phlopsi\access_control\propel\Sessions as ChildSessions;
use org\bitbucket\phlopsi\access_control\propel\SessionsQuery as ChildSessionsQuery;
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
     * @var        ObjectCollection|ChildSessions[] Collection to store aggregation of ChildSessions objects.
     */
    protected $collSessionss;
    protected $collSessionssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $prohibitionsUserssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $rolesUserssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $sessionssScheduledForDeletion = null;

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
        return !empty($this->modifiedColumns);
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return in_array($col, $this->modifiedColumns);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return array_unique($this->modifiedColumns);
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
        $this->new = (Boolean) $b;
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
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            while (false !== ($offset = array_search($col, $this->modifiedColumns))) {
                array_splice($this->modifiedColumns, $offset, 1);
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
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
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
     * @return User The current object, for fluid interface
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
     * @return User The current object, for fluid interface
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
     * @return   string
     */
    public function getExternalId()
    {

        return $this->external_id;
    }

    /**
     * Get the [id] column value.
     *
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Set the value of [external_id] column.
     *
     * @param      string $v new value
     * @return   \org\bitbucket\phlopsi\access_control\propel\User The current object (for fluent API support)
     */
    public function setExternalId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->external_id !== $v) {
            $this->external_id = $v;
            $this->modifiedColumns[] = UserTableMap::EXTERNAL_ID;
        }


        return $this;
    } // setExternalId()

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \org\bitbucket\phlopsi\access_control\propel\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserTableMap::ID;
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
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('ExternalId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->external_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \org\bitbucket\phlopsi\access_control\propel\User object", 0, $e);
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

            $this->collSessionss = null;

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

        $con->beginTransaction();
        try {
            $deleteQuery = ChildUserQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
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

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
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
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
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

            if ($this->sessionssScheduledForDeletion !== null) {
                if (!$this->sessionssScheduledForDeletion->isEmpty()) {
                    foreach ($this->sessionssScheduledForDeletion as $sessions) {
                        // need to save related object because we set the relation to null
                        $sessions->save($con);
                    }
                    $this->sessionssScheduledForDeletion = null;
                }
            }

                if ($this->collSessionss !== null) {
            foreach ($this->collSessionss as $referrerFK) {
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

        $this->modifiedColumns[] = UserTableMap::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::EXTERNAL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'EXTERNAL_ID';
        }
        if ($this->isColumnModified(UserTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }

        $sql = sprintf(
            'INSERT INTO users (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
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
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
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
                $result['ProhibitionsUserss'] = $this->collProhibitionsUserss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRolesUserss) {
                $result['RolesUserss'] = $this->collRolesUserss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSessionss) {
                $result['Sessionss'] = $this->collSessionss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
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
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
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

        if (array_key_exists($keys[0], $arr)) $this->setExternalId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setId($arr[$keys[1]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::EXTERNAL_ID)) $criteria->add(UserTableMap::EXTERNAL_ID, $this->external_id);
        if ($this->isColumnModified(UserTableMap::ID)) $criteria->add(UserTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);
        $criteria->add(UserTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
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

            foreach ($this->getSessionss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSessions($relObj->copy($deepCopy));
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
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \org\bitbucket\phlopsi\access_control\propel\User Clone of current object.
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
        if ('Sessions' == $relationName) {
            return $this->initSessionss();
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
     * @return Collection|ChildProhibitionsUsers[] List of ChildProhibitionsUsers objects
     * @throws PropelException
     */
    public function getProhibitionsUserss($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collProhibitionsUserssPartial && !$this->isNew();
        if (null === $this->collProhibitionsUserss || null !== $criteria  || $partial) {
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

                    $collProhibitionsUserss->getInternalIterator()->rewind();

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
     * Sets a collection of ProhibitionsUsers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $prohibitionsUserss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildUser The current object (for fluent API support)
     */
    public function setProhibitionsUserss(Collection $prohibitionsUserss, ConnectionInterface $con = null)
    {
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
    public function countProhibitionsUserss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
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
     * @param    ChildProhibitionsUsers $l ChildProhibitionsUsers
     * @return   \org\bitbucket\phlopsi\access_control\propel\User The current object (for fluent API support)
     */
    public function addProhibitionsUsers(ChildProhibitionsUsers $l)
    {
        if ($this->collProhibitionsUserss === null) {
            $this->initProhibitionsUserss();
            $this->collProhibitionsUserssPartial = true;
        }

        if (!in_array($l, $this->collProhibitionsUserss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProhibitionsUsers($l);
        }

        return $this;
    }

    /**
     * @param ProhibitionsUsers $prohibitionsUsers The prohibitionsUsers object to add.
     */
    protected function doAddProhibitionsUsers($prohibitionsUsers)
    {
        $this->collProhibitionsUserss[]= $prohibitionsUsers;
        $prohibitionsUsers->setUser($this);
    }

    /**
     * @param  ProhibitionsUsers $prohibitionsUsers The prohibitionsUsers object to remove.
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeProhibitionsUsers($prohibitionsUsers)
    {
        if ($this->getProhibitionsUserss()->contains($prohibitionsUsers)) {
            $this->collProhibitionsUserss->remove($this->collProhibitionsUserss->search($prohibitionsUsers));
            if (null === $this->prohibitionsUserssScheduledForDeletion) {
                $this->prohibitionsUserssScheduledForDeletion = clone $this->collProhibitionsUserss;
                $this->prohibitionsUserssScheduledForDeletion->clear();
            }
            $this->prohibitionsUserssScheduledForDeletion[]= clone $prohibitionsUsers;
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
     * @return Collection|ChildProhibitionsUsers[] List of ChildProhibitionsUsers objects
     */
    public function getProhibitionsUserssJoinPermission($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildProhibitionsUsersQuery::create(null, $criteria);
        $query->joinWith('Permission', $joinBehavior);

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
     * @return Collection|ChildRolesUsers[] List of ChildRolesUsers objects
     * @throws PropelException
     */
    public function getRolesUserss($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collRolesUserssPartial && !$this->isNew();
        if (null === $this->collRolesUserss || null !== $criteria  || $partial) {
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

                    $collRolesUserss->getInternalIterator()->rewind();

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
     * Sets a collection of RolesUsers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $rolesUserss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildUser The current object (for fluent API support)
     */
    public function setRolesUserss(Collection $rolesUserss, ConnectionInterface $con = null)
    {
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
     * @param    ChildRolesUsers $l ChildRolesUsers
     * @return   \org\bitbucket\phlopsi\access_control\propel\User The current object (for fluent API support)
     */
    public function addRolesUsers(ChildRolesUsers $l)
    {
        if ($this->collRolesUserss === null) {
            $this->initRolesUserss();
            $this->collRolesUserssPartial = true;
        }

        if (!in_array($l, $this->collRolesUserss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddRolesUsers($l);
        }

        return $this;
    }

    /**
     * @param RolesUsers $rolesUsers The rolesUsers object to add.
     */
    protected function doAddRolesUsers($rolesUsers)
    {
        $this->collRolesUserss[]= $rolesUsers;
        $rolesUsers->setUser($this);
    }

    /**
     * @param  RolesUsers $rolesUsers The rolesUsers object to remove.
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeRolesUsers($rolesUsers)
    {
        if ($this->getRolesUserss()->contains($rolesUsers)) {
            $this->collRolesUserss->remove($this->collRolesUserss->search($rolesUsers));
            if (null === $this->rolesUserssScheduledForDeletion) {
                $this->rolesUserssScheduledForDeletion = clone $this->collRolesUserss;
                $this->rolesUserssScheduledForDeletion->clear();
            }
            $this->rolesUserssScheduledForDeletion[]= clone $rolesUsers;
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
     * @return Collection|ChildRolesUsers[] List of ChildRolesUsers objects
     */
    public function getRolesUserssJoinRole($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRolesUsersQuery::create(null, $criteria);
        $query->joinWith('Role', $joinBehavior);

        return $this->getRolesUserss($query, $con);
    }

    /**
     * Clears out the collSessionss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSessionss()
     */
    public function clearSessionss()
    {
        $this->collSessionss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSessionss collection loaded partially.
     */
    public function resetPartialSessionss($v = true)
    {
        $this->collSessionssPartial = $v;
    }

    /**
     * Initializes the collSessionss collection.
     *
     * By default this just sets the collSessionss collection to an empty array (like clearcollSessionss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSessionss($overrideExisting = true)
    {
        if (null !== $this->collSessionss && !$overrideExisting) {
            return;
        }
        $this->collSessionss = new ObjectCollection();
        $this->collSessionss->setModel('\org\bitbucket\phlopsi\access_control\propel\Sessions');
    }

    /**
     * Gets an array of ChildSessions objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildSessions[] List of ChildSessions objects
     * @throws PropelException
     */
    public function getSessionss($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSessionssPartial && !$this->isNew();
        if (null === $this->collSessionss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSessionss) {
                // return empty collection
                $this->initSessionss();
            } else {
                $collSessionss = ChildSessionsQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSessionssPartial && count($collSessionss)) {
                        $this->initSessionss(false);

                        foreach ($collSessionss as $obj) {
                            if (false == $this->collSessionss->contains($obj)) {
                                $this->collSessionss->append($obj);
                            }
                        }

                        $this->collSessionssPartial = true;
                    }

                    $collSessionss->getInternalIterator()->rewind();

                    return $collSessionss;
                }

                if ($partial && $this->collSessionss) {
                    foreach ($this->collSessionss as $obj) {
                        if ($obj->isNew()) {
                            $collSessionss[] = $obj;
                        }
                    }
                }

                $this->collSessionss = $collSessionss;
                $this->collSessionssPartial = false;
            }
        }

        return $this->collSessionss;
    }

    /**
     * Sets a collection of Sessions objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $sessionss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildUser The current object (for fluent API support)
     */
    public function setSessionss(Collection $sessionss, ConnectionInterface $con = null)
    {
        $sessionssToDelete = $this->getSessionss(new Criteria(), $con)->diff($sessionss);


        $this->sessionssScheduledForDeletion = $sessionssToDelete;

        foreach ($sessionssToDelete as $sessionsRemoved) {
            $sessionsRemoved->setUser(null);
        }

        $this->collSessionss = null;
        foreach ($sessionss as $sessions) {
            $this->addSessions($sessions);
        }

        $this->collSessionss = $sessionss;
        $this->collSessionssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Sessions objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Sessions objects.
     * @throws PropelException
     */
    public function countSessionss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSessionssPartial && !$this->isNew();
        if (null === $this->collSessionss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSessionss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSessionss());
            }

            $query = ChildSessionsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collSessionss);
    }

    /**
     * Method called to associate a ChildSessions object to this object
     * through the ChildSessions foreign key attribute.
     *
     * @param    ChildSessions $l ChildSessions
     * @return   \org\bitbucket\phlopsi\access_control\propel\User The current object (for fluent API support)
     */
    public function addSessions(ChildSessions $l)
    {
        if ($this->collSessionss === null) {
            $this->initSessionss();
            $this->collSessionssPartial = true;
        }

        if (!in_array($l, $this->collSessionss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSessions($l);
        }

        return $this;
    }

    /**
     * @param Sessions $sessions The sessions object to add.
     */
    protected function doAddSessions($sessions)
    {
        $this->collSessionss[]= $sessions;
        $sessions->setUser($this);
    }

    /**
     * @param  Sessions $sessions The sessions object to remove.
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeSessions($sessions)
    {
        if ($this->getSessionss()->contains($sessions)) {
            $this->collSessionss->remove($this->collSessionss->search($sessions));
            if (null === $this->sessionssScheduledForDeletion) {
                $this->sessionssScheduledForDeletion = clone $this->collSessionss;
                $this->sessionssScheduledForDeletion->clear();
            }
            $this->sessionssScheduledForDeletion[]= clone $sessions;
            $sessions->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Sessionss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildSessions[] List of ChildSessions objects
     */
    public function getSessionssJoinSessionType($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSessionsQuery::create(null, $criteria);
        $query->joinWith('SessionType', $joinBehavior);

        return $this->getSessionss($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
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
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
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
            if ($this->collSessionss) {
                foreach ($this->collSessionss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collProhibitionsUserss instanceof Collection) {
            $this->collProhibitionsUserss->clearIterator();
        }
        $this->collProhibitionsUserss = null;
        if ($this->collRolesUserss instanceof Collection) {
            $this->collRolesUserss->clearIterator();
        }
        $this->collRolesUserss = null;
        if ($this->collSessionss instanceof Collection) {
            $this->collSessionss->clearIterator();
        }
        $this->collSessionss = null;
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
