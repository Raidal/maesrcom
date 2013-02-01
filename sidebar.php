<div id="sidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>


<ul>
	<li class="widget">

<h3>站内搜索</h3>
<div class="forme">
	<form action="/search" id="searchbox">
	    <input class="text" name="q" id="search-text" type="text" onblur="this.value==''?this.value=' 请输入关键词':null" onfocus="this.value==' 请输入关键词'?this.value='':null" value=" 请输入关键词" name="keywords">
	    <input  src="http://www.maesr.com/wp-content/themes/maesrcom/images/sea.jpg" type="image" name="Submit"  class="in2" />
	</form>
</div>
<div class=clear></div><br/>

<h3>最新公告</h3>
<p>欢迎来到私密日记网,这是一个只属于你我的小站</p>
<p>在这里你可以看到最真实的自己和最跳动的旋律</p>
<p>那些我们喜欢的美丽文字,曾停留在心间,纪录下来</p>
<br/>

<h3>活跃读者</h3>
		<ul class="ffox_most_active">
			<?php if (function_exists('zonce_most_active_friends')) { echo zonce_most_active_friends(16,'');} ?>
		</ul>
<div class=clear></div><br/>

<h3>随机文章</h3>
<ul>
   <?php
   $rand_posts = get_posts('numberposts=10&orderby=rand');
   foreach( $rand_posts as $post ) :
   ?>
   <li><a target="_blank" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
   <?php endforeach; ?>
</ul>
	<br/>

<h3>最新评论</h3>
	
	
		
		<ul class="recentcomments">
			<?php if (function_exists('zonce_recentcomments')) { echo zonce_recentcomments(8,18,''); } ?>
		</ul>
            <br/>

<!-- Hot posts view start -->
	<?php if (is_single()) {
				$catid = get_the_category(); //通过这个函数可以获得该文章下的分类信息
				$currentcat_id = $catid[0]->cat_ID; //获取得到该文章下的分类ID			
			}elseif (is_category()){ 	
				$currentcat_id = get_query_var('cat');
			} 
	?>
	<?php
	$args_hotpost = array( 
				'orderby' => 'rand', 
				'meta_key' => 'duoshuo_thread_id', 
				'order' => 'DESC',
				'ignore_sticky_posts' => 1,
				'showposts' => 6, // 显示篇数
				'cat' => $currentcat_id,  // 分类ID
				);	
    $meiwen_hot_post = new WP_Query( $args_hotpost );
	?>		
	<div class="widget">
		<h3>热门文章</h3>
			<div id="ta-post" class="clearfix">
				<ul>
					<?php while ($meiwen_hot_post->have_posts()) : $meiwen_hot_post->the_post(); update_post_caches($posts); ?>
					<li><a target="_blank" href="<?php echo the_permalink(); ?>" title="<?php the_title(); ?>" ><img src="<?php if ( get_post_meta($post->ID, 'thumbnails', true) ) : ?><?php echo get_post_meta($post->ID, 'thumbnails', true);?><?php else: ?><?php echo catch_first_image() ?><?php endif; ?>" alt="<?php the_title(); ?>" width="80" height="65" ></a>
					
					</li>
					<?php endwhile; wp_reset_query(); ?>	
				</ul>
			</div>
	</div>
<br/>
<!-- Hot posts view end -->

<?php if(is_home() or is_category()){ ?>
	<h3>近期文章</h3>
		<ul>
			<?php
			$myposts = get_posts('numberposts=10&offset=0&category=0');
			foreach($myposts as $post) : setup_postdata($post);
			?>
			<li><span><a target="_blank" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span></li>
			<?php endforeach; ?>
		</ul>
		<br/>
		
	<?php } ?>	

<div id="siderbar-ad1"><a href='http://www.maesr.com/tougao' target=”_blank” > <img src='http://maesr.b0.upaiyun.com/upaiyun/2012/12/direct-id-324.jpg' height="80px" alt='私密日记' border='0' width='278'> </a></div>
<div id="siderbar-ad2" style="margin-top:10px"><a href='http://www.maesr.com/tougao' target=”_blank” > <img src='http://www.maesr.com/wp-content/uploads/2013/01/tougao01.jpg' height="80px" alt='私密日志' border='0' width='278'> </a></div>
<div id="siderbar-ad3" style="margin-top:10px"><a href='http://www.maesr.com/tougao' target=”_blank” > <img src='http://maesr.b0.upaiyun.com/upaiyun/2012/12/direct-id-314.jpg' height="80px" alt='私密日志' border='0' width='278'> </a></div>
<br/>

<div id="PAGE_AD_2"></div>

	</li>
</ul>

<?php endif; ?>
</div>