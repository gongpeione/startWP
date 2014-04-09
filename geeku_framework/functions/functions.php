<?php

/*--------------- [ wp_head() 的处理 ] -----------------*/

    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 ); //删除短链接shortlink
    remove_action( 'wp_head', 'wp_generator' ); //删除版权
    remove_action( 'wp_head', 'feed_links_extra', 3 ); //删除包含文章和评论的feed
    remove_action( 'wp_head', 'rsd_link' ); //删除外部编辑器
    remove_action( 'wp_head', 'wlwmanifest_link' ); //删除外部编辑器
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); //删除上一篇下一篇
    add_filter('show_admin_bar','__return_false'); //移除admin条
    foreach(array('comment_text','the_content','the_excerpt','the_title') as $xx)
    remove_filter($xx,'wptexturize'); //禁止半角符号自动变全角
    remove_filter('comment_text','capital_P_dangit',31);
    remove_filter('the_content','capital_P_dangit',11);
    remove_filter('the_title','capital_P_dangit',11); //禁止自动把’Wordpress’之类的变成’WordPress’
    add_filter( 'use_default_gallery_style', '__return_false' ); //禁用默认gallery样式


/*--------------- [ 载入必要的css/js文件 ] -----------------*/
    
    function geeku_enqueue_scripts() 
    {
        if( !is_admin() ) 
        {    
            wp_enqueue_style( 'style', bloginfo('stylesheet_url'), array(), '1.0', 'screen' );
            wp_enqueue_style( 'main', bloginfo('template_url') . '/geeku_framework/css/main.css', array(), '1.0', 'screen' );
            wp_deregister_script( 'jquery' );
            wp_register_script( 'jquery', 'http://lib.sinaapp.com/js/jquery/1.7.2/jquery.min.js', array(), '1.7.2', false );
            wp_enqueue_script( 'jquery' );
            wp_register_script( 'lazyload', bloginfo('template_url') . '/geeku_framework/js/lazyload.js', array(), '0', false );
            wp_enqueue_script( 'lazyload' );
            wp_register_script( 'slimbox', bloginfo('template_url') . '/geeku_framework/js/lazyload.js', array(), '0', false );
            wp_enqueue_script( 'slimbox' );        
        } 
    }
    add_action( 'init', 'geeku_enqueue_scripts' );

/*--------------- [ Lazyload ] -----------------*/

    /* codeFrom: http://www.tedlife.com/wordpress_zheng_que_de_shi_yong_jquery_lazyload_js.html*/
    add_filter('the_content', 'lazyload_filter_the_content');
    add_filter('wp_get_attachment_link', 'lazyload_filter_the_content');
    function lazyload_filter_the_content($content) 
    {
      if (is_feed() || is_robots()|| is_preview() || ( function_exists( 'is_mobile' ) && is_mobile() )) 
        return $content;
      
      return preg_replace_callback('/(< \s*img[^>]+)(src\s*=\s*"[^"]+")([^>]+>)/i', lazyload_preg_replace_callback, $content);
    }

    function lazyload_preg_replace_callback($matches) 
    {
        if (!preg_match('/class\s*=\s*"/i', $matches[0])) 
        {
          $class_attr = 'class="" ';
        }
        $replacement = $matches[1] . $class_attr . 'src="' . get_template_directory_uri() . '/images/grey.gif" data-original' . substr($matches[2], 3) . $matches[3];

        $replacement = preg_replace('/class\s*=\s*"/i', 'class="lazy ', $replacement);

        $replacement .= '<noscript>' . $matches[0] . '</noscript>';
        return $replacement;
    }

/*---------------- [ PostView ]------------------*/

    function getPostViews($postID)
    {
 
        //自定义域名称
        $count_key = 'post_views_count';
        //获取域值即浏览次数
        $count = get_post_meta($postID, $count_key, true);
        //如果为空表示没有点击过，返回0
        if($count=='')
        {
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0";
        }
        return $count;
    }

    //更新日志浏览数-参数文章ID
    function updatePostViews($postID) 
    {
        //自定义域名称
        $count_key = 'post_views_count';
        //获取域值即浏览次数
        $count = get_post_meta($postID, $count_key, true);
        //如果为空就设为1，表示第一次点击
        if($count=='')
        {
            $count = 1;
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, $count);
        }
        else
        {
            //如果不为空，加1，更新数据
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    };


/*---------------- [ 面包屑导航 ]------------------*/

    function get_breadcrumbs()
    {
        global $wp_query;
        
        if ( !is_home() )
        {
            // Start the UL
            echo '<ul class="breadcrumb">';
            // Add the Home lin
            echo '<li><a href="'. get_settings('home') .'">'. 首页 .'</a> <span class="divider">/</span></li>';
      
            if ( is_category() )
            {
                $catTitle = single_cat_title( "", false );
                $cat = get_cat_ID( $catTitle );
                echo "<li class='active'> ". get_category_parents( $cat, TRUE, " <span class='divider'>/</span>  " ) ."</li>";
            }
            elseif ( is_archive() && !is_category() )
            {
                echo "<li class='active'> Archives</li>";
            }
            elseif ( is_search() ) {
      
                echo "<li class='active'> Search Results</li>";
            }
            elseif ( is_404() )
            {
                echo "<li class='active'> 404 Not Found</li>";
            }
            elseif ( is_single() )
            {
                $category = get_the_category();
                $category_id = get_cat_ID( $category[0]->cat_name );
      
                echo '<li> '. get_category_parents( $category_id, TRUE, " <span class='divider'>/</span> " );
                echo "<li class='active'>".the_title('','', FALSE) ."</li>";
            }
            elseif ( is_page() )
            {
                $post = $wp_query->get_queried_object();
      
                if ( $post->post_parent == 0 ){
      
                    echo "<li> ".the_title('','', FALSE)."</li>";
      
                } else {
                    $title = the_title('','', FALSE);
                    $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
                    array_push($ancestors, $post->ID);
      
                    foreach ( $ancestors as $ancestor ){
                        if( $ancestor != end($ancestors) ){
                            echo '<li> <a href="'. get_permalink($ancestor) .'">'. strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) .'</a> <span class="divider">/</span></li>';
                        } else {
                            echo '<li> '. strip_tags( apply_filters( 'single_post_title', get_the_title( $ancestor ) ) ) .'</li>';
                        }
                    }
                }
            }
            
            // End the UL
            echo "</ul>";
        }
    }


/*---------------- [ 获取缩略图 ]------------------*/
    
    add_theme_support( 'post-thumbnails' );

    function post_thumbnail( $width = 100, $height = 80, $extraClass="", $moreInfo = "" )
    {
        global $post;
        if( has_post_thumbnail() )
        {    
            //如果有缩略图，则显示缩略图
            $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
            $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$timthumb_src[0].'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="' . $extraClass.'"  height="'.$height.'" width="'.$width.'" '.$moreInfo.' />';
            echo $post_timthumb;
        } 
        else 
        {
            $post_timthumb = '';
            ob_start();
            ob_end_clean();
            $output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $index_matches);    //获取日志中第一张图
            $first_img_src = $index_matches [1];    //获取该图src
            if( !empty($first_img_src) )
            {    //如果日志中有图片
                $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$first_img_src.'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="' . $extraClass.'"   height="'.$height.'" width="'.$width.'" '.$moreInfo.' />';
            } 
            else 
            {    //如果日志中没有图片，则显示默认
                 $post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.get_bloginfo("template_url").'/img/default.jpg&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="' . $extraClass.'"   height="'.$height.'" width="'.$width.'" '.$moreInfo.' />';
            }
            echo $post_timthumb;
        }
    };


/*---------------- [ Pagenavi ]------------------*/

    function gk_pagenavi( $p = 3 ) 
    {
          if ( is_singular() ) return;
          global $wp_query, $paged;
          $max_page = $wp_query->max_num_pages;
          if ( $max_page == 1 ) return;
          if ( empty( $paged ) ) $paged = 1;
          echo '<div class="wp-pagenavi pagination pull-center"><ul>';
          if ( $paged > 4 ) p_link( 1, '|<' );
          if ( $paged > 1 ) p_link( $paged-1, '«' );
          for( $i = $paged - $p ; $i <= $paged + $p ; $i++ ) {
            if ( $i > 0 && $i <= $max_page ) 
                $i == $paged ? print "<li><span class='current'>{$i}</span></li> " : p_link( $i );
          }
          if ( $paged < $max_page ) p_link( $paged+1, '»' );
          if ( $paged < $max_page-3 ) p_link( $max_page, '>|' );
          echo '</ul></div>';
    };
    function p_link( $i, $title = '' ) 
    {
          if ( $title == '' ) $title = "{$i}";
          echo "<li><a href='", esc_html( get_pagenum_link( $i ) ), "'>{$title}</a></li>";
    }; 


/*---------------- [ 短代码 ]------------------*/

    //警示
    function warningbox($atts, $content=null, $code="") 
    {	
    	$return = '<div class="warning shortcodestyle">';	
    	$return .= $content;	
    	$return .= '</div>';	
    	return $return;
    }
    add_shortcode('warning' , 'warningbox' );

    //禁止
    function nowaybox($atts, $content=null, $code="") 
    {	
    	$return = '<div class="noway shortcodestyle">';	
    	$return .= $content;	
    	$return .= '</div>';	
    	return $return;
    }
    add_shortcode('noway' , 'nowaybox' );

    //购买
    function buybox($atts, $content=null, $code="") 
    {	
    	$return = '<div class="buy shortcodestyle">';	
    	$return .= $content;	
    	$return .= '</div>';	
    	return $return;
    }
    add_shortcode('buy' , 'buybox' );

    //项目版
    function taskbox($atts, $content=null, $code="") 
    {	
    	$return = '<div class="task shortcodestyle">';	
    	$return .= $content;	
    	$return .= '</div>';	
    	return $return;
    }
    add_shortcode('task' , 'taskbox' );

    //音乐播放器
    function doubanplayer($atts, $content=null)
    {	
    	extract(shortcode_atts(array("auto"=>'0'),$atts));	
    	return '<embed src="'.get_bloginfo("template_url").'/geeku_framework/other/doubanplayer.swf?url='.$content.'&amp;autoplay='.$auto.'" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" width="400" height="30">';	}add_shortcode('music','doubanplayer');

    //mp3专用播放器
    function mp3link($atts, $content=null)
    {	
    	extract(shortcode_atts(array("auto"=>'0',"replay"=>'0',),$atts));	 
    	return '<embed src="'.get_bloginfo("template_url").'/geeku_framework/other/dewplayer.swf?mp3='.$content.'&amp;autostart='.$auto.'&amp;autoreplay='.$replay.'" wmode="transparent" height="20" width="240" type="application/x-shockwave-flash" />';	
    }
    add_shortcode('mp3','mp3link');	



/*---------------- [ 后台编辑器添加按钮 ]------------------*/

function add_quicktags() 
{

?> 

<script type="text/javascript"> 
QTags.addButton( 'gray', '灰色面板', '[task][/task]', '' ); 
QTags.addButton( 'red', '红色禁止', '[noway][/noway]', '' ); 
QTags.addButton( 'yellow', '黄色警告', '[warning][/warning]', '' ); 
QTags.addButton( 'music', '音乐播放器', '[music][/music]', '' ); 
QTags.addButton( 'mp3', 'mp3播放器', '[mp3][/mp3]', '' ); 
</script>

<?php
}
add_action('admin_print_footer_scripts', 'add_quicktags' );

/*---------------- [ 后台编辑器添加按钮 ]------------------*/

    /* HowToUse: <body <?php body_class(); ?>>*/
    /* CodeFrom: http://www.tedlife.com/add_user_browser_and_os_classes_in_wordpress_body_class.html*/
    function mv_browser_body_class($classes) 
    {
            global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
            if($is_lynx) $classes[] = 'lynx';
            elseif($is_gecko) $classes[] = 'gecko';
            elseif($is_opera) $classes[] = 'opera';
            elseif($is_NS4) $classes[] = 'ns4';
            elseif($is_safari) $classes[] = 'safari';
            elseif($is_chrome) $classes[] = 'chrome';
            elseif($is_IE) 
            {
                    $classes[] = 'ie';
                    if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
                    $classes[] = 'ie'.$browser_version[1];
            } 
            else $classes[] = 'unknown';
            if($is_iphone) $classes[] = 'iphone';
            if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) 
            {
                $classes[] = 'osx';
            } 
            elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) 
            {
                $classes[] = 'linux';
            } 
            elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) 
            {
                $classes[] = 'windows';
            }
            return $classes;
    }
    add_filter('body_class','mv_browser_body_class');