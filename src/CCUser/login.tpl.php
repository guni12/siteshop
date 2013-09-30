<h1>Login</h1>
<p>Login using your acronym or email.</p>
<?=$login_form->GetHTML('form')?>
  <fieldset>
    <?=$login_form['acronym']->GetHTML()?>
    <?=$login_form['password']->GetHTML()?>  
    <?=$login_form['login']->GetHTML()?>
    <?php if($allow_create_user) : ?>
	<!--var_dump($create_user_url);?>C:\wamp\www\bth\siteshop\src\CCUser-->
	<!--print_r($create_user_url);?>string 'http://localhost/bth/siteshop/user/create' (length=41)-->
      <p class='form-action-link'><a href='<?=$create_user_url?>' title='Create a new user account'>Create user</a></p>
    <?php endif; ?>
  </fieldset>
</form>