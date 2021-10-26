<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filemanager extends Backend_Controller {
	
	public $mod = 'filemanager';

	public function __construct() 
	{
		parent::__construct();
	}

	
	public function index()
	{
		if ( $this->role->i('read') )
		{
			$this->meta_title('File Manager');
			$this->render_view('view_index', $this->vars);
		}
		else
		{
			$this->render_403();
		}
	}
} // End Class.