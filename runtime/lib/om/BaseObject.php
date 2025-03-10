<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

/**
 * This class contains attributes and methods that are used by all
 * business objects within the system.
 *
 * @method     BaseObject fromXML(string $data) Populate the object from an XML string
 * @method     BaseObject fromYAML(string $data) Populate the object from a YAML string
 * @method     BaseObject fromJSON(string $data) Populate the object from a JSON string
 * @method     BaseObject fromCSV(string $data) Populate the object from a CSV string
 * @method     string toXML(boolean $includeLazyLoadColumns) Export the object to an XML string
 * @method     string toYAML(boolean $includeLazyLoadColumns) Export the object to a YAML string
 * @method     string toJSON(boolean $includeLazyLoadColumns) Export the object to a JSON string
 * @method     string toCSV(boolean $includeLazyLoadColumns) Export the object to a CSV string
 *
 * @author     Hans Lellelid <hans@xmpl.org> (Propel)
 * @author     Frank Y. Kim <frank.kim@clearink.com> (Torque)
 * @author     John D. McNally <jmcnally@collab.net> (Torque)
 * @version    $Revision$
 * @package    propel.runtime.om
 */
abstract class BaseObject
{

    /**
     * attribute to determine if this object has previously been saved.
     *
     * @var        boolean
     */
    protected $_new = true;

    /**
     * attribute to determine whether this object has been deleted.
     *
     * @var        boolean
     */
    protected $_deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     *
     * @var        array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     *
     * @var        array
     */
    protected $virtualColumns = array();

    /**
     * Empty constructor (this allows people with their own BaseObject implementation to use its constructor)
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
     * @param string $col column fully qualified name (BasePeer::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     *
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return in_array($col, $this->modifiedColumns);
    }

    /**
     * Get the columns that have been modified in this object.
     *
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
     * @return true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->_new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and Peers.
     *
     * @param  boolean $b the state of the object.
     * @return self
     */
    public function setNew($b)
    {
        $this->_new = (boolean) $b;

        return $this;
    }

    /**
     * Whether this object has been deleted.
     *
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->_deleted;
    }

    /**
     * Specify whether this object has been deleted.
     *
     * @param boolean $b The deleted state of this object.
     *
     * @return self
     */
    public function setDeleted($b)
    {
        $this->_deleted = (boolean) $b;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     *
     * @param ?PropelPDO $con
     *
     * @return boolean
     */
    public function preSave(?PropelPDO $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     *
     * @param ?PropelPDO $con
     */
    public function postSave(?PropelPDO $con = null)
    {
    }

    /**
     * Code to be run before inserting to database
     *
     * @param ?PropelPDO $con
     *
     * @return boolean
     */
    public function preInsert(?PropelPDO $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     *
     * @param ?PropelPDO $con
     */
    public function postInsert(?PropelPDO $con = null)
    {
    }

    /**
     * Code to be run before updating the object in database
     *
     * @param ?PropelPDO $con
     *
     * @return boolean
     */
    public function preUpdate(?PropelPDO $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     *
     * @param ?PropelPDO $con
     */
    public function postUpdate(?PropelPDO $con = null)
    {
    }

    /**
     * Code to be run before deleting the object in database
     *
     * @param ?PropelPDO $con
     *
     * @return boolean
     */
    public function preDelete(?PropelPDO $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     *
     * @param ?PropelPDO $con
     */
    public function postDelete(?PropelPDO $con = null)
    {
    }

    /**
     * Code to be run after object hydration
     *
     * @param array $row
     * @param int $startcol
     * @param bool $rehydrate
     */
    public function postHydrate($row, $startcol = 0, $rehydrate = false)
    {
    }

    /**
     * Sets the modified state for the object to be false.
     *
     * @param string $col If supplied, only the specified column is reset.
     *
     * @return self
     */
    public function resetModified($col = null)
    {
        if ($col !== null) {
            while (($offset = array_search($col, $this->modifiedColumns)) !== false) {
                array_splice($this->modifiedColumns, $offset, 1);
            }
        } else {
            $this->modifiedColumns = array();
        }

        return $this;
    }

    /**
     * Compares this with another <code>BaseObject</code> instance.  If
     * <code>obj</code> is an instance of <code>BaseObject</code>, delegates to
     * <code>equals(BaseObject)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param object obj The object to compare to.
     *
     * @return bool Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclass = get_class($this);
        if (is_object($obj) && $obj instanceof $thisclass) {
            if ($this === $obj) {
                return true;
            } elseif ($this->getPrimaryKey() === null || $obj->getPrimaryKey() === null) {
                return false;
            } else {
                return ($this->getPrimaryKey() === $obj->getPrimaryKey());
            }
        } else {
            return false;
        }
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
     * @param string $name The virtual column name
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
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException('Cannot get value of inexistent virtual column ' . $name);
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return BaseObject The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param string $msg
     * @param int    $priority One of the Propel::LOG_* logging levels
     *
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
     * @param mixed $parser A PropelParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return BaseObject The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof PropelParser) {
            $parser = PropelParser::getParser($parser);
        }

        return $this->fromArray($parser->toArray($data), BasePeer::TYPE_PHPNAME);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed   $parser                 A PropelParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     *
     * @return string The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof PropelParser) {
            $parser = PropelParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
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
     * Catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you use a custom BaseObject
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     *
     * @throws PropelException
     */
    public function __call($name, $params)
    {
        if (preg_match('/get(\w+)/', $name, $matches)) {
            $virtualColumn = $matches[1];
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }
        if (preg_match('/^from(\w+)$/', $name, $matches)) {
            return $this->importFrom($matches[1], reset($params));
        }
        if (preg_match('/^to(\w+)$/', $name, $matches)) {
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($matches[1], $includeLazyLoadColumns);
        }
        throw new PropelException('Call to undefined method: ' . $name);
    }
}
