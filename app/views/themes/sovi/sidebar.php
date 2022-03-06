<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Categories -->
<div class="sidebar-widgets clearfix mb-3">
	<div class="widget-title">
		<h4 class="tx-capitalize">Categories</h4>
	</div>
	<div class="widget clearfix widget-category">
		<div class="widget-body">
			<ul class="nav nav-pills flex-column">
				<?php
					$sidebar_category = $this->CI->db
						->select('id_category,COUNT(*)')
						->from('t_post')
						->where('active','Y')
						->group_by('id_category')
						->order_by('COUNT(*)','DESC')
						->get()
						->result_array();
					foreach ($sidebar_category as $rescount):
						$row_scategory = $this->CI->db
							->select('id,title,seotitle')
							->where('id',$rescount['id_category'])
							->where('id >','1')
							->where('active','Y')
							->get('t_category')
							->row_array();

						$num_spost = $this->CI->db
							->select('id')
							->where('id_category',$rescount['id_category'])
							->where('active','Y')
							->get('t_post')
							->num_rows();
						
						if (!$row_scategory || $num_spost == 0) continue;
				?>
				<li class="nav-item">
					<a href="<?=site_url('category/'.$row_scategory['seotitle']);?>" class="nav-link"><?=$row_scategory['title'];?> <small class="pull-right"><?=$num_spost;?></small></a>
				</li>
				<?php endforeach ?>
			</ul>
		</div>
	</div>
</div>
<!--/ Categories -->

<!-- tags -->
<div class="sidebar-widgets clearfix mb-3">
	<div class="widget-title">
		<h4 class="tx-capitalize">Tags</h4>
	</div>
	<div class="widget clearfix widget-tags">
		<div class="widget-body tagcloud">
			<?php
				$side_tags = $this->CI->db
					->select('
							  t_tag.title, 
							  t_tag.seotitle, 
							  COUNT(t_post.id) AS tag_count
							')
					->from('t_tag')
					->join('t_post', "t_post.tag LIKE CONCAT('%',t_tag.seotitle,'%')", 'LEFT')
					->group_by('t_tag.id')
					->get()
					->result_array();
				foreach ( $side_tags as $row_stag ):
					if ( $row_stag['tag_count'] == 0 )
						continue;
			?>
			<a href="<?=site_url('tag/'.$row_stag['seotitle']);?>" class=""><?=$row_stag['title'];?></a>
			<?php endforeach ?>
		</div>
	</div>
</div>
<!--/ tags -->

<!-- ADS -->
<div class="sidebar-widgets clearfix mb-3">
	<div class="widget">
		<img src="<?=post_images('ads.jpg','',TRUE);?>" style="width:100%;">
	</div>
</div>
<!--/ ADS -->