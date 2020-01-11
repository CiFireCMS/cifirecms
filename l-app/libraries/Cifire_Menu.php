<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cifire_Menu {

	var $vardata;
	private $langMenu;

	public function __construct()
	{		
		$this->CI =& get_instance();
		$this->_url = site_url();
		$this->admin_url = admin_url();
	}


	public function front_menu($group_id, $ul_attr = '', $li_attrs = '', $a_attr ='', $li_ul_attr = '', $ul_li_a_ul_li = '')
	{
		global $_;

		$get_menu = $this->CI->db
			->where('group_id', $group_id)
			->where('active', 'Y')
			->order_by('parent_id', 'ASC')
			->order_by('position', 'ASC')
			->get('t_menu')
			->result_array();

		foreach ($get_menu as $row) 
		{
			$a_class = "";
			$a_attr2 = "";

			if (!empty($row['class'])) 
			{
				$pecah = explode('||', $row['class']);

				if (count($pecah) < 2)
				{
					continue;
				}

				$a_class = 'class="'.trim($pecah[0]).'"';
				
				if ($pecah[0] == "null")
				{
					$a_class = "";
				}

				$a_attr2 = trim($pecah[1]);
				$a_attr2 = html_entity_decode($a_attr2);
			}
			$pecah_url = explode('==', $row['url']);
			$count_url = count($pecah_url)-1;
			$href = ( $count_url == 1 ? $pecah_url[1] : site_url($pecah_url[0]));

			if ($row['parent_id'] == 0) 
			{
				if (empty($row['url']))
				{
					$label = '<a ' . $a_class . ' ' . $a_attr . ' ' . $a_attr2 .'  href="">';
				}
				elseif ($row['url'] == '#')
				{
					$label = '<a ' . $a_class . ' ' . $a_attr . ' ' . $a_attr2 .'  href="#">';
				}
				else
				{
					$label = '<a ' . $a_class . ' ' . $a_attr . ' ' . $a_attr2 .'  href="' . $href .'" >';
				}
			} 
			else 
			{
				if (empty($row['url']))
				{
					$label = '<a '.$a_class.' href="">';
				}
				elseif($row['url'] == '#')
				{
					$label = '<a '.$a_class.' href="#">';
				}
				else 
				{
					$label = '<a '.$a_class.' href="' . $href .'">';
				}
			}

			$label .= $row['title'];
			$label .= '</a>';
			$li_attr = $row['class'];
			
			$this->add_row($row['id'], $row['parent_id'], $li_attr, $label);
		}
		
		$menu_result = $this->generate_list($ul_attr, $li_attrs, $li_ul_attr, $ul_li_a_ul_li);
		return $menu_result;
	}


	public function add_row($id, $parent, $li_attr, $label)
	{
		$this->vardata[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label);
		return $this;
	}


	public function generate_list($attr = '', $attrs = '', $attrss = '')
	{
		return $this->ul(0, $attr, $attrs, $attrss);
	}


	public function ul($parent = 0, $attr = '', $attrs = '', $attrss = '')
	{
		static $i = 1;

		$indent = str_repeat("\t\t", $i);
		
		if (isset($this->vardata[$parent])) 
		{
			if ($attr)
			{
				$attr = $attr;
			}

			if ($attrs)
			{
				$attrs = $attrs;
			}

			if ($attrss)
			{
				$attrss = $attrss;
			}

			$html = "\n$indent";
			$html .= "<ul ".$attr.">";

			$i++;

			foreach ($this->vardata[$parent] as $row) 
			{
				$child = $this->ul($row['id'], $attrss);
				$html .= "\n\t$indent";

				if ($child)
				{
					$html .= '<li '.$attrs.'>';
				}
				else
				{
					$html .= '<li>';
				}

				$html .= $row['label'];

				if ($child) 
				{
					$i--;
					$html .= $child;
					$html .= "\n\t$indent";
				}

				$html .= '</li>';
			}

			$html .= "\n$indent</ul>";

			return $html;
		} 
		else 
		{
			return FALSE;
		}
	}





	public function dashboard_menu($group_id, $ul_attr = '', $li_attrs = '', $a_attr ='', $li_ul_attr = '', $ul_li_a_ul_li = '')
	{
		global $_;

		$get_menu = $this->CI->db
			->where('group_id', $group_id)
			->where('active', 'Y')
			->order_by('parent_id', 'ASC')
			->order_by('position', 'ASC')
			->get('t_menu')
			->result_array();

		foreach ($get_menu as $row) 
		{
			$mLine = 'menu_'.seotitle($row['title']);
			// $title = isset($this->langMenu[$mLine]) ? lang_line($mLine) : $row['title'];
			$title = lang_line($mLine);
			if ($title=='Undefined') {
				$title = $row['title'];
			}
			$a_url ='javascript:void(0)';

			// $st = seotitle($row['title']);
			// if ($st == 'file-manager') {
			// 	$a_url = site_url('content/vendor/filemanager/dialog.php?type=0');
			// }
			
			if (!empty($row['url'])) 
			{
				$a_url = admin_url($row['url']);
			}

			$classes = explode("||", $row['class']);

			if (count($classes) >= 2) 
			{
				$a_class = trim($classes[1]);
				$i_class = trim($classes[0]);
			}
			else 
			{
				$a_class = "";
				$i_class = $row['class'];
			}

			if ($row['class']=='header') {
				// $label = '<div class="text-uppercase font-size-xs line-height-xs">'.$title.'</div>';
				$label = $title;
			}
			else 
			{
				if ($row['parent_id'] == 0) 
				{
					$label = '<a href="' . $a_url . '">';
				} 
				else 
				{
					$label = '<a href="' . $a_url . '" class="'.$a_class.'">';
				}
				
				// set menu icon.
				if (!empty($row['class']) && $row['parent_id'] == 0) 
				{
					$label .= '<i data-feather="'.$i_class.'"></i> <span>'.$title.'</span>';
				} 
				elseif (empty($row['class']) && $row['parent_id'] == 0)
				{
					$label .= '<i data-feather="git-commit"></i> <span>'.$title.'</span> ';
				}
				else 
				{
					$label .= $title;
				}

				$label .= '</a>';
			}


			if ($row['class']=='header') {
				$li_attr = 'class="mg-l-20-force mg-t-25-force menu-others"';
			} else {
				$li_attr = '';
			}
			$liblock = $row['url'];
			$ultitle = $row['url'];
			$subt = $row['title'];
			$this->add_row_dasboard($row['id'], $row['parent_id'], $li_attr, $label, $liblock, $ultitle, $subt);
		}
		
		return $this->generate_list_dasboard($ul_attr, $li_attrs, $li_ul_attr, $ul_li_a_ul_li);
	}


	public function ul_dasboard($parent = 0, $attr = '', $attrs = '', $attrss = '')
	{
		static $i = 1;

		$indent = str_repeat("\t\t", $i);
		
		if (isset($this->vardata[$parent])) 
		{
			if ($attr) 
			{
				$attr = $attr;
			}

			if ($attrs) 
			{
				$attrs = $attrs;
			}

			if ($attrss) 
			{
				$attrss = $attrss;
			}

			$html = "\n$indent";

			$submenu_title = $this->CI->db->where('id', $parent)->get('t_menu')->row_array()['title'];
			$html .= '<ul '.$attr.'>';

			$i++;

			foreach ($this->vardata[$parent] as $row) 
			{
				$child = $this->ul_dasboard($row['id'], 'class="sub-menu" style="display:block;"');
				$html .= "\n\t$indent";

				if ($child) 
				{
					$html .= '<li>';
				}
				else 
				{
					// $html .= '<li '. $row['li_attr'] .'>';
					$html .= '<li '. $row['li_attr'] .'>';
				}

				$html .= $row['label'];

				if ($child) 
				{
					$i--;
					$html .= $child;
					$html .= "\n\t$indent";
				}

				$html .= '</li>';
			}

			$html .= "\n$indent</ul>";
			
			return $html;
		} 
		else 
		{
			return FALSE;
		}
	}


	public function generate_list_dasboard($attr = '', $attrs = '', $attrss = '')
	{
		return $this->ul_dasboard(0, $attr, $attrs, $attrss);
	}


	public function add_row_dasboard($id = '', $parent = '', $li_attr = '', $label = '', $liblock = '',$ultitle = '', $subt = '')
	{
		$this->vardata[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label, 'liblock'=>$liblock, 'ultitle' => $ultitle, 'sub_title'=>$subt);
		return $this;
	}



	public function clear()
	{
		$this->vardata = array();
	}
} // End class