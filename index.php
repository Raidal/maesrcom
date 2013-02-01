<?php get_header(); ?>

   <!-- <div id="container">-->
	 
    	<!--<div class="content">-->

              <?php get_sidebar(); ?><!--sidebox-->
		
        	<div id="mainbox">
            	<div class="post-c">


			<?php if(have_posts()) : ?>
			<?php while(have_posts()) : the_post(); ?>

				<div class="post">

					<?php if (get_post_meta($post->ID, 'thumbnails', true)){ ?>
					<div class="post-img" style="background:url('<?php echo get_post_meta($post->ID, 'thumbnails', true); ?>') no-repeat center center;">
					<?php } else { ?>
					<div class="post-img" style="background:url('<?php bloginfo('stylesheet_directory'); ?>/images/nopic.jpg') no-repeat center center;">
					<?php } ?>
						<a href="<?php the_permalink() ?>" rel="bookmark" target="_blank" title="Link to: <?php the_title_attribute(); ?>" class="png"><?php the_title(); ?></a>
					</div>

                        		<div class="post-txt">
                            			<div class="post-t-l">
                                			<h2><a href="<?php the_permalink() ?>" rel="bookmark" target="_blank" title="Permanent Link to <?php the_title_attribute(); ?>" class="titlelink"><?php echo mb_strimwidth(get_the_title(), 0, 46, '...'); ?></a></h2>
                                			<div class="entry-info">
                                    				By <?php the_author_posts_link(); ?>&nbsp;&nbsp;&nbsp;&nbsp;Time <?php the_time('Y.m.d H:i:s') ?>
                                			</div>
                            			</div>
                            			<div class="post-t-r">
                                			<div class="views"><?php if(function_exists('the_views')) { the_views(); } ?></div>
                                			<p><a href="<?php the_permalink() ?>" target="_blank"></a></p>
                            			</div>
                        		</div>
					<p><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 210,"..."); ?></p>
					<div class="entry-info2"><?php _e( '栏目：', 'd4' ); ?><?php the_category(', '); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php the_tags('关键词： ', ' , ', ''); ?></div>
				 </div>
			<?php endwhile; ?>
			<?php endif; ?>
		</div>
       
                <div class="page_navi"><?php par_pagenavi(9); ?></div>                
                
            </div><!--mainbox end-->
            
        </div><!--content end-->
      
    <!--</div>container end-->

<div style="width:980px">
<div id="links">
<?php if ( is_home()&&!is_paged()) { ?>
<?php wp_list_bookmarks('title_li=&category=2'); ?>
<?php } ?>
</div>
</div><!--只首页显示链接-->

<?php get_footer(); ?>