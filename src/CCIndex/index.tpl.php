<h1>Index Controller</h1>
<p>Welcome to Siteshop index controller.</p>

<h2>Download</h2>
<p>You can download Siteshop from github.</p>
<blockquote>
    <code>git clone git://github.com/guni12/siteshop.git</code>
</blockquote>
<p>You can review its source directly on github: <a href='https://github.com/guni12/siteshop'>https://github.com/guni12/siteshop</a></p>

<h2>Installation</h2>
<p>First you have to make the data-directory writable. This is the place where Siteshop needs
    to be able to write and create files.</p>
<blockquote>
    <code>cd siteshop; chmod 777 site/data</code>
</blockquote>

<p>Second, Siteshop has some modules that need to be initialised. You can do this through a 
    controller. Point your browser to the following link.</p>
<blockquote>
    <a href='<?= create_url('modules/install') ?>'>modules/install</a>
</blockquote>	