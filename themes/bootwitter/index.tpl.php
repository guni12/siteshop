<!doctype html>
    <html lang='en'>
    <head>
      <meta charset='utf-8'/>
      <title><?=$title?></title>
      <link rel='shortcut icon' href='<?=theme_url($favicon)?>'/>
      <link href='<?= $stylesheet ?>bootstrap.css' rel="stylesheet" media="screen">
      <?php if(isset($inline_style)): ?><style><?=$inline_style?></style><?php endif; ?>
    </head>    
    <body>
        <div class="container">
            <div class="row">
                <div class="span12">
                    <div class="navbar navbar-fluid-top navbar-inverse">
                        <div class="navbar-inner">
                            <a class="brand" href="<?=base_url()?>">Siteshop</a>
                            <div class="span8"><?= makeMenu() ?></div>                           
                            <div class='span1'><?=login_menu()?></div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span2"><a href='<?=base_url()?>'><img id='site-logo' src='<?=theme_url($logo)?>' alt='logo' width='<?=$logo_width?>' height='<?=$logo_height?>' /></a></div>
                        <div class="span10 page-header"><h1><a href='<?= base_url() ?>'><?= $header ?></a> <small><?= $slogan ?></small></h1></div>             
                    </div>
                </div>
            </div>       
    <?php if(region_has_content('flash')): ?>
        <div class="row-fluid">
            <div class='span4 offset3 flash'>
                <div id='flash'><?=render_views('flash')?></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(region_has_content('featured-first', 'featured-middle', 'featured-last')): ?>
        <div class="row-fluid">
            <div class='span10 offset3'>
                <div class="span2 featured-first"><?=render_views('featured-first')?></div>
                <div class="span4 featured-middle"><?=render_views('featured-middle')?></div>
                <div class="span2 featured-last"><?=render_views('featured-last')?></div>
            </div>
        </div>
    <?php endif; ?>
        
    <div class="row-fluid">
        <div class="span4 offset3"><?=  get_messages_from_session_modified()?></div>
    </div>
    <div class="row-fluid">
        <div class="span10 offset3">
            <div class='span6 primary'><?=@$main?><?=render_views('primary')?><?=render_views()?></div>
            <div class='span2 sidebar'><?=render_views('sidebar')?></div>
        </div>
    </div>



    <?php if(region_has_content('triptych-first', 'triptych-middle', 'triptych-last')): ?>
        <div class="row-fluid">
            <div class="span10 offset3">
                <div class='span2 triptych-first'><?=render_views('triptych-first')?></div>
                <div class='span4 triptych-middle'><?=render_views('triptych-middle')?></div>
                <div class='span2 triptych-last'><?=render_views('triptych-last')?></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row-fluid well">
        <div class="span8 offset2">
            <?php if(region_has_content('footer-column-one', 'footer-column-two', 'footer-column-three', 'footer-column-four')): ?>          
            
                <div class='span3 footer-column-one'><?=render_views('footer-column-one')?></div>
                <div class='span3 footer-column-two'><?=render_views('footer-column-two')?></div>
                <div class='span3 footer-column-three'><?=render_views('footer-column-three')?></div>
                <div class='span3 footer-column-four'><?=render_views('footer-column-four')?></div>
            <?php endif; ?>
        </div>
        <div class="row-fluid well">
            <div class="span8 offset2">
                <?=render_views('footer')?><?=$footer?><?=get_tools()?><?=get_debug()?>
            </div>
        </div>
    </div>
        
        <script src="jquery.js"></script>
	<script src="<?= $javascript ?>bootstrap.js"></script>
    </body>
</html>