<?php

/**
 * A model for a guestbok, to show off some basic controller & model-stuff.
 * 
 * @package SiteshopCore
 */
class CMGuestbook extends CObject implements IHasSQL, ArrayAccess, IModule {
    
    /**
     * Properties
     */
    public $entry;

   /**
     * Constructor
     */
    public function __construct($id = null) {
        parent::__construct();
        if ($id) {
            $this->LoadById($id);
        } else {
            $this->entry = array();
        }
    }
    
    
    /**
     * Implementing ArrayAccess for $this->data
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->entry[] = $value;
        } else {
            $this->entry[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->entry[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->entry[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->entry[$offset]) ? $this->entry[$offset] : null;
    }
    
    /**
     * Load guestbook by id.
     *
     * @param id integer the id of the content.
     * @returns boolean true if success else false.
     */
    public function LoadById($id) {
        $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select guestbook * by id'), array($id));
        var_dump($res);
        if (empty($res)) {
            $this->AddMessage('error', "Failed to load content with id '$id'.");
            return false;
        } else {
            $this->entry = $res[0];
            
        }
        return true;
    }
    
     /**
     * List all content.
     *
     * @param $args array with various settings for the request. Default is null.
     * @returns array with listing or null if empty.
     */
    public function ListAll($args = null) {
        try {           
                return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook', $args));
               
        } catch (Exception $e) {
            echo $e;
            return null;
        }
    }

    /**
     * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
     *
     * @param string $key the string that is the key of the wanted SQL-entry in the array.
     */
    public static function SQL($key = null) {
        $queries = array(
		'drop table guestbook' => "DROP TABLE IF EXISTS Guestbook;",
            'create table guestbook' => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now', 'localtime')), updated DATETIME default NULL, deleted DATETIME default NULL);",
            'insert into guestbook' => 'INSERT INTO Guestbook (entry) VALUES (?);',
            'select guestbook * by id' => 'SELECT * FROM Guestbook WHERE id=? AND deleted IS NULL;',
            'select * from guestbook' => 'SELECT * FROM Guestbook WHERE deleted IS NULL ORDER BY id DESC;',  
                                       
            'delete from guestbook' => 'DELETE FROM Guestbook;',
            'update guestbook' => "UPDATE Guestbook SET entry=?, updated=datetime('now', 'localtime') WHERE id=?;",
            'update guestbook as deleted' => "UPDATE Guestbook SET deleted=datetime('now', 'localtime') WHERE id=?;",
        );
        if (!isset($queries[$key])) {
            throw new Exception("No such SQL query, key '$key' was not found.");
        }
        return $queries[$key];
    }

    /**
     * Implementing interface IModule. Manage install/update/deinstall and equal actions.
     */
    public function Manage($action = null) {
        switch ($action) {
            case 'install':
                try {
				$this->db->ExecuteQuery(self::SQL('create table guestbook'));
                    $this->db->ExecuteQuery(self::SQL('create table guestbook'));
                    return array('success', 'Successfully created the database tables (or left them untouched if they already existed).');
                } catch (Exception$e) {
                    die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
                }
                break;

            default:
                throw new Exception('Unsupported action for this module.');
                break;
        }
    }
    
    /**
   * Add a new entry to the guestbook and save to database.
   */
  public function Add($entry) {
    $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
    $this->session->AddMessage('success', 'Successfully inserted new message.');
    if($this->db->rowCount() != 1) {
      die('Failed to insert new guestbook item into database.');
    }
  }

   /**
     * Save guestbook-content. If it has an id, use it to update current entry or else insert new entry.
     *
     * @returns boolean true if success else false.
     */
    public function UpdateOrCreate() {
        $msg = null;
        if ($this['id']) {
            $this->db->ExecuteQuery(self::SQL('update guestbook'), array($this['entry'], $this['id']));
            $msg = 'updated';
        }else{
            $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($this['entry']));
        $this['id'] = $this->db->LastInsertId();
            $msg = 'created';
        }
        $rowcount = $this->db->RowCount();
        if($rowcount){
            $this->session->AddMessage('success', "Successfully {$msg} a post.");
        }else{
            $this->session->AddMessage('error', "The guestbook was not {$msg}.");
        }
        if ($this->db->rowCount() != 1) {
            die('Failed to insert new guestbook item into database.');
        }
        return $rowcount === 1;
    }

    /**
     * Delete all entries from the guestbook and database.
     */
    public function DeleteAll() {
        $this->db->ExecuteQuery(self::SQL('delete from guestbook'));
        $this->session->AddMessage('info', 'Removed all messages from the guestbook table.');
    }
    
     /**
     * Delete content. Set its deletion-date to enable wastebasket functionality.
     *
     * @returns boolean true if success else false.
     */
    public function Delete() {
        if ($this['id']) {
            $this->db->ExecuteQuery(self::SQL('update guestbook as deleted'), array($this['id']));
        }
        $rowcount = $this->db->RowCount();
        if ($rowcount) {
            $this->AddMessage('success', "Successfully set your post as deleted.");
        } else {
            $this->AddMessage('error', "Failed to set guestbook post '" . htmlEnt($this['id']) . "' as deleted.");
        }
        return $rowcount === 1;
    }

    /**
     * Read all entries from the guestbook & database.
     */
    public function ReadAll() {
        try {
            return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
        } catch (Exception $e) {
            return array();
        }
    }

}

