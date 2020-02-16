<?php
/**
 * CiFireCMS Pagination Library
 *
 * @author    AdimanCifi
 * @license   MIT License
 * @version   1.0.0
 * @link      https://www.alweak.com
 * @package   CiFIreCMS v2.x
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Cifire_Pagination {
	/**
	 * @var string
	*/
	protected $base_url = ''; 
	
	/**
	 * @var int
	*/
	protected $index_page;
	
	/**
	 * @var int
	*/
	protected $per_page;

	/**
	 * @var int
	*/
	protected $total_rows;

	/**
	 * @var int
	*/
	protected $limit_item = 3;
	
	/**
	 * for exaple to use : post content pagination
	 * @var int
	*/
	protected $total_link;

	/**
	 * @var string
	*/
	protected $list_tag_open  = '<li class="page-item">';

	/**
	 * @var string
	*/
	protected $list_tag_close = '</li>';

	/**
	 * @var string
	*/
	protected $link_tag_open  = '<a>';
	
	/**
	 * @var string
	*/
	protected $link_tag_close = '</a>';
	
	/**
	 * @var string
	*/
	protected $link_tag_attributes = 'class="page-link"';

	/**
	 * @var string|bool
	*/
	protected $prev_link = 'Previous';
	
	/**
	 * @var string|bool
	*/
	protected $next_link = 'Next';


	/**
	 * @var array
	*/
	public function __construct(array $params = array())
	{
		$this->initialize($params);
	}


	public function create_links() 
	{
		$ITEM = '';
		$segment = $this->index_page;

		$ITEM .= $this->_prev_link();
		
		if ($this->limit_item)
		{
			if ($this->limit_item && $this->index_page > $this->limit_item)
			{
				$ITEM .= $this->list_tag_open; // list_tag_open
				$ITEM .= preg_replace('/>/', ' href="'.$this->base_url.'1"'.$this->link_tag_attributes.'>', $this->link_tag_open);
				$ITEM .= '1';
				$ITEM .= $this->link_tag_close;
				$ITEM .= $this->list_tag_close;

				if ($this->index_page > $this->limit_item+1)
				{
					$lto = preg_replace('/>/', ' disabled>', $this->list_tag_open);
					$ITEM .= preg_replace('/class="/', 'class="disabled ',$lto); // list_tag_open
					$ITEM .= preg_replace('/>/', ' href="#"'.$this->link_tag_attributes.'>', $this->link_tag_open);
					$ITEM .= '...';
					$ITEM .= $this->link_tag_close;
					$ITEM .= $this->list_tag_close;
				}
			}
			
			// items before active index
			for ($i=$this->index_page-($this->limit_item-1); $i<$this->index_page; $i++) 
			{
				if ($i < 1) continue;

				$ITEM .= $this->list_tag_open; // list_tag_open
				$ITEM .= preg_replace('/>/', ' href="'.$this->base_url.$i.'"'.$this->link_tag_attributes.'>', $this->link_tag_open);
				$ITEM .= $i;
				$ITEM .= $this->link_tag_close;
				$ITEM .= $this->list_tag_close;
			}
		}


		//  Active
		if ($this->limit_item)
		{
			$ITEM .= preg_replace('/class="/', 'class="active ',$this->list_tag_open); // list_tag_open
			$ITEM .= preg_replace('/>/', ' href="#"'.$this->link_tag_attributes.'>', $this->link_tag_open);
			$ITEM .= $this->index_page;
			$ITEM .= $this->link_tag_close;
			$ITEM .= $this->list_tag_close;
		}
		else
		{
			$maxlink = $this->total_link?$this->total_link:$this->total_links();
			for ($i=1; $i < $maxlink+1; $i++)
			{
				$class_active = $this->index_page == $i?'active':'';
				$href = $this->index_page == $i?'#':$this->base_url.$i;
				$ITEM .= preg_replace('/class="/', "class=\"$class_active ",$this->list_tag_open); // list_tag_open
				$ITEM .= preg_replace('/>/', ' href="'.$href.'"'.$this->link_tag_attributes.'>', $this->link_tag_open);
				$ITEM .= $i;
				$ITEM .= $this->link_tag_close;
				$ITEM .= $this->list_tag_close;
			}
		}
		
		if ($this->limit_item)
		{
			for ($i = $this->index_page + 1; $i < ($this->index_page + $this->limit_item); $i++)  
			{
				if ($i > $this->total_links())
				{
					break;
				}

				$ITEM .= $this->list_tag_open; // list_tag_open
				$ITEM .= preg_replace('/>/', ' href="'.$this->base_url.$i.'"'.$this->link_tag_attributes.'>', $this->link_tag_open);
				$ITEM .= $i;
				$ITEM .= $this->link_tag_close;
				$ITEM .= $this->list_tag_close;
			}

			// arfter
			if ($this->index_page+($this->limit_item-1) < $this->total_links())
			{
				if ($this->index_page < ($this->total_links() - $this->limit_item)) {

					$lto = preg_replace('/>/', ' disabled>', $this->list_tag_open);
					$ITEM .= preg_replace('/class="/', 'class="disabled ',$lto); // list_tag_open
					$ITEM .= preg_replace('/>/', ' href="#" '.$this->link_tag_attributes.'>', $this->link_tag_open);
					$ITEM .= '...';
					$ITEM .= $this->link_tag_close;
					$ITEM .= $this->list_tag_close;
				}

				$ITEM .= $this->list_tag_open; // list_tag_open
				$ITEM .= preg_replace('/>/', ' href="'.$this->base_url.$this->total_links().'" '.$this->link_tag_attributes.'>', $this->link_tag_open);
				$ITEM .= $this->total_links();
				$ITEM .= $this->link_tag_close;
				$ITEM .= $this->list_tag_close;
			}
		}

		$ITEM .= $this->_next_link();
		
		return $ITEM;
	}


	private function _prev_link()
	{
		$links = '';
		if ($this->prev_link != FALSE)
		{

			$segment = $this->index_page-1;

			if ($segment >= 1)
			{
				$links .= $this->list_tag_open; // list_tag_open
				$links .= preg_replace('/>/', ' href="'.$this->base_url.$segment.'" '.$this->link_tag_attributes.'>', $this->link_tag_open);
			} 
			else
			{
				$link = preg_replace('/>/', ' disabled>', $this->list_tag_open);
				$links .= preg_replace('/class="/', 'class="disabled ',$link); // list_tag_open

				$links .= preg_replace('/>/', ' href="#" '.$this->link_tag_attributes.'>', $this->link_tag_open);
			}
			
			$links .= $this->prev_link;
			$links .= $this->link_tag_close;
			$links .= $this->list_tag_close; 
		}

		return $links;
	}


	private function _next_link()
	{
		$links = '';
		if ($this->next_link != FALSE)
		{
			if ($this->total_link) {
				$index_page = $this->index_page;
				$total_link = $this->total_link;
				$segment = $this->index_page+1;
			} else {
				$index_page = $this->index_page;
				$total_link = $this->total_links();
				$segment = $this->index_page+1;
			}

			if ( $index_page < $total_link)
			{
				$links .= $this->list_tag_open; // list_tag_open
				$links .= preg_replace('/>/', ' href="'.$this->base_url.$segment.'" '.$this->link_tag_attributes.'>', $this->link_tag_open);
			} 
			else
			{
				$link = preg_replace('/>/', ' disabled>', $this->list_tag_open);
				$links .= preg_replace('/class="/', 'class="disabled ',$link); // list_tag_open

				$links .= preg_replace('/>/', ' href="#" '.$this->link_tag_attributes.'>', $this->link_tag_open);
			}
			
			$links .= $this->next_link;
			$links .= $this->link_tag_close;
			$links .= $this->list_tag_close; 
		}

		return $links;
	}


	public function total_links()
	{
		$int = ceil($this->total_rows / $this->per_page);
		return (int)$int;
	}


	public function initialize(array $params = array())
	{
		if (isset($params['per_page'])) {
			$this->per_page = $params['per_page'];
			unset($params['per_page']);
		}
		else
		{
			$this->per_page = get_setting('page_item');
		}

		foreach ($params as $key => $val)
		{
			if (property_exists($this, $key))
			{
				$this->$key = $val;
			}
		}

		return $this;
	}
} // End Class