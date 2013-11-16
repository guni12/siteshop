<?php

/**
 * Admin Control Panel to manage admin stuff.
 * 
 * @package SiteshopCore
 */
class CCAdminControlPanel extends CObject implements IController {

    /**
     * properties
     */   
    protected $content;
    protected $guestbook;
    protected $user;
    protected $connections;
    private $nrOfUsers;
    private $nrOfGroups;
    private $groupnames;
    private $memberGroups;
    private $restOfGroups;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->content = new CMContent();
        $this->guestbook = new CMGuestbook();
        $this->user = new CMUser();
        $this->Lists();
    }
    
    public function Lists(){
        $list = $this->user->ListAllUsers();
        $this->nrOfUsers = count($list);
        
        $groups = $this->user->ListAllGroups();
        $this->nrOfGroups = count($groups);
        //var_dump($groups);
        
        $userindexer = 1;

        for ($i = 0; $i < $this->nrOfUsers; $i++) {
            $this->connections[$i] = $this->user->db->ExecuteSelectQueryAndFetchAll($this->user->SQL('get group memberships'), array($userindexer));
            $userindexer++;
        }
        
        $temp;
        $temp2;
        
         for ($i = 0; $i < $this->nrOfGroups; $i++) {
            $temp[$i] = $groups[$i]['name'];
        }
        
         
         for ($i = 0; $i < $this->nrOfGroups; $i++) {
            $temp2[$i] = $groups[$i]['id'];
        }
        
         for ($i = 0; $i < $this->nrOfGroups; $i++) {
            $id = $temp2[$i];
            $name = $temp[$i];
            $this->groupnames[$id] = $name;
        }
        
       //var_dump($this->groupnames);

    }

    /**
     * Show profile information of the user.
     */
    public function Index() {
      $html = '<ul>
          <li>A little test here</li><li>One</li><li>Two</li><li>Three</li><li>Four</li><li>Five</li></ul>';
        $this->views->SetTitle('ACP: Admin Control Panel')
        ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/index.tpl.php', array(

            'blogs' => $this->content->ListAll(array('type' => 'post', 'order-by' => 'id', 'order-order' => 'DESC')),
            'home' => $this->content->ListAll(array('type' => 'home', 'order-by' => 'title')),
            'users' => $this->user->ListAllUsers(),
            'groups' => $this->user->ListAllGroups(),
            'joins' => $this->connections,
            'guestbook' => $this->guestbook,
            'user' => $this->user,                    
        ))
            ->AddIncludeToRegion('sidebar', __DIR__ . '/sidebar.tpl.php', array('text'=>$html));
    }

    public function CreateUser() {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();

        $form =  new CForm(array(), array(
            'acronym' => array(
                'type' => 'text',
                'autofocus' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'password1' => array(
                'type' => 'password',
                'label' => t('Password:'),
                'required' => true,
                'autofocus' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'password2' => array(
                'type' => 'password',
                'label' => t('Password again:'),
                'required' => true,
                'validation' => array('not_empty', 'match' => 'password1'),
            ),
            'name' => array(
                'type' => 'text',
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'email' => array(
                'type' => 'text',
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'create' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t('DoCreate'),
                'callback' => function($f) {
                    
                    return CSiteshop::Instance()->user->Create($f->Value('acronym'), $f->Value('password1'), $f->Value('name'), $f->Value('email'));
                }
            ),
                )
        );

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', 'The CreateUserForm could not be processed.');
            $this->RedirectToController('createuser');
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }
            
        $this->views->SetTitle("Create user")
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => null,
                    'mainHeader' => 'Create',
                    'header1' => 'Create a new user here:',
                    'header2' => null,
        ));
    }
    
    public function CreateContent($id = null){
         $if = new CInterceptionFilter();
        $if->AdminOrForbidden();
        
        $filter = array('plain'=>'plain', 'bbcode'=>'bbcode', 'htmlpurify'=>'htmlpurify', 'markdown'=>'markdown', 'markdown_x'=>'markdown_x', 'markdown_x_smart'=>'markdown_x_smart');
        $type = array('page'=>'page','home'=>'home' ,'post'=>'post' );

        $thispost = $this->content->AdminLoad($id);

        $save = isset($thispost['id']) ? 'save' : 'create';
        
        $form = new CForm(array(), array(
            'id' => array(
                'type' => 'hidden',
                'value' => $thispost['id'],
            ),
            'title' => array(
                'type' => 'text',
                'autofocus' => true,
                'label' => t('Title:'),
                'value' => $thispost['title'],
                'validation' => array('not_empty'),
            ),
            'key' => array(
                'type' => 'text',
                'label' => t('Key'),
                'value' => $thispost['key'],
                'validation' => array('not_empty'),
            ),
            'data' => array(
                'type' => 'textarea',
                'label' => t('Content:'),
                'value' => $thispost['data'],
                'validation' => array('not_empty'),
            ), 
            'type' => array(
                'type' => 'select',
                'readonly' => true,
                'options' => $type,
                'value' => $thispost['type'],
                'label' => t('Type:'),
            ),
            'filter' => array(
                'type' => 'select',
                'readonly' => true,
                'options' => $filter,
                'value' => $thispost['filter'],
                'label' => t('Key'),
                'validation' => array('not_empty'),
            ),
            'doCreateContent' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t($save),
                'callback' => function($f) {
                    return $this->content->AdminSave($f->Value('id'), $f->Value('key'), $f->Value('type'), $f->Value('title'), $f->Value('data'), $f->Value('filter'), $filter, $type);
                }
            ),
                     'doDeleteContent' => array(
                'type' => 'submit',
                'value' => t('Delete'),
                'callback' => function($f) {
                    return $this->content->AdminDelete($f->Value('id'), $f->Value('key'));
                }
            ),
                )
        );

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', 'The content could not be created.');
            $this->RedirectToController('createcontent');
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }
        
        $title = isset($id) ? 'Edit' : 'Create';
         $this->views->SetTitle("$title content: " . htmlEnt($this->content['title']))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => null,
                    'mainHeader' => 'Create content',
                    'header1' => 'Create new page here:',
                    'header2' => null,
        ));
    }

    /**
     * Form to create a new group.
     *
     *   
     */
    public function CreateGroup() {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();

        $form = new CForm(array(), array(
            'acronym' => array(
                'type' => 'text',
                'autofocus' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'name' => array(
                'type' => 'text',
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'doUpdateGroups' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t('Create'),
                'callback' => function($f) {
                    return CSiteshop::Instance()->user->CreateGroup($f->Value('acronym'), $f->Value('name'));
                }
            ),
                )
        );

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', 'The groupcreateform could not be processed.');
            $this->RedirectToController('creategroup');
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }


        $this->views->SetTitle("Create group")
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => null,
                    'mainHeader' => 'Create',
                    'header1' => 'Create group  here:',
                    'header2' => null,
                    
        ));
    }

    /**
     * Edit a selected member.
     *
     * @param id integer the id of the member.
     */
    public function Groupedit($id = null) {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();
        
        $thisgroup = $this->user->GetGroupsById($id);

        $form = new CForm(array(), array(
            'id' => array(
                'type' => 'text',
                'value' => $thisgroup['id'],
                'label' => t('Group id:'),
                'readonly' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'acronym' => array(
                'type' => 'text',
                'value' => $thisgroup['acronym'],
                'autofocus' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'name' => array(
                'type' => 'text',
                'value' => $thisgroup['name'],
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'doUpdateGroups' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t('Update'),
                'callback' => function($f) {
                    return CSiteshop::Instance()->user->UpdateGroups($f->Value('acronym'), $f->Value('name'), $f->Value('id'));
                }
            ),
            'deleteThis' => array(
                'type' => 'submit',
                'value' => t('Delete'),
                'callback' => function($f) {
                    return CSiteshop::Instance()->user->DeleteGroup($f->Value('acronym'), $f->Value('id'));
                }
            ),
                )
        );

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', 'The groupeditform could not be processed.');
            $this->RedirectToController('groupedit', $id);
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }


        $this->views->SetTitle("Update group")
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'user' => $thisgroup,
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => null,
                    'mainHeader' => 'Edit',
                    'header1' => 'Edit name or acronym  here:',
                    'header2' => null,
               
        ));
    }

    /**
     * Edit a selected member.
     *
     * @param id integer the id of the member.
     */
    public function Edit($id = null) {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();
        
        $thisuser = $this->user->GetMemberById($id);

        $form = new CForm(array(), array(
            'id' => array(
                'type' => 'text',
                'value' => $thisuser['id'],
                'label' => t('Member id:'),
                'readonly' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'acronym' => array(
                'type' => 'text',
                'value' => $thisuser['acronym'],
                'autofocus' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'name' => array(
                'type' => 'text',
                'value' => $thisuser['name'],
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'email' => array(
                'type' => 'text',
                'value' => $thisuser['email'],
                'label' => t('New email:'),
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'doUpdateNames' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t('Update'),
                'callback' => function($f) {
                    return CSiteshop::Instance()->user->Update($f->Value('acronym'), $f->Value('name'), $f->Value('email'), $f->Value('id'));
                }
            ),
            'deleteThis' => array(
                'type' => 'submit',
                'value' => t('Delete'),
                'callback' => function($f) {
                    return CSiteshop::Instance()->user->DeleteMember($f->Value('acronym'), $f->Value('id'));
                }
            ),
                )
        );

        $status = $form->Check();
        if ($status === false) {
            $this->AddMessage('notice', 'The editform could not be processed.');
            $this->RedirectToController('edit', $id);
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }


        $passwordform = new CForm(array(), array(
            'password1' => array(
                'type' => 'password',
                'value' => $thisuser['password'],
                'label' => t('Current password:'),
                'readonly' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'password2' => array(
                'type' => 'password',
                'label' => t('New password:'),
                'autofocus' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
            'password3' => array(
                'type' => 'password',
                'label' => t('New password again:'),
                'required' => true,
                'validation' => array('not_empty', 'match' => 'password2'),
            ),
            'acronym' => array(
                'type' => 'text',
                'readonly' => true,
                'value' => $thisuser['acronym'],
            ),
            'id' => array(
                'type' => 'text',
                'readonly' => true,
                'value' => $thisuser['id'],
                'label' => t('Member id:'),
            ),
            'doChange' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t('Change password'),
                'callback' => function($f) {
                    return CSiteshop::Instance()->user->ChangePasswordAdmin($f->Value('password3'), $f->Value('id'));
                }
            ),
                )
        );

        $status2 = $passwordform->Check();
        if ($status2 === false) {
            $this->AddMessage('notice', 'The password could not be changed, ensure that all fields match and the current password is correct.');
            $this->RedirectToController('edit', $id);
        } else if ($status2 === true) {
            $this->AddMessage('success', ('Saved new password.'));
            $this->RedirectTo('acp');
        }
        

        
        $this->views->SetTitle("Update member: ")
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'user' => $thisuser,
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => $passwordform->GetHTML(array('class' => 'admin-edit')),
                    'mainHeader' => 'Edit',
                    'header1' => 'Edit name, acronym or email-address here:',
                    'header2' => 'Edit password here',
        ));
    }
    
     /**
     * Edit a selected member.
     *
     * @param id integer the id of the member.
     */
    public function JoinEdit($id = null) {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();
        
        $thisuser = $this->user->GetMemberById($id);
        
        $pointer = $thisuser['id'] - 1;
        //var_dump($this->connections);
        
        $membersGroupnames;
        $theGroupids;
        $groups = null;
        
        // get the groupnames for this user (= $pointer)
        for ($i = 0; $i < $this->nrOfGroups; $i++) {
            if (!isset($this->connections[$pointer][$i])) {
                break;
            } else {
                $membersGroupnames[$i] = $this->connections[$pointer][$i]['name'];         
            }
        }
        
        for ($i = 0; $i < $this->nrOfGroups; $i++) {
            if (!isset($this->connections[$pointer][$i])) {
                break;
            } else {
                $theGroupids[$i] = $this->connections[$pointer][$i]['idGroups'];         
            }
        }
        
        //var_dump($membersGroupnames);
        //var_dump($theGroupids);
        $tempcount = count($membersGroupnames);
        
        for ($i = 0; $i < $tempcount; $i++) {
            $listid = $theGroupids[$i];
             $name = $membersGroupnames[$i];
            $this->memberGroups[$listid] = $name;
        }

        $this->restOfGroups = array_diff($this->groupnames, $this->memberGroups);
        //echo '$this->memberGroups<br />';
        //var_dump($this->memberGroups);
        //echo '$this->groupnames<br />';
        //var_dump($this->groupnames);
        //echo 'rest of groups<br />';
        //var_dump($this->restOfGroups);
        
        $outOfForm = new CForm(array(), array(
            'idUser' => array(
                'type' => 'text',
                'value' => $thisuser['id'],
                'label' => t('idUser:'),
                'readonly' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
             'name' => array(
                'type' => 'text',
                'value' => $thisuser['name'],
                'label' => t('Name:'),
                'readonly' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
              'groupChoice' => array(
                'type' => 'select',
                'readonly' => true,
                'options' => $this->memberGroups,
                  'value' => 'name',
                'label' => t('Choose Group to delete:'),
            ),   
            'groupEdit' => array(
                'type' => 'submit',
                'value' => t('Delete Group'),
                'callback' => function($f) {
                    return CSiteshop::Instance()->user->OutOfGroup($f->Value('idUser'), $f->Value('groupChoice'));
                }
            ),
)
                 );
            
             $status3 = $outOfForm->Check();
        if ($status3 === false) {
            $this->AddMessage('notice', 'The groups could not be chosen.');
            $this->RedirectToController('joinedit', $id);
        } else if ($status3 === true) {
            $this->RedirectTo('acp');
        }

         $enterForm = new CForm(array(), array(
             'idUser' => array(
                'type' => 'hidden',
                'value' => $thisuser['id'],
                'label' => t('idUser:'),
                'readonly' => true,
                'required' => true,
                'validation' => array('not_empty'),
            ),
                 'groupChoice2' => array(
                'type' => 'select',
                'readonly' => true,
                'options' => $this->restOfGroups,
                  'value' => 'name',
                'label' => t('Choose Group to add:'),
            ),   
            'groupAdding' => array(
                'type' => 'submit',
                'value' => t('Add Group'),
                'callback' => function($f) {
                    return CSiteshop::Instance()->user->AddAGroup($f->Value('idUser'), $f->Value('groupChoice2'));
                }
            ),
)
                 );
            
        $status4 = $enterForm->Check();
        if ($status4 === false) {
            $this->AddMessage('notice', 'The groups could not be chosen.');
            $this->RedirectToController('edit', $id);
        } else if ($status4 === true) {
            $this->RedirectTo('acp');
        }
        

        
         $this->views->SetTitle("Update member: ")
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'user' => $thisuser,
                    'form' => $outOfForm->GetHTML(array('class' => 'admin-edit')),
                    'form2' => $enterForm->GetHTML(array('class' => 'admin-edit')),
                    'mainHeader' => 'Edit groupmembership',
                    'header1' => 'Out from group',
                    'header2' => 'Enter group',

        ));
    }

    /**
     * Deletes the guestbook
     *
     * 
     */
    public function Deleteguestbook() {
        $guestbook = new CMGuestbook();
        $guestbook->DeleteAll();
        $this->RedirectTo('acp');
    }

}
