<div id ='sidecolor'>
<p><?= t('The path to your application-directory is') ?>

<code><?= SITESHOP_APPLICATION_PATH . '/data' ?></code></p>
<p class ='smaller-text'><?= t('(this is defined in') ?> <code>index.php</code>)</p>

<p><?= t('First you have to make the data-directory writable. This is the place where Siteshop needs to be able to write and create files.') ?></p>
<?php
$is_directory = is_dir(SITESHOP_APPLICATION_PATH . '/data');
$is_writable = is_writable(SITESHOP_APPLICATION_PATH . '/data');
?> 

<?php if ($is_directory && $is_writable && $goodSofar): ?>

    <p class='success'><?= t('Success. The data directory exists and is writable.') ?></p>
    <?php $this->goodSidebar = true; ?>

<?php elseif ($is_directory): ?>

    <p class='error'><?= t('Failed. The data directory exists but it is NOT writable.') ?></p>

    <p><?= t('Correct this by changing the permissions on the directory.') ?></p>


        <code>cd siteshop; chmod 777 application/data</code><br />


<?php else: ?>

    <p class='error'><?= t('Failed. The data directory does NOT exist.') ?></p>

    <p><?= t('Are you sure that you have the correct SITESHOP_APPLICATION_PATH set in ') ?><code>index.php</code><?= t('? It is currently set to:') ?></p>

    <p><code><?= SITESHOP_APPLICATION_PATH ?></code></p>

    <p><?= t('Try to create the directory.') ?></p>


        <code>cd <?= SITESHOP_APPLICATION_PATH ?>; mkdir data; chmod 777 data</code><br />


<?php endif; ?>

<div id='well'>
    <h3><?= t('Verify that the default database is available') ?></h3>
</div>
    
<p><?= t('Lets check to see if we can connect to the default database.') ?></p>

<p><?= t('Your default database is (change this in ') ?><code>application/config.php</code>):</p>

<code><?= $dsn ?></code><br /><br />

<?php
global $ss;
$this->db_works = $ss->db === null ? false : true;
?> 

<?php if ($this->db_works): ?>

    <p class='success'><?= t('Great, I can connect to the database!') ?></p>
    

<?php else: ?>

    <p class='error'><?= t('Failed. I can not connect to the database. Review your database connection settings.') ?></p>

<?php endif; ?>

<?php if($goodSofar && $this->goodSidebar): ?>       
    <p><?= t('Second, Siteshop has some modules that need to be initialized.') ?></p>
    
    <p><?= t('When you have done that you can login as root/root for the administrator. At the acp page you can make the changes you prefer.')?></p>
    <p><?= t('Go back to the index page and start to get to know Siteshop, but first - point your browser to the following link. Enjoy!')?></p>
    
    <blockquote>
        <a href='<?= create_url('modules/install') ?>'>modules/install</a>
    </blockquote>
<?php endif; ?>
</div>