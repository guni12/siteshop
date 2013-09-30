<h1>Index Controller</h1>
<p>This is what you can do for now.</p>
<!--<?=print_r($menu);// Array ( [0] => index [1] => developer [2] => developer/displayobject 
//[3] => developer/links [4] => guestbook [5] => guestbook/handler [6] => user [7] => user/create 
//[8] => user/docreate [9] => user/profile [10] => user/dochangepassword [11] => user/doprofilesave 
//[12] => user/login [13] => user/dologin [14] => user/logout [15] => user/init [16] => acp ) 1?>-->
<?php foreach($menu as $val): ?>
<li><a href='<?=create_url($val)?>'><?=$val?></a>  
<?php endforeach; ?>		
