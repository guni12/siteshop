<?php
/**
* A model for content stored in database.
*
* @package SiteshopCore
*/
class CMContent extends CObject implements IHasSQL, ArrayAccess {

  /**
* Properties
*/
  public $data;


  /**
* Constructor
*/
  public function __construct($id=null) {
    parent::__construct();
    if($id) {
      $this->LoadById($id);
    } else {
      $this->data = array();
    }
  }


  /**
* Implementing ArrayAccess for $this->data
*/
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->data[$offset]); }
  public function offsetUnset($offset) { unset($this->data[$offset]); }
  public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }


  /**
* Implementing interface IHasSQL. Encapsulate all SQL used by this class.
*
* @param string $key the string that is the key of the wanted SQL-entry in the array.
*/
    public static function SQL($key=null, $args=null) {
        $order_order = isset($args['order-order']) ? $args['order-order'] : 'ASC';
        $order_by = isset($args['order-by']) ? $args['order-by'] : 'id'; 
        $queries = array(
            'drop table content' => "DROP TABLE IF EXISTS Content;",
            'create table content' => "CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, filter TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
            'insert content' => 'INSERT INTO Content (key,type,title,data,filter,idUser) VALUES (?,?,?,?,?,?);',
            'select * by id' => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=?;',
            'select * by key' => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=?;',
            'select * by type' => "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? ORDER BY {$order_by} {$order_order};",
            'select *' => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id;',
            'update content' => "UPDATE Content SET key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
     );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }



  /**
* Init the database and create appropriate tables.
*/
  public function Init() {
$html_text = <<<EOD
Hi there
---------  
<p style ='color:orange'>To compare, three posts have the same content:</p>  
We can write __thick__ text or _thin_    
a [link](http://a-link.se/ 'Hurra')  with alt-text  
Exemple on link with index: [behovsbo][1]  
    ![gris](http://www.student.bth.se/~guni12/phpmvc/siteshop/themes/core/pig.jpg 'My logo')
  
*   Apa
    * antal
*   Björn
    1.  brunbjörn
    2.  isbjörn
        * MILJÖ
    3. tvättbjörn
*   Chimpans  

[1]: http://www.behovsbo.se

* * *
- - - - 
> Email-style angle brackets
>are used for blockquotes.
> > And, they can be nested.
*****
<p style ='color:orange'>With typography the following ought to be different:</p>      
"We want a quotation"  
--And a dash!   
We can test ellips and then...
*******  
<p style ='color:orange'>Here some text suited for MarkdownExtra:</p>
Apple
:   Pomaceous fruit of plants of the genus Malus in 
    the family Rosaceae.

Orange
:   The fruit of an evergreen tree of the genus Citrus.
        
| Item      | Value |
| --------- | -----:|
| Computer  | $1600 |
| Phone     |   $12 |
| Pipe      |    $1 |
        
Header level 1 {#id1}
=====================  
Here comes a paragraph. 
        
* Unordered list
* Unordered list again
        
Header level 2 {#id2}
---------------------  
Here comes another paragraph, now intended as blockquote.  
        
1. Ordered list
2. Ordered list again
        
> This should be a blockquote.

###Header level 3 {#id3}

Here will be a table.

| Header 1 | Header 2     | Header 3 | Header 4      |
|----------|:-------------|:--------:|--------------:|
| Data 1   | Left aligned | Centered | Right aligned |
| Data     | Data         | Data     | Data          |

Here is a paragraph with some **bold** text and some *italic* text and a [link to dbwebb.se](http://dbwebb.se).

EOD;
    try {
      $this->db->ExecuteQuery(self::SQL('drop table content'));
      $this->db->ExecuteQuery(self::SQL('create table content'));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', "This is a demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', "This is another demo post.\n\nThis is another row in this demo post.\n\nThis is where I work: www.opera.se", 'plain', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', "This is one more demo post.\n\nThis is another row in this demo post.\n\nCheckout this site: http://www.behovsbo.se.", 'plain', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('home', 'page', 'Home page', "This is a demo page, this could be your personal home-page.\n\nSiteshop (based on Lydia) is a PHP-based MVC-inspired Content management Framework, watch the making of Lydia at: http://dbwebb.se/lydia/tutorial.", 'plain', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('about', 'page', 'About page', "This is a demo page, this could be your personal about-page.\n\nSiteshop (based on Lydia) is used as a tool to educate in MVC frameworks.", 'plain', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('download', 'page', 'Download page', "This is a demo page, this could be your personal download-page.\n\nYou can download your own copy of siteshop from https://github.com/guni12/siteshop.", 'plain', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('bbcode', 'page', 'Page with BBCode', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://www.student.bth.se/~guni12]a link to /~guni12[/url]. You can also include images using bbcode, such as the siteshop logo: [img]http://www.student.bth.se/~guni12/phpmvc/siteshop/themes/core/pig.jpg[/img]", 'bbcode', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('htmlpurify', 'page', 'Page with HTMLPurifier', "This is a demo page with some HTML code intended to run through <a href='http://htmlpurifier.org/'>HTMLPurify</a>. Edit the source and insert HTML code and see if it works.\n\n<b>Text in bold</b> and <i>text in italic</i> and <a href='http://www.student.bth.se/~guni12'>a link to /~guni12</a>. JavaScript, like this: <javascript>alert('hej');</javascript> should however be removed.\n\n<p style='color:orange'>En test av några css-element: <code>(\$content['id'])</code> för att se hur det tar sig ut.</p><h1>En ny <span style='color:red'>Rubrik</span> också</h1>", 'htmlpurify', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('markdown', 'page','Page with Markdown', $html_text, 'markdown', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('markdown_x', 'page', 'Page with MarkdownExtra', $html_text, 'markdown_x', $this->user['id']));
      $this->db->ExecuteQuery(self::SQL('insert content'), array('markdown_x_smart', 'page', 'Page with MarkdownExtra plus Typographer', $html_text, 'markdown_x_smart', $this->user['id']));
      $this->AddMessage('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
    } catch(Exception$e) {
      die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
    }
  }
  

  /**
* Save content. If it has an id, use it to update current entry or else insert new entry.
*
* @returns boolean true if success else false.
*/
    public function Save() {
        $msg = null;
        if($this['id']) {
            $this->db->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this['id']));
            $msg = 'update';
        } else {
            $this->db->ExecuteQuery(self::SQL('insert content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this->user['id']));
            $this['id'] = $this->db->LastInsertId();
            $msg = 'created';
        }
        $rowcount = $this->db->RowCount();
        if($rowcount) {
            $this->AddMessage('success', "Successfully {$msg} content '" . htmlEnt($this['key']) . "'.");
        } else {
            $this->AddMessage('error', "Failed to {$msg} content '" . htmlEnt($this['key']) . "'.");
        }
        return $rowcount === 1;
    }


  /**
* Load content by id.
*
* @param id integer the id of the content.
* @returns boolean true if success else false.
*/
  public function LoadById($id) {
    $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
    if(empty($res)) {
      $this->AddMessage('error', "Failed to load content with id '$id'.");
      return false;
    } else {
      $this->data = $res[0];
    }
    return true;
  }
  
  
  /**
* List all content.
*
* @param $args array with various settings for the request. Default is null.
* @returns array with listing or null if empty.
*/
    public function ListAll($args=null) {
        try {
            if(isset($args) && isset($args['type'])) {
                return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by type', $args), array($args['type']));
            } else {
                return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select *', $args));
            }
        } catch(Exception $e) {
            echo $e;
            return null;
        }
    }
    
    
          /**
       * Filter content according to a filter.
       *
       * @param $data string of text to filter and format according its filter settings.
       * @returns string with the filtered data.
       */
    public static function Filter($data, $filter) {
        switch($filter) {
            /*case 'php': $data = nl2br(makeClickable(eval('?>'.$data))); break;
            case 'html': $data = nl2br(makeClickable($data)); break;*/
            //case 'markdown': $data = CTextFilter::Typographer(CTextFilter::Markdown($data)); break;
            case 'markdown': $data = CTextFilter::Markdown($data); break;
            //case 'htmlpurify': $data = nl2br(CTextFilter::Purify($data)); break;
            //case 'htmlpurify': $data = nl2br(CHTMLPurifier::Purify($data)); break;
            //case 'bbcode': $data = nl2br(bbcode2html(htmlEnt($data))); break;
            case 'bbcode': $data = nl2br(CTextFilter::Bbcode2HTML(htmlEnt($data))); break;
            case 'htmlpurify': $data = nl2br(CTextFilter::Purify(CHTMLPurifier::Purify($data))); break;
            case 'markdown_x': $data = CTextFilter::MarkdownExtra($data); break;
            case 'markdown_x_smart': $data = CTextFilter::Typographer(CTextFilter::MarkdownExtra($data)); break;
            case 'plain':
            //default: $data = nl2br(makeClickable(htmlEnt($data))); break;
            default: $data = CTextFilter::MakeClickable(htmlEnt($data)); break;
        }
        return $data;
    }

    
    /**
* Get the filtered content.
*
* @returns string with the filtered data.
*/
  public function GetFilteredData() {
    return $this->Filter($this['data'], $this['filter']);
  }
}
