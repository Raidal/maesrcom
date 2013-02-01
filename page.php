<?php get_header(); ?>

    <div id="container">
    	<div class="content">
<?php get_sidebar(); ?><!--sidebox-->
        	<div id="mainbox">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            	<div class="post-content">
			<h2><?php the_title(); ?></h2>
			<p class="entry-info">发布时间：<?php the_time('Y-m-d'); ?>&nbsp;&nbsp;栏目：<?php the_category(', ') ?>&nbsp;&nbsp;评论：<?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?>&nbsp;&nbsp;<?php if(function_exists('the_views')) { the_views(); } ?></p>
			<div class="content-c"><?php the_content(); ?></div>
			


			<h2>作者相关文章：</h2>
			<?php echo get_related_author_posts(); ?>
 
                        </br>

    <!-- Baidu Button BEGIN -->
    <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
        <a class="bds_qzone"></a>
        <a class="bds_tsina"></a>
        <a class="bds_tqq"></a>
        <a class="bds_renren"></a>
        <a class="bds_huaban"></a>
        <a class="bds_tqf"></a>
        <a class="bds_douban"></a>
        <a class="bds_qq"></a>
        <a class="bds_t163"></a>
        <a class="bds_tfh"></a>
        <a class="bds_ff"></a>
        <span class="bds_more">更多</span>
		<a class="shareCount"></a>
    </div>
<script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=706685" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
    var bds_config = {'snsKey':{'tsina':'1917204733'}};
	document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?t=" + new Date().getHours();
</script>
<!-- Baidu Button END -->
                        <div class="clear"></div>
                        <div id="comments">
				<?php comments_template(); ?>
			</div>

                                </div><!--post-content end-->

             <?php endwhile; endif; ?>
               
                              </div><!--mainbox end-->
            
                           </div><!--content end-->
           
	                </div><!--container end-->

<?php get_footer(); ?>