<?php 
    
    /*--------------- [ 引入GEEKU_FRAMEWORK ] -----------------*/

    require_once(TEMPLATEPATH . '/geeku_framework/framework_init.php');


    /*--------------- [ 网站临时维护 ] -----------------*/

    /*function wp_maintenance_mode(){
        if(!current_user_can('edit_themes') || !is_user_logged_in()){
            wp_die('<meta charset="UTF8" />
网站临时维护中，请稍后...', '网站临时维护中，请稍后...', array('response' => '503'));
        }
    }
    add_action('get_header', 'wp_maintenance_mode');*/


    /*--------------- [ 自定义登录失败的信息 ] -----------------*/

    /***********************************************
    *                增强网站安全性。
	*  我们知道，当我们输入一个存在的用户名，但是输入错误的密码
    *  wordpress默认会返回的信息是该账户的密码不正确，
    *  这就等于告诉黑客确实存在这样的账户，方便了黑客进行下一步行动。
	***********************************************/

    function failed_login() {
        return 'Incorrect login information.';
    }
    add_filter('login_errors', 'failed_login');


    /*--------------- [ 侧边栏 ] -----------------*/

    if ( function_exists('register_sidebar') ) {   
      register_sidebar(array(   
        'name'          =>'文章页侧边栏', 
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="parts %2$s">',   
        'after_widget'  => '</div>',   
        'before_title'  => '<h3>',   
        'after_title'   => '</h3>',   
      ));  

      register_sidebar(array(   
        'name'          =>'底部侧边栏', 
        'id'            => 'sidebar-2',
        'before_widget' => '<div id="%1$s" class="sidebar-f span4 %2$s">',  
        'after_widget'  => '</div>',   
        'before_title'  => '<h4>',   
        'after_title'   => '</h4>',   
      ));    
     }  


    /*--------------- [ 菜单 ] -----------------*/

    add_theme_support('nav-menus');

    if( function_exists('register_nav_menus') )
    {   
        register_nav_menus( array( 'homepage' => __( '主页菜单' ),'friends' => __( '友情链接' )  ) );
    }

?>