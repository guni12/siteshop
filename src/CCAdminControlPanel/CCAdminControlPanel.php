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

    public function Lists() {
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
        $if = new CInterceptionFilter();
        $access = $if->AdminOrForbidden();
        $this->views->SetTitle(t('ACP: Admin Control Panel'))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/index.tpl.php', array(
                    'blogs' => $this->content->ListAll(array('type' => 'post', 'order-by' => 'id', 'order-order' => 'DESC')),
                    'home' => $this->content->ListAll(array('type' => 'home', 'order-by' => 'title')),
                    'byline' => $this->content->ListAll(array('type' => 'byline', 'order-by' => 'title')),
                    'users' => $this->user->ListAllUsers(),
                    'groups' => $this->user->ListAllGroups(),
                    'joins' => $this->connections,
                    'header1' => t('Here you can create and edit'),
                    'header2' => t('The blogs'),
                    'header3' => t('The pages'),
                    'header4' => t('Actions'),
                    'header5' => t('The users'),
                    'header6' => t('The groups'),
                    'text' => t('a user or group or edit content in pages and blog. You can also delete the whole guestbook if needed.'),
                    'text2' => t('Create new content'),
                    'acronym' => t('Acronym'),
                    'name' => t('Name'),
                    'algoritm' => t('Algorithm'),
                    'created' => t('Created'),
                    'updated' => t('Updated'),
                    'memberedit' => t('Edit member'),
                    'joinedit' => t('Edit joins'),
                    'groups2' => t('Groups'),
                    'edit' => t('Edit'),
                    'groupedit' => t('GroupEdit'),
                ))
                ->AddIncludeToRegion('sidebar', __DIR__ . '/sidebar.tpl.php', array(
                    'guestbook' => $this->guestbook,
                    'user' => $this->user,
                    'footers' => $this->content->ListAll(array('type' => 'footer', 'order-by' => 'id')),
                    'secret1' => $this->content->ListAll(array('type' => 'secret1')),
                    'secret2' => $this->content->ListAll(array('type' => 'secret2')),
                    'header5' => t('The secrets'),
                    'header1' => t('The guestbook can be deleted here:'),
                    'header2' => t('Create a new member'),
                    'header3' => t('Create a new group'),
                    'header4' => t('Initiate the database here:'),
                    'text1' => t('Delete the guestbook'),
                    'text2' => t('Create user'),
                    'text3' => t('Create group'),
                    'text4' => t('Init database, create tables and create default admin user'),
        ));
    }

    public function CreateUser() {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();

        $form = new CForm(array(), array(
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
            $this->AddMessage('notice', t('The CreateUserForm could not be processed.'));
            $this->RedirectToController('createuser');
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }

        $this->views->SetTitle(t('Create user'))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => null,
                    'mainHeader' => t('Create user'),
                    'header1' => t('Create a new user here:'),
                    'header2' => null,
        ));
    }

    public function CreateContent($id = null) {
        $if = new CInterceptionFilter();
        $if->AdminOrForbidden();

        $filter = array('plain' => 'plain', 'bbcode' => 'bbcode', 'htmlpurify' => 'htmlpurify', 'markdown' => 'markdown', 'markdown_x' => 'markdown_x', 'markdown_x_smart' => 'markdown_x_smart');
        $type = array('page' => 'page', 'home' => 'home', 'post' => 'post', 'footer' => 'footer', 'byline' => 'byline', 'secret1' => 'secret1', 'secret2' => 'secret2');

        $thispost = $this->content->AdminLoad($id);

        $save = isset($thispost['id']) ? 'Save' : 'Create';

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
                'label' => 'Filter',
                'validation' => array('not_empty'),
            ),
            'doCreateContent' => array(
                'type' => 'submit',
                'space' => true,
                'value' => t($save),
                'callback' => function($f) {
                    return $this->content->AdminSave($f->Value('id'), $f->Value('key'), $f->Value('type'), $f->Value('title'), $f->Value('data'), $f->Value('filter'));
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
            $this->AddMessage('notice', t('The content could not be created.'));
            $this->RedirectToController('createcontent');
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }

        $title = isset($id) ? t('Edit') : t('Create');
        $this->views->SetTitle($title . t('content: ') . htmlEnt($this->content['title']))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => null,
                    'mainHeader' => t('Create content'),
                    'header1' => t('Create new page here'),
                    'header2' => null,))
                ->AddClassToRegion('sidebar', 'acp')
                ->AddIncludeToRegion('sidebar', __DIR__ . '/sideedit.tpl.php', array(
                    'bold1' => t('Title'),
                    'bold2' => t('Key'),
                    'bold3' => t('Content'),
                    'bold4' => t('Type'),
                    'bold5' => t('Filter'),
                    'text1' => t('This is the clickable link to your post from the archive.'),
                    'text2' => t('Make a unique key for the post.'),
                    'text3' => t('You can write using various filters.'),
                    'text4' => t('post - a blogpost'),
                    'text5' => t('home - the static homepage'),
                    'text6' => t('page - any contentpage.'),
                    'text7' => t('is simple, but remember that the computer does not recognise how you write the text in the textarea - it just adds all text in a long row.'),
                    'text8' => t('will recognise a new line in your textarea. You can also use'),
                    'text9' => t('if you like.'),
                    'text10' => t('is a plugin-filter to use with normal html-tags such as'),
                    'text11' => t('etc. You cannot use java script with it.'),
                    'text12' => t('all the markdown filters need you to learn a special syntax. Try to write'),
                    'text13' => t('To include a photo use this:'),
                    'text14' => t('The above code is where the image is going to be placed and the address for the image can be written anywhere in your textarea.'),
                    'text15' => t('Title'),
                    'text16' => t('or place your image somewhere else in your tree and refer to that.'),
                    'text17' => t('stands for'),
                    'text18' => t('and the last filter also include the plugin typographer.'),
                    'text19' => t('You can study how to write in this way at'),
                    'text20' => t('and at'),
                ))
        ;
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
            $this->AddMessage('notice', t('The groupcreateform could not be processed.'));
            $this->RedirectToController('creategroup');
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }


        $this->views->SetTitle(t('Create group'))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => null,
                    'mainHeader' => t('Create'),
                    'header1' => t('Create group  here:'),
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
            $this->AddMessage('notice', t('The groupeditform could not be processed.'));
            $this->RedirectToController('groupedit', $id);
        } else if ($status === true) {
            $this->RedirectTo('acp');
        }


        $this->views->SetTitle(t('Update group'))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'user' => $thisgroup,
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => null,
                    'mainHeader' => t('Edit'),
                    'header1' => t('Edit name or acronym  here:'),
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
            $this->AddMessage('notice', t('The editform could not be processed.'));
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



        $this->views->SetTitle(t('Update member: '))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'user' => $thisuser,
                    'form' => $form->GetHTML(array('class' => 'admin-edit')),
                    'form2' => $passwordform->GetHTML(array('class' => 'admin-edit')),
                    'mainHeader' => t('Edit'),
                    'header1' => t('Edit name, acronym or email-address here:'),
                    'header2' => t('Edit password here'),
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
            $this->AddMessage('notice', t('The group could not be chosen.'));
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
            $this->AddMessage('notice', t('The group could not be chosen.'));
            $this->RedirectToController('edit', $id);
        } else if ($status4 === true) {
            $this->RedirectTo('acp');
        }



        $this->views->SetTitle(t('Update member: '))
                ->AddClassToRegion('primary', 'acp')
                ->AddIncludeToRegion('primary', __DIR__ . '/edit.tpl.php', array(
                    'user' => $thisuser,
                    'form' => $outOfForm->GetHTML(array('class' => 'admin-edit')),
                    'form2' => $enterForm->GetHTML(array('class' => 'admin-edit')),
                    'mainHeader' => t('Edit groupmembership'),
                    'header1' => t('Out from group'),
                    'header2' => t('Enter group'),
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
