<?php /* Template Name: search */ ?>
<?php get_header(); ?>
<div class="content">
   <div id="mainbox">
     <div id="cse"></div>

    


     </div><!--mainbox-->
 <?php get_sidebar(); ?><!--sidebox-->
        
    </div><!--container end-->
<?php get_footer(); ?>

<script src="http://www.google.com/jsapi" type="text/javascript"></script>
    <script type="text/javascript">
    google.load('search', '1', {language : 'zh-CN'});
    google.setOnLoadCallback(function() {
        var customSearchControl = new google.search.CustomSearchControl(
      '002804873446831095173:7t28fgio334');
        customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
        customSearchControl.setLinkTarget(google.search.Search.LINK_TARGET_SELF);
        var options = new google.search.DrawOptions();
        var search = '<?php echo $_GET['q'] ; ?>';
        // options.setSearchFormRoot('cse-search-form'); // Google 搜索框
        options.setInput(document.getElementById('search-text')); // 自定义搜索框
        document.getElementById('searchbox').setAttribute('onSubmit',"document.getElementById('search-text').select(); return false;"); // 自定义搜索框
        document.getElementById('search-text').value = search; // 自定义搜索框
        customSearchControl.draw('cse', options);
        customSearchControl.execute(search);
    }, true);
</script>