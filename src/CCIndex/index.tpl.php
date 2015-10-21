<?php if (file_exists('application/data/installed.txt')): ?>
    <div id='headcolor'>
        <h2><?= t('Welcome to Siteshop index controller.') ?></h2>
    </div>
    <p><?= t('This is the index page with all the controllers') ?></p>
    <h2><?= t('Download') ?></h2>
    <p><?= t('You can download Siteshop from github.') ?></p>
    <blockquote>
        <code>git clone git://github.com/guni12/siteshop.git</code>
    </blockquote>
    <p><?= t('You can review its source directly on github: ') ?><br /><a href='https://github.com/guni12/siteshop'>https://github.com/guni12/siteshop</a></p>

<p><?= t('Now you are ready to check this MVC out.')?></p><br />
<p><?= t('You can always go back to the startup-page by clicking on the startup-link to the right.')?></p>

<a href='<?= base_url() ?>source.php'>Kolla källkoden för detaljer</a>

<? else: ?>

    <div id='headcolor'>
        <h2><?= t('Welcome to Siteshop framework.') ?></h2>
    </div>
<? endif; ?>
<p><?= t('If your server is apache - these modules are enabled:') ?></p>
<pre>
<?php
if (!function_exists('apache_get_modules')){
    print_r('Your server is not apache');
    }else{
    print_r(apache_get_modules());
    $modules = apache_get_modules();
    echo in_array('mod_rewrite', $modules) ? "mod_rewrite module is enabled" : "mod_rewrite module is not enabled";

}
?>
</pre>
