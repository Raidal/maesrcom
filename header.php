<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="<?php if (is_single()){ $keywords = "";$tags = wp_get_post_tags($post->ID);foreach ($tags as $tag ) {$keywords = $keywords . $tag->name . ", ";}echo $keywords;}else{echo ("私密日记,心情日记,剧透,剧透网,影评");} ?>" />
        <meta name="description" content="<?php if (is_single()){ echo get_the_title();} else{echo ("私密日记,记录你我的感动瞬间,分享简单快乐");}?>" />
        <title><?php wp_title( '-', true, 'right' ); echo wp_specialchars( get_bloginfo('name'), 1 ) ?> - <?php bloginfo('description'); ?><?php if ( $paged < 2 ) {} else { echo (' - 第'); echo ($paged);echo ('页');}?></title>
        <link rel="stylesheet" href="http://www.maesr.com/wp-content/themes/maesrcom/style.css" type="text/css" media="screen" />
	<link rel="icon" href="http://maesr.com/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="maesr.com/favicon.ico" type="image/x-icon">
   
	<?php wp_head(); ?>

　　　　<?php if (is_archive() && ($paged > 1) && ($paged < $wp_query->max_num_pages)) { ?>   
　　　　<link rel="prefetch" href="<?php echo get_next_posts_page_link(); ?>">   
　　　　<link rel="prerender" href="<?php echo get_next_posts_page_link(); ?>">   
　　　　<?php } ?>
             
</head>
<body>
 <div id="center">
    <div class="content">
      <div id="logo-nav">
        <h1><a href="http://www.maesr.com" name="top"><img src="<?php bloginfo('template_url'); ?>/images/logo.png" height="35px" width="180px" alt="私密日记"/></a></h1>
           <div id="nav">
            <ul>
	            记录属于你我的感动瞬间,分享简单快乐
                    <br>
                    <iframe width="136" height="24" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="no" border="0" src="http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&width=136&height=24&uid=1252509785&style=2&btn=red&dpc=1"></iframe>     
<iframe src="http://follow.v.t.qq.com/index.php?c=follow&a=quick&name=raidal&style=5&t=1346915709909&f=1" frameborder="0" scrolling="auto" width="178" height="24" marginwidth="0" marginheight="0" allowtransparency="true"></iframe>
                </ul>
            </div>
<div id="search">
<!-- Baidu Button BEGIN -->
    <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
        <a class="bds_qzone"></a>
        <a class="bds_tsina"></a>
        <a class="bds_tqq"></a>
        <a class="bds_renren"></a>
        <a class="bds_ff"></a>
        <span class="bds_more">更多</span>
	<a class="shareCount"></a>
    </div>
<!-- Baidu Button END -->          
        </div>
    </div><!--logo nav end-->
<div id="me">
	<?php
	if(function_exists('wp_nav_menu')) {
	wp_nav_menu(array('theme_location'=>'primary','menu_id'=>'nave','container'=>'ul'));
	}
	?>
</div>
<div><div class="tirp"><strong>公告：</strong>您正在浏览全新改版的私密日记网，如果有什么好的意见和建议，请写信给我们！<a href="http://feed.feedsky.com/maesr" target="_blank" rel="nofollow">点击订阅</a> 或添加到<a href="http://www.maesr.com">收藏夹</a>，方便下次浏览(Ctrl+D).你也可以给我们投稿，<a href="http://www.maesr.com/tougao" target="_blank">点击投稿</a></div>
<div id="PAGE_AD_3"></div>
</div>
 