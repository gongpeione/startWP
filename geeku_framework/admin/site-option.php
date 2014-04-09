<?php

add_action('init','of_options');

if (!function_exists('of_options')) {
function of_options(){

// VARIABLES
$themename = "Geekust";
$shortname = "ge";

// Populate siteoptions option in array for use in theme
global $of_options;
$of_options = get_option('of_options');
$GLOBALS['template_path'] = get_template_directory_uri() . '/geeku_framework';


//Access the WordPress Categories via an Array
$of_categories = array();  
$of_categories_obj = get_categories('hide_empty=0');
foreach ($of_categories_obj as $of_cat) {
$of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
$categories_tmp = array_unshift($of_categories, "选择一个分类:");    


//Access the WordPress Pages via an Array
$of_pages = array();
$of_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($of_pages_obj as $of_page) {
$of_pages[$of_page->ID] = $of_page->post_name; }
$of_pages_tmp = array_unshift($of_pages, "选择博客页面:");       


// Image Alignment radio box
$options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 


// Image Links to Options
$options_image_link_to = array("image" => "The Image","post" => "The Post"); 


//More Options
$uploads_arr = wp_upload_dir();
$all_uploads_path = $uploads_arr['path'];
$all_uploads = get_option('of_uploads');
$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");


//Footer Columns Array
$footer_columns = array("1","2","3","4","5","6");


//Paths for "type" => "images"
$url =  get_template_directory_uri() . '/geeku_framework/admin/images/color-schemes/';
$footerurl =  get_template_directory_uri() . '/geeku_framework/admin/images/footer-layouts/';
$fonturl =  get_template_directory_uri() . '/geeku_framework/admin/images/fonts/';
$framesurl =  get_template_directory_uri() . '/geeku_framework/admin/images/image-frames/';
$logourl =  get_template_directory_uri() . '/geeku_framework/admin/images/logo-builder/';
$recaptcha_themes = get_template_directory_uri() . '/geeku_framework/admin/images/recaptcha-themes/';//since version 2.6


//Access the WordPress Categories via an Array
$exclude_categories = array();  
$exclude_categories_obj = get_categories('hide_empty=0');
foreach ($exclude_categories_obj as $exclude_cat) {
$exclude_categories[$exclude_cat->cat_ID] = $exclude_cat->cat_name;}










/*-----------------------------------------------------------------------------------*/
/* Create Site Options Array */
/*-----------------------------------------------------------------------------------*/
$options = array();
			
			
			$options[] = array( "name" => __('基本设置','truethemes_localize'),
			"type" => "heading");
			

$options[] = array( "name" => __('网站Logo','truethemes_localize'),
			"desc" => __('图片高度最好不要超过260px.','truethemes_localize'),
			"id" => $shortname."_sitelogo",
			"std" => "",
			"type" => "upload");
			
$options[] = array( "name" => __('登陆页面Logo','truethemes_localize'),
			"desc" => __('为你的登陆页面设置Logo. 326px*82px','truethemes_localize'),
			"id" => $shortname."_loginlogo",
			"std" => "",
			"type" => "upload");
			
$options[] = array( "name" => __('Favicon图标','truethemes_localize'),
			"desc" => __('','truethemes_localize'),
			"id" => $shortname."_favicon",
			"std" => "",
			"type" => "upload");
			
									   
$options[] = array( "name" => __('网站统计代码','truethemes_localize'),
			"desc" => __('放置你的 谷歌分析 或者其他网站统计代码.','truethemes_localize'),
			"id" => $shortname."_google_analytics",
			"std" => "",
			"type" => "textarea");
			
			
$options[] = array( "name" => __('更新提示','truethemes_localize'),
			"desc" => __('主题后台更新提示。不需要显示则取消勾选','truethemes_localize'),
			"id" => $shortname."_update_notifier",
			"std" => "true",
			"type" => "checkbox");			
			
						
			
//filter to allow developer to add new options to general settings.			
$options = apply_filters('theme_option_general_settings',$options);			
			
			
			
			
			
$options[] = array( "name" => __('风格设置','truethemes_localize'),
			"type" => "heading");

									
$options[] = array( "name" => __('自定义 CSS','truethemes_localize'),
			"desc" => __('在这里添加你的自定义CSS.','truethemes_localize'),
			"id" => $shortname."_custom_css",
			"std" => "",
			"type" => "textarea");
			
		
$options = apply_filters('theme_option_style_settings',$options);	


/*
			
$options[] = array( "name" => __('博客设置','truethemes_localize'),
			"type" => "heading");
			
$options[] = array( "name" => __('Featured Images','truethemes_localize'),
			"desc" => __('Select the image frame style for featured images.','truethemes_localize'),
			"id" => $shortname."_blog_image_frame",
			"std" => "modern",
			"type" => "images",
			"options" => array(
				'modern' => $framesurl . 'modern.png',
				'shadow' => $framesurl . 'shadow.png'
				));
			
$options[] = array( "name" => __('Blog Page','truethemes_localize'),
			"desc" => __('Select your blog page from the dropdown list.','truethemes_localize'),
			"id" => $shortname."_blogpage",
			"std" => "",
			"type" => "select",
			"options" => $of_pages);

$options[] = array( "name" => __('Banner Text','truethemes_localize'),
			"desc" => __('This text is displayed in the banner area of the Blog page.','truethemes_localize'),
			"id" => $shortname."_blogtitle",
			"std" => "Blog",
			"type" => "text");
			
$options[] = array( "name" => __('Button Text','truethemes_localize'),
			"desc" => __('These buttons are displayed after each blog post excerpt.','truethemes_localize'),
			"id" => $shortname."_blogbutton",
			"std" => "Continue Reading &rarr;",
			"type" => "text");
			
$options[] = array( "name" => __('Drag-to-Share','truethemes_localize'),
					"desc" => __('Drag-to-share functionality is added to each blog post by default. <em>Un-check this box to disable drag-to-share.</em>','truethemes_localize'),
					"id" => $shortname."_dragshare",
					"std" => "true",
					"type" => "checkbox");
			
$options[] = array( "name" => __('"Posted by" Information','truethemes_localize'),
			"desc" => __('<em>Check this box</em> to disable the "Posted by" information located under each Blog Post Title.</em>','truethemes_localize'),
			"id" => $shortname."_posted_by",
			"std" => "false",
			"type" => "checkbox");
			
$options[] = array( "name" => __('Post Date and Comments','truethemes_localize'),
			"desc" => __('<em>Check this box</em> to disable the posted date and comments count located next to each Blog Post.</em>','truethemes_localize'),
			"id" => $shortname."_post_date",
			"std" => "false",
			"type" => "checkbox");
			
$options[] = array( "name" => __('About-the-Author','truethemes_localize'),
			"desc" => __('The author\'s bio is displayed at the end of each blog post by default. <em>Un-check this box to disable the bio.</em> (Author bio\s can be set in the Wordpress user profile page. (<a href=\"profile.php\">Users > Your Profile</a>)','truethemes_localize'),
			"id" => $shortname."_blogauthor",
			"std" => "true",
			"type" => "checkbox");
			
$options[] = array( "name" => __('Related Posts','truethemes_localize'),
			"desc" => __('Related posts are displayed at the end of each blog post by default. <em>Un-check this box to disable the related posts.</em>','truethemes_localize'),
			"id" => $shortname."_related_posts",
			"std" => "true",
			"type" => "checkbox");
			
$options[] = array( "name" => __('Related Posts Title','truethemes_localize'),
			"desc" => __('Enter the title that is displayed above the list of related posts. <em>Simply leave this field blank if you won\'t be using this functionality.</em>','truethemes_localize'),
			"id" => $shortname."_related_posts_title",
			"std" => "Related Posts",
			"type" => "text");
			
$options[] = array( "name" => __('Related Posts Count','truethemes_localize'),
			"desc" => __('Enter the amount of related posts you\'d like to display. <em>Simply leave this field blank if you won\'t be using this functionality.</em>','truethemes_localize'),
			"id" => $shortname."_related_posts_count",
			"std" => "5",
			"type" => "text");
			
			
$options[] = array( "name" => __('Exclude Categories','truethemes_localize'),
			"desc" => __('Check off any post categories that you\'d like to exclude from the blog.','truethemes_localize'),
			"id" => $shortname."_blogexcludetest",
			"std" => "",
			"type" => "multicheck",
			"options" => $exclude_categories);

$options[] = array( "name" => __('Post Comments','truethemes_localize'),
			"desc" => __('Post comments are enabled by default. <em>Un-check this box to completely disable comments on all blog posts.</em>','truethemes_localize'),
			"id" => $shortname."_post_comments",
			"std" => "true",
			"type" => "checkbox");			
			
			
			
//allow developer to add in new options to blog settings.			
$options = apply_filters('theme_option_blog_settings',$options);				
*/			
		
$number = array('4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9,  '10' => 10);			
			
$options[] = array( "name" => __('主页设置','truethemes_localize'),
			"type" => "heading");


$options[] = array( "name" => __('每块显示的文章数','truethemes_localize'),
			"desc" => __('选择在主页每块显示的文章数.','truethemes_localize'),
			"id" => $shortname."_count",
			"std" => "",
			"type" => "select",
			"options" => $number);


$options[] = array( "name" => __('包含的分类-1','truethemes_localize'),
			"desc" => __('选择在主页第1块显示的分类名.','truethemes_localize'),
			"id" => $shortname."_part1",
			"std" => "",
			"type" => "select",
			"options" => $exclude_categories);
$options[] = array( "name" => __('分类-1 描述','truethemes_localize'),
			"desc" => __('选择在主页第2块显示的分类的描述.','truethemes_localize'),
			"id" => $shortname."_part1_des",
			"std" => "",
			"type" => "text",);


$options[] = array( "name" => __('包含的分类-2','truethemes_localize'),
			"desc" => __('选择在主页第2块显示的分类名.','truethemes_localize'),
			"id" => $shortname."_part2",
			"std" => "",
			"type" => "select",
			"options" => $exclude_categories);
$options[] = array( "name" => __('分类-2 描述','truethemes_localize'),
			"desc" => __('选择在主页第2块显示的分类的描述.','truethemes_localize'),
			"id" => $shortname."_part2_des",
			"std" => "",
			"type" => "text",);

$options[] = array( "name" => __('包含的分类-3','truethemes_localize'),
			"desc" => __('选择在主页第3块显示的分类名.','truethemes_localize'),
			"id" => $shortname."_part3",
			"std" => "",
			"type" => "select",
			"options" => $exclude_categories);
$options[] = array( "name" => __('分类-3 描述','truethemes_localize'),
			"desc" => __('选择在主页第3块显示的分类的描述.','truethemes_localize'),
			"id" => $shortname."_part3_des",
			"std" => "",
			"type" => "text",);

$options[] = array( "name" => __('包含的分类-4','truethemes_localize'),
			"desc" => __('选择在主页第4块显示的分类名.','truethemes_localize'),
			"id" => $shortname."_part4",
			"std" => "",
			"type" => "select",
			"options" => $exclude_categories);
$options[] = array( "name" => __('分类-4 描述','truethemes_localize'),
			"desc" => __('选择在主页第4块显示的分类的描述.','truethemes_localize'),
			"id" => $shortname."_part4_des",
			"std" => "",
			"type" => "text",);
			
			
//allow developer to add in new options to homepage settings.			
$options = apply_filters('theme_option_home_settings',$options);	



/*-----------------------------------------------------------------------------------*/
/* Create Site Options Array */
/*-----------------------------------------------------------------------------------*/			
			
			$options[] = array( "name" => __('SEO设置','truethemes_localize'),
			"type" => "heading");

$options[] = array( "name" => __('网站描述','truethemes_localize'),
			"desc" => __('填写你网站的描述内容，一般不超过200个字符'),
			"id" => $shortname."_description",
			"std" => "",
			"type" => "text");	

$options[] = array( "name" => __('网站关键词','truethemes_localize'),
			"desc" => __('填写你网站的关键词，一般不超过100个字符'),
			"id" => $shortname."_keywords",
			"std" => "",
			"type" => "text");	
						
			
//filter to allow developer to add new options to general settings.			
$options = apply_filters('theme_option_seo_settings',$options);	
		
			
$options[] = array( "name" => __('杂项','truethemes_localize'),
			"type" => "heading");

			
$options[] = array( "name" => __('首页 "点击进入" 按钮文字','truethemes_localize'),
			"desc" => __('','truethemes_localize'),
			"id" => $shortname."_enter_button",
			"std" => "点击进入",
			"type" => "text");
			
$options[] = array( "name" => __('"搜索" 按钮文字','truethemes_localize'),
			"desc" => __('','truethemes_localize'),
			"id" => $shortname."_search_button",
			"std" => "搜索",
			"type" => "text");

$options[] = array( "name" => __('"阅读更多" 按钮文字','truethemes_localize'),
			"desc" => __('','truethemes_localize'),
			"id" => $shortname."_readmore_button",
			"std" => "阅读更多",
			"type" => "text");

$options[] = array( "name" => __('底部版权信息','truethemes_localize'),
			"desc" => __('','truethemes_localize'),
			"id" => $shortname."_copyright",
			"std" => "Copyright © 2013 Geeku.Net",
			"type" => "text");
			

			
//allow developer to add in new options to forms.				
$options = apply_filters('theme_option_forms_settings',$options);			
			
						
/*			
			
			$options[] = array( "name" => __('Advanced Options','truethemes_localize'),
			"type" => "heading");
			
$options[] = array( "name" =>  __('Attention','truethemes_localize'),
					"desc" => "",
					"id" => $shortname."_custom_info_text",
					"std" => __('This section is intended for advanced users who wish to make significant design changes to the default theme. If you do not wish to make these types of changes you can simply ignore this entire section.','truethemes_localize'),
					"type" => "info");
					
/* $options[] = array( "name" =>  __('Background Color &rarr; Main Content Area",
					"desc" => __('Select a background color for the main content area.",
					"id" => $shortname."_main_content_background_color",
					"std" => "",
					"type" => "color"); /
					

$options[] = array( "name" =>  __('Font Color &rarr; Custom Logo','truethemes_localize'),
					"desc" => __('Select a font color for the custom logo.','truethemes_localize'),
					"id" => $shortname."_custom_logo_font_color",
					"std" => "",
					"type" => "color");
					
$options[] = array( "name" =>  __('Font Color &rarr; Main Menu','truethemes_localize'),
					"desc" => __('Select a font color for the main menu items.','truethemes_localize'),
					"id" => $shortname."_main_menu_font_color",
					"std" => "",
					"type" => "color");
					
					$options[] = array( "name" =>  __('Font Color &rarr; Main Content','truethemes_localize'),
					"desc" => __('Select a font color for the main content area.','truethemes_localize'),
					"id" => $shortname."_main_content_font_color",
					"std" => "",
					"type" => "color");

$options[] = array( "name" =>  __('Font Color &rarr; Footer Content','truethemes_localize'),
					"desc" => __('Select a font color for the footer content area.','truethemes_localize'),
					"id" => $shortname."_footer_content_font_color",
					"std" => "",
					"type" => "color");
					
					
$options[] = array( "name" =>  __('Font Color &rarr; Links','truethemes_localize'),
					"desc" => __('Select a font color for links.','truethemes_localize'),
					"id" => $shortname."_link_font_color",
					"std" => "",
					"type" => "color");
					
$options[] = array( "name" =>  __('Font Color &rarr; Link:Hover','truethemes_localize'),
					"desc" => __('Select a font color for links on hover.','truethemes_localize'),
					"id" => $shortname."_link_hover_font_color",
					"std" => "",
					"type" => "color");
					
$options[] = array( "name" =>  __('Font Color &rarr; Side Navigation','truethemes_localize'),
					"desc" => __('Select a font color for the side navigation items.','truethemes_localize'),
					"id" => $shortname."_side_menu_font_color",
					"std" => "",
					"type" => "color");
					
$options[] = array( "name" =>  __('Font Color &rarr; H1 Headings','truethemes_localize'),
					"desc" => __('Select a font color for all &lt;h1&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h1_font_color",
					"std" => "",
					"type" => "color");
					
$options[] = array( "name" =>  __('Font Color &rarr; H2 Headings','truethemes_localize'),
					"desc" => __('Select a font color for all &lt;h2&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h2_font_color",
					"std" => "",
					"type" => "color");
					
$options[] = array( "name" =>  __('Font Color &rarr; H3 Headings','truethemes_localize'),
					"desc" => __('Select a font color for all &lt;h3&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h3_font_color",
					"std" => "",
					"type" => "color");					

$options[] = array( "name" =>  __('Font Color &rarr; H4 Headings','truethemes_localize'),
					"desc" => __('Select a font color for all &lt;h4&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h4_font_color",
					"std" => "",
					"type" => "color");

$options[] = array( "name" =>  __('Font Color &rarr; H5 Headings','truethemes_localize'),
					"desc" => __('Select a font color for all &lt;h5&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h5_font_color",
					"std" => "",
					"type" => "color");


$options[] = array( "name" =>  __('Font Color &rarr; H6 Headings','truethemes_localize'),
					"desc" => __('Select a font color for all &lt;h6&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h6_font_color",
					"std" => "",
					"type" => "color");					
					

										


//start of font-size selectors.

//auto generate font size array from 9px to 50px.
//change numbers to increase or decrease sizes.
$font_sizes = array();
for($size = 9; $size < 51; $size ++){
$font_sizes[] = $size."px";
}

array_unshift($font_sizes,"--select--");										
					
$options[] = array( "name" => __('Font Size &rarr; Custom Logo','truethemes_localize'),
			"desc" => __('Select a font size for the custom logo.','truethemes_localize'),
			"id" => $shortname."_custom_logo_font_size",
			"std" => "--select--",
			"type" => "select",
			"options" => $font_sizes);	
			
$options[] = array( "name" => __('Font Size &rarr; Main Menu','truethemes_localize'),
			"desc" => __('Select a font size for the main menu items.','truethemes_localize'),
			"id" => $shortname."_main_menu_font_size",
			"std" => "--select--",
			"type" => "select",
			"options" => $font_sizes);
			

$options[] = array( "name" => __('Font Size &rarr; Main Content','truethemes_localize'),
			"desc" => __('Select a font size for the main content area.','truethemes_localize'),
			"id" => $shortname."_main_content_font_size",
			"std" => "--select--",
			"type" => "select",
			"options" => $font_sizes);
			
$options[] = array( "name" => __('Font Size &rarr; Side Navigation','truethemes_localize'),
			"desc" => __('Select a font size for side navigation items. headings.','truethemes_localize'),
			"id" => $shortname."_side_menu_font_size",
			"std" => "--select--",
			"type" => "select",
			"options" => $font_sizes);

$options[] = array( "name" =>  __('Font Size &rarr; H1 Headings','truethemes_localize'),
					"desc" => __('Select a font size for all &lt;h1&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h1_font_size",
					"std" => "--select--",
					"type" => "select",
					"options" => $font_sizes);				

$options[] = array( "name" =>  __('Font Size &rarr; H2 Headings','truethemes_localize'),
					"desc" => __('Select a font size for all &lt;h2&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h2_font_size",
					"std" => "--select--",
					"type" => "select",
					"options" => $font_sizes);	
					
$options[] = array( "name" =>  __('Font Size &rarr; H3 Headings','truethemes_localize'),
					"desc" => __('Select a font size for all &lt;h3&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h3_font_size",
					"std" => "--select--",
					"type" => "select",
					"options" => $font_sizes);			

$options[] = array( "name" =>  __('Font Size &rarr; H4 Headings','truethemes_localize'),
					"desc" => __('Select a font size for all &lt;h4&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h4_font_size",
					"std" => "--select--",
					"type" => "select",
					"options" => $font_sizes);	

$options[] = array( "name" =>  __('Font Size &rarr; H5 Headings','truethemes_localize'),
					"desc" => __('Select a font size for all &lt;h5&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h5_font_size",
					"std" => "--select--",
					"type" => "select",
					"options" => $font_sizes);	


$options[] = array( "name" =>  __('Font Size &rarr; H6 Headings','truethemes_localize'),
					"desc" => __('Select a font size for all &lt;h6&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h6_font_size",
					"std" => "--select--",
					"type" => "select",
					"options" => $font_sizes);
					
$options[] = array( "name" =>  __('Font Size &rarr; Footer Content','truethemes_localize'),
					"desc" => __('Select a font size for the footer content area. headings.','truethemes_localize'),
					"id" => $shortname."_footer_content_font_size",
					"std" => "--select--",
					"type" => "select",
					"options" => $font_sizes);



//array of all custom font types.
$font_types = array(
				'nofont',
				'Arial',
				'Arial Black',
				'Courier New',
				'Georgia',
				'Helvetica',
				'Impact',
				'Lucida Console',
				'Lucida Sans Unicode',
				'Tahoma',
				'Times New Roman',
				'Verdana',
				'MS Sans Serif',
				'Droid Sans',
				'Cabin',
				'Cantarell',
				'Cuprum',
				'Oswald',
				'Neuton',
				'Orbitron',
				'Arvo',
				'Kreon',
				'Indie Flower',
				'Josefin Sans'
				);
										
					
$options[] = array( "name" => __('Font Face &rarr; Custom Logo Text','truethemes_localize'),
			"desc" => __('Select a font face for your custom logo text.','truethemes_localize'),
			"id" => $shortname."_custom_logo_font",
			"std" => "nofont",
			"type" => "select",
			"options" => $font_types);											


$options[] = array( "name" => __('Font Face &rarr; Main Content','truethemes_localize'),
			"desc" => __('Select a font face for the main content area.','truethemes_localize'),
			"id" => $shortname."_main_content_font",
			"std" => "nofont",
			"type" => "select",
			"options" => $font_types);


$options[] = array( "name" => __('Font Face &rarr; Main Nenu','truethemes_localize'),
			"desc" => __('Select a font face for the main menu items.','truethemes_localize'),
			"id" => $shortname."_main_navigation_font",
			"std" => "nofont",
			"type" => "select",
			"options" => $font_types);

$options[] = array( "name" => __('Font Face &rarr; Side Navigation','truethemes_localize'),
			"desc" => __('Select a font face for the side navigation items.','truethemes_localize'),
			"id" => $shortname."_sidebar_menu_font",
			"std" => "nofont",
			"type" => "select",
			"options" => $font_types);


$options[] = array( "name" =>  __('Font Face &rarr; H1 Headings','truethemes_localize'),
					"desc" => __('Select a font face for all &lt;h1&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h1_font",
					"std" => "nofont",
					"type" => "select",
					"options" => $font_types);			

$options[] = array( "name" =>  __('Font Face &rarr; H2 Headings','truethemes_localize'),
					"desc" => __('Select a font face for all &lt;h2&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h2_font",
					"std" => "nofont",
					"type" => "select",
					"options" => $font_types);
					

$options[] = array( "name" =>  __('Font Face &rarr; H3 Headings','truethemes_localize'),
					"desc" => __('Select a font face for all &lt;h3&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h3_font",
					"std" => "nofont",
					"type" => "select",
					"options" => $font_types);


$options[] = array( "name" =>  __('Font Face &rarr; H4 Headings','truethemes_localize'),
					"desc" => __('Select a font face for all &lt;h4&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h4_font",
					"std" => "nofont",
					"type" => "select",
					"options" => $font_types);


$options[] = array( "name" =>  __('Font Face &rarr; H5 Headings','truethemes_localize'),
					"desc" => __('Select a font face for all &lt;h5&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h5_font",
					"std" => "nofont",
					"type" => "select",
					"options" => $font_types);


				
$options[] = array( "name" =>  __('Font Face &rarr; H6 Headings','truethemes_localize'),
					"desc" => __('Select a font face for all &lt;h6&gt; headings.','truethemes_localize'),
					"id" => $shortname."_h6_font",
					"std" => "nofont",
					"type" => "select",
					"options" => $font_types);	
				
$options[] = array( "name" =>  __('Font Face &rarr; Footer Content','truethemes_localize'),
					"desc" => __('Select a font face for the footer content area.','truethemes_localize'),
					"id" => $shortname."_footer_content_font",
					"std" => "nofont",
					"type" => "select",
					"options" => $font_types);
													
									
//allow developer to add in new options to Additional settings.			
$options = apply_filters('theme_option_additional_settings',$options);
*/			


update_option('of_template',$options); 					  
update_option('of_themename',$themename);   
update_option('of_shortname',$shortname);

}
}
?>