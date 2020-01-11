<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cifire_Pagination {

	protected $base_url = ''; 
	protected $index_page;
	protected $per_page;
	protected $total_rows; 
	protected $limit_item = 3;
	protected $total_link;  // contoh pemakaian di post content pagination

	protected $list_tag_open  = '<li class="page-item">';
	protected $list_tag_close = '</li>';

	protected $link_tag_open  = '<a>';
	protected $link_tag_close = '</a>';
	protected $link_tag_attributes = 'class="page-link"';

	protected $prev_link = 'Previous'; // or bool
	protected $next_link = 'Next'; // or bool


	public function __construct($params = array())
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

} // End Class.