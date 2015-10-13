<div class='login-edit'>
<h1><?= t('Login') ?></h1>
<p><?= t('Login using your acronym or email.') ?></p>
<?=$login_form->GetHTML(array('start'=>true))?>
  <fieldset>
    <?=$login_form['acronym']->GetHTML()?>
    <?=$login_form['password']->GetHTML()?>  
    <?=$login_form['login']->GetHTML()?>
    <?php if($allow_create_user) : ?>
            <p class='form-action-link'><a href='<?=$create_user_url?>' title='Create a new user account'><?= t('Create user') ?></a></p>
    <?php endif; ?>
  </fieldset>
</form>
</div>


