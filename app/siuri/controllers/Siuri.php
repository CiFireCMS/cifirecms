<?php defined('BASEPATH') or exit('No direct script access allowed');

class Siuri extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();

		$this->APPATH = _PATH;
		
		if (! $this->input->is_cli_request())
		{
			echo "Siuri is command line interface tool.";
			exit();
			return false;
		}
		
		$this->load->helper('file');
	}


	public function index()
	{
		// echo "\n\033[33m  \033[0m\n";
		echo "\nUsage :\n";
		echo "siuri [command]\n\n";

		echo "Command :\n";
		echo " make controller\n";
		echo " make model\n";
		echo " make helper\n";

		echo "\n";
		return true;
	}


	public function _remap($method, $params = array())
	{
		switch ($method)
		{
			case 'controller':
				$this->controller(
					!empty($params[0]) ? $params[0] : Null,
					!empty($params[1]) ? $params[1] : 'CI_Controller'
				);
			break;

			case 'model':
				$this->model(
					!empty($params[0]) ? $params[0] : Null,
					!empty($params[1]) ? $params[1] : Null,
					!empty($params[2]) ? $params[2] : Null,
					!empty($params[3]) ? $params[3] : 'CI_Model'
				);
			break;

			case 'create_table':
				$this->create_table(
					!empty($params[0]) ? $params[0] : NULL,
				);
			break;

			case 'make':
				$this->make();
			break;

			default:
				$this->index();
			break;
		}
		return true;
	}




	public function make()
	{
		echo "\n\033[33m Usage: \n\033[0m";
		echo " php siuri make [command]\n\n";

		echo "\033[33m Arguments: \n\033[0m";
		echo " command    Name of command.\n\n";

		echo "\033[33m Example: \n\033[0m";
		echo " php siuri make controller\n";
		echo " # Create a controller.\n\n";

		echo " php siuri make model\n";
		echo " # Create a model.\n\n";

		echo " php siuri make table\n";
		echo " # Create a table.\n\n";


		echo "\n";
		return true;
	}









	public function controller($name = Null, $extendsName = 'CI_Controller')
	{
		// No param, Will response help.
		if (!isset($name))
		{
			echo "\n\033[33m Usage: \n\033[0m";
			echo " php siuri controller [myController] [myExtends]\n\n";
			
			echo "\033[33m Arguments: \n\033[0m";
			echo " myController    Name of the controller class (use [.] to seperate sub directory).\n";
			echo " myExtends       Class extends which class.\n\n";

			echo "\033[33m Example: \n\033[0m";
			echo " php siuri make controller MyController\n";
			echo " # Create a MyController.php file in controllers folder.\n\n";
			
			echo " php siuri make controller MyController MyExtends\n";
			echo " # Create a MyController.php file with extends class MyExtends in controllers folder.\n\n";

			echo " php siuri make controller dir.subdir.MyController MyExtends\n";
			echo " # Create a controllers/dir/subdir/MyController.php file with extends class MyExtends.\n\n";
			return false;
		}
		// Recursive create folder and return path
		$path = $this->_folder_creator($name, 'controllers');

		// File exist.
		if (file_exists($this->APPATH.'controllers/'.$path.'.php'))
		{
			// echo "\n \033[33mERROR:\033[0m Controller \"\033[33m{$path}\033[0m\" already exists.!\n";
			echo $this->_note('ERROR', "Controller \"{$path}\" already exists.!");
			return false;
		}

		// Actually write file.
		if (! write_file($this->APPATH.'controllers/'.$path.'.php', $this->_controller_creator($path, $extendsName)))
		{
			echo $this->_note('ERROR', "Unable to write the file!");
			return false;
		}

		echo $this->_note('SUCCESS', "Controller {$name} was created");
		return true;
	}

	// controller creator.
	protected function _controller_creator($name, $extendsName)
	{
		$name = explode('/', $name)[count(explode('/', $name))-1]; // Find the end of array
		$data = "<?php\n";
		$data .= "defined('BASEPATH') or exit('No direct script access allowed');\n\n";
		$data .= "class ".$name." extends ".$extendsName." \n";
		$data .= "{\n";
		$data .= "	public function __construct()\n";
		$data .= "	{\n";
		$data .= "		parent::__construct();\n";
		$data .= "	}\n\n";
		$data .= "	public function index()\n";
		$data .= "	{\n";
		$data .= "		echo \"$name\";\n";
		$data .= "	}\n\n";
		$data .= "} //End Class.\n";
		return $data;
	}





	// public function model($name = Null, $table = Null, $primaryKey = Null, $extendsName = 'CI_Model')
	public function model($name = Null, $table = Null, $extendsName = 'CI_Model')
	{
		$extendsName = (empty($extendsName) ? "CI_Model" : $extendsName);

		// No param, Will response help.
		if (empty($name))
		{
			echo "\n\033[33m Usage: \n\033[0m";
			echo " php siuri make model [modelName] [table] [extendsName] \n\n";

			echo "\033[33m Arguments: \n\033[0m";
			echo " modelName    Name of model class (use [.] to seperate sub folder)\n";
			echo " table        This class will operate which table\n";
			echo " extendsName  This class extends which class\n\n";

			echo "\033[33m Example: \n\033[0m";
			echo " php siuri make model Test_model\n";
			echo " # Create a file Test_model.php in models folder.\n\n";

			echo " php siuri make model Product_model t_products\n";
			echo " # Create a file Product_model.php contain a variable \$table='t_products'.\n\n";

			echo " php siuri make model User_model t_users MY_Model\n";
			echo " # Create a file User_model.php contain a variable \$table='t_user' and extends MY_Model.\n\n";

			echo " php siuri make model some.other.User_model t_users MY_Model\n";
			echo " # Create a file models/some/other/User_model.php\n";
			echo " # contain a variable \$table='t_users' and class extends MY_Model.\n\n";
			return false;
		} // empty($name)

		else
		{
			$return = true;
			$note = $this->_note('SUCCESS', "Model {$name} was created.");

			// Recursive create folder and return path
			$path = $this->_folder_creator($name, 'models');

			// File exist.
			if (file_exists($this->APPATH.'models/'.$path.'.php'))
			{
				$note = $this->_note('ERROR', "Model \"{$path}\" already exists!");
				$return = false;
			}

			if (dbconnect()->table_exists($table))
			{
				$tableNote = $this->_note('ERROR', "Table \"{$table}\" already exists!");
				$tableReturn = false;
			}

			// Actually write model file.
			if (!write_file($this->APPATH.'models/'.$path.'.php', $this->_model_creator($path, $table, $extendsName)))
			{
				$note = $this->_note('ERROR', "Unable to write the file!");
				$return = false;
			}


			if (!empty($table))
			{			
				if ($return == false)
				{
					echo $note;
					return false;
				}
				else
				{
					echo $note;
					echo $this->create_table($table)['note'];
					return true;
				}
			}
			else
			{
				echo $note;
				return true;
			}
		}
	}

	// model creator
	// protected function _model_creator($name, $table, $extendsName)
	protected function _model_creator($name, $table, $extendsName)
	{
		$table = (!empty($table) ? $table : "t_mytable");
		$extends = (!empty($extendsName) ? "extends ".$extendsName : "");

		$name = explode('/', $name)[count(explode('/', $name))-1]; // Find the end of array
		$data = "<?php\ndefined('BASEPATH') OR exit('No direct script access allowed');\n\n";
		$data .= "class {$name} {$extends}\n";
		$data .= "{\n";
		$data .= "	protected \$table = '{$table}';\n\n";

		$data .= "	public function __construct()\n";
		$data .= "	{\n";
		$data .= "		parent::__construct();\n";
		$data .= "	}\n\n";

		$data .= "	public function getAll()\n";
		$data .= "	{\n";
		$data .= "		return \$this->db->get(\$this->table)->result_array();\n";
		$data .= "	}\n\n";

		$data .= "	public function getWhere(\$field = '', \$fieldValue = '')\n";
		$data .= "	{\n";
		$data .= "		return \$this->db->where(\$field, \$fieldValue)->get(\$this->table)->row_array();\n";
		$data .= "	}\n\n";

		$data .= "} // End Class.\n";
		return $data;
	}


	public function create_table($tableName='')
	{
		$tableName = strtolower($tableName);

		$note = $this->_note('SUCCESS', "Table \"{$tableName}\" was created.");
		$return = true;

		$this->db = dbconnect();
		dbconnect()->reconnect();
		$this->load->dbforge();
		
		if (!dbconnect()->conn_id)
		{
			$note = $this->_note('ERROR', "Unable to connect to your database server using the provided settings.', 500, 'A Database Error Occurred");
			$return = false;
		}
		else
		{
			if (dbconnect()->table_exists($tableName))
			{
				$note = $this->_note('ERROR', "Table \"{$tableName}\" already exists!");
				$return = false;
			}

			// add field primary key.
			$this->dbforge->add_field(array(
				'ID' => array(
					'type'           => 'INT',
					'constraint'     => 255,
					'unsigned'       => false,
					'auto_increment' => true,
				),
				'date_created' => array(
					'type'     => 'DATETIME',
				),
				'date_modify' => array(
					'type'     => 'DATETIME',
				),
				'status' => array(
					'type'     => "ENUM('Y','N')",
					'default'  => 'Y',
				),
			));

			$this->dbforge->add_key('ID', true);
			$table_attr = array('ENGINE' => 'InnoDB');
			$this->db->db_debug = false;
			$this->dbforge->create_table($tableName, true, $table_attr);
		}

		return array('return'=>$return, 'note'=>$note);
	}


	protected function dbconnect()
	{
		$config = array(
			'port'     => $_SERVER['DB_PORT'],
			'hostname' => $_SERVER['DB_HOST'],
			'username' => $_SERVER['DB_USER'],
			'password' => $_SERVER['DB_PASS'],
			'database' => $_SERVER['DB_NAME'],
			'dbdriver' => 'mysqli',
			'dbprefix' => '',
			'pconnect' => FALSE,
			'db_debug' => (ENVIRONMENT !== 'production'),
			'cache_on' => FALSE,
			'cachedir' => '',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'encrypt'  => FALSE,
			'compress' => FALSE,
			'stricton' => FALSE,
			'failover' => array(),
			'save_queries' => TRUE
		);

		return $this->load->database($config, true);
	}



	// recursive create folder and return file path
	protected function _folder_creator($fileName, $mvc)
	{
		$separator = '.';

		$folder = $this->APPATH.$mvc.'/';
		$arrDir = explode($separator, $fileName); 
		unset($arrDir[count($arrDir)-1]);

		foreach ($arrDir as $key => $value)
		{
			$folder .= $value.'/';
			if (! file_exists($folder))
			{
				mkdir($folder);
			}
		}

		$arrDir = explode($separator, $fileName);
		switch ($mvc)
		{
			case 'views':
				$arrDir[count($arrDir)-1] = strtolower( $arrDir[count($arrDir)-1] );
			break;
			default:
				// $arrDir[count($arrDir)-1] = ucfirst( $arrDir[count($arrDir)-1] );
				$arrDir[count($arrDir)-1] = $arrDir[count($arrDir)-1];
			break;
		}

		return implode('/', $arrDir);
	}




	private function _note($heading = '', $message = '')
	{
		$data = "\n\033[33m{$heading}:\033[0m {$message}\n";
		return $data;
	}
} // End Class.
