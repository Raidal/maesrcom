<?php get_header(); ?>
      <div id="container">
    	<div class="content">
          <?php get_sidebar(); ?><!--sidebox-->
        	<div id="mainbox">
            
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            	<div class="post-content">
			<h2><?php the_title(); ?></h2>
			<p class="entry-info">发布时间：<?php the_time('Y-m-d'); ?>&nbsp;&nbsp;栏目：<?php the_category(', ') ?>&nbsp;&nbsp;评论：<?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?>&nbsp;&nbsp;<?php if(function_exists('the_views')) { the_views(); } ?>次点击 </p>
			<div class="content-c">
				<?php the_content('(more)'); ?>
				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>		
<p >
欢迎转载，请注明转自：<a href="http://www.maesr.com/">私密日记网</a><br />
本文链接: <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_permalink(); ?></a>
</p>
<!-- 复制添加版权 -->
<script type="text/javascript">document.body.oncopy=function(){ event.returnValue=false; var t=document.selection.createRange().text;var s="本文转自私密日记网(www.maesr.com),原文地址："+location.href; clipboardData.setData('Text','\r\n'+t+'\r\n'+s+'\r\n');}</script>
<!-- 复制添加版权 -->
<p>
<span class="">
<?php if (get_previous_post()) { echo '上一篇：'; previous_post_link('%link'); } else { echo "这已经是最新一篇"; }?>
</span>
<br/>
<span class="">
<?php if (get_next_post()) { echo '下一篇：'; next_post_link('%link'); } else { echo "这已经是最后一篇"; }?>
</span>
</p>

<!-- Baidu Button BEGIN -->
    <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
        <a class="bds_qzone"></a>
        <a class="bds_tsina"></a>
        <a class="bds_tqq"></a>
        <a class="bds_renren"></a>
        <a class="bds_diandian"></a>
        <a class="bds_tqf"></a>
        <a class="bds_douban"></a>
        <a class="bds_qq"></a>
        <a class="bds_t163"></a>
        <a class="bds_tfh"></a>
        <a class="bds_ff"></a>
        <span class="bds_more">更多</span>
	<a class="shareCount"></a>
    </div>

<!-- Baidu Button END -->
 
</div>
<div id="PAGE_AD_1"></div><br/>

<div class="articles">
<div class="post-author"><div class="avatar"><?php echo get_avatar( get_the_author_email(), '70' ); ?></div>
<div class="post-author-desc">
<a class="post-author-name" target="blank" title="去看看他/她的专栏" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><span><?php echo the_author_meta( 'nickname' ); ?></span></a><br>
<div class="post-author-description"><?php echo the_author_meta( 'description' ); ?></div>
<div class="post-author-links"><a rel="nofollow" target="blank" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">作者专栏</a><?php if (get_the_author_meta('weibo_sina')!=""){ ?><?php echo "<a href='" . get_the_author_meta('weibo_sina') . "' target='blank'> | 新浪微博</a>"; ?><?php } ?><?php if (get_the_author_meta('weibo_tx')!=""){ ?><?php echo "<a href='" . get_the_author_meta('weibo_tx') . "' target='blank'> | 腾讯微博</a>"; ?><?php } ?><?php if (get_the_author_meta('renren')!=""){ ?><?php echo "<a href='" . get_the_author_meta('renren') . "' target='_blank'> | 人人</a>"; ?><?php } ?></div>
<div class="clear"></div>
<div class="post-author-title">关于小编</div>
</div>
</div>
</div>
<div class="clear"></div>

                       
	       <div id="comments">
			
                            <?php comments_template('', true); ?>
	
                      </div>
                       
                </div><!--post-content end-->
               
		<?php endwhile; endif; ?> 

            </div><!--mainbox end-->
            
        </div><!--content end-->
    </div><!--container end-->
<?php get_footer(); ?>