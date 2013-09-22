<?php
    /**
    * Standard controller layout.
    *
    * @package SiteshopCore
    */
class CCIndex implements IController {

       /**
        * Implementing interface IController. All controllers must have an index action.
        */
	public function Index() {   
		global $ss;
		$ss->data['title'] = "The Index Controller";
		$ss->data['main'] = "<h1>The Index Controller</h1>";
    }

} 