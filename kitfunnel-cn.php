<?php
/*
Plugin Name: KitFunnel CN
Plugin URI: https://kitfunnel.com
Description: Personalización CN
Version: 1.1.25
Author: KitFunnel
License: GPL 2+
License URI: https://kitfunnel.com */ 

require_once "kitfunnelcn-base.php";
class KitFunnelCN {
    public $plugin_file=__FILE__;
    public $response_obj;
    public $license_message;
    public $show_message=false;
    public $slug="kitfunnel-cn";
    public $plugin_version='';
    public $text_domain='';
    function __construct() {
        add_action( 'admin_print_styles', [ $this, 'set_admin_style' ] );
        $this->set_plugin_data();
	    $main_lic_key="KitFunnelCN_lic_Key";
	    $lic_key_name =Kit_Funnel_C_N_Base::get_lic_key_param($main_lic_key);
        $license_key=get_option($lic_key_name,"");
        if(empty($license_key)){
	        $license_key=get_option($main_lic_key,"");
	        if(!empty($license_key)){
	            update_option($lic_key_name,$license_key) || add_option($lic_key_name,$license_key);
            }
        }
        $lice_email=get_option( "KitFunnelCN_lic_email","");
        Kit_Funnel_C_N_Base::add_on_delete(function(){
           update_option("KitFunnelCN_lic_Key","");
        });
        if(Kit_Funnel_C_N_Base::check_wp_plugin($license_key,$lice_email,$this->license_message,$this->response_obj,__FILE__)){
            add_action( 'admin_menu', [$this,'active_admin_menu'],99999);
            add_action( 'admin_post_KitFunnelCN_el_deactivate_license', [ $this, 'action_deactivate_license' ] );
            //$this->licenselMessage=$this->mess;



add_action('admin_enqueue_scripts','bs_custom_admin_styles');function bs_custom_admin_styles(){$css_version='2.8';$css_url=add_query_arg('v',$css_version,plugins_url('/css/styles-admin.css',__FILE__));wp_enqueue_style('custom-admin-styles',$css_url);}add_action('login_enqueue_scripts','bs_custom_login_styles');function bs_custom_login_styles(){$css_version='2.4';$css_url=add_query_arg('v',$css_version,plugins_url('/css/styles-login.css',__FILE__));wp_enqueue_style('custom-login-styles',$css_url);}add_action('wp_enqueue_scripts','bs_custom_theme_styles');function bs_custom_theme_styles(){$css_version='4.10';$css_url=add_query_arg('v',$css_version,plugins_url('/css/styles-theme.css',__FILE__));wp_enqueue_style('custom-theme-styles',$css_url);}add_action('check_admin_referer','logout_without_confirm',10,2);function logout_without_confirm($action,$result){if($action=="log-out"&&!isset($_GET['_wpnonce'])){$redirect_to=isset($_REQUEST['redirect_to'])?$_REQUEST['redirect_to']:'/cn/account/?display=inicio';$location=str_replace('&amp;','&',wp_logout_url($redirect_to));header("Location: $location");die;}}add_action('wp_login_failed','elementor_form_login_fail',9999999);function elementor_form_login_fail($username){$referrer=wp_get_referer();if(!empty($referrer)&&!strpos($referrer,'wp-login')&&!strpos($referrer,'wp-admin')){wp_redirect(remove_query_arg(array('display'),$referrer).'?display=error-login');exit;}}add_action('wp_authenticate','elementor_form_login_empty',10,2);function elementor_form_login_empty($username,$pwd){$referrer=wp_get_referer();if(empty($username)||empty($pwd)){if(!strpos($referrer,'wp-login')&&!strpos($referrer,'wp-admin')){wp_redirect(remove_query_arg(array('display'),$referrer).'?display=error-login');exit;}}}function add_custom_script_my_footer(){if(has_elementor_page_class('elementor-page-60')){ ?><script>document.addEventListener("DOMContentLoaded",function(){document.getElementById("password").setAttribute("type","password")})</script><?php }}add_action('wp_footer','add_custom_script_my_footer');function add_custom_script_to_footer(){if(has_elementor_page_class('elementor-page-60')){ ?><script>function updateToggleButtonIcon(t,e){var n="password"===e.type?"<?php echo home_url(); ?>/wp-content/plugins/kitfunnel-cn/img/view.png":"<?php echo home_url(); ?>/wp-content/plugins/kitfunnel-cn/img/view-of.png";t.innerHTML='<img src="'+n+'" alt="Mostrar Contraseña" style="width: 20px; height: 20px;">'}document.addEventListener("DOMContentLoaded",function(){var t=document.getElementById("password"),e=t.parentNode,n=document.createElement("button");n.setAttribute("type","button"),n.setAttribute("class","show-pass"),n.style.cursor="pointer",n.style.zIndex="99",updateToggleButtonIcon(n,t),e.appendChild(n),n.addEventListener("click",function(){"password"===t.type?t.type="text":t.type="password",updateToggleButtonIcon(n,t)})})</script><?php }}add_action('wp_footer','add_custom_script_to_footer');function has_elementor_page_class($class){$classes=get_body_class();return in_array($class,$classes);}add_action('admin_print_styles-edit.php','set_edit_columns_width_cursos_online');function set_edit_columns_width_cursos_online(){if(get_current_screen()->post_type==='lecciones'){echo '<style> .column-completable-course { width: 140px !important; } .column-categoria { width: 190px !important; } .column-id { width: 60px !important; } </style>';}}function my_favicon(){$options=get_option('media-customization');echo '<link rel="shortcut icon" href="'.$options['favicon-sitio-pt'].'" />';}add_action('wp_head','my_favicon');function my_login_logo(){$options=get_option('media-customization');$logo_url=$options['logo-alternativo-sitio-pt'];echo '<style type="text/css"> .login h1 a { background-image: url('.$logo_url.') !important; } </style>';}add_action('login_enqueue_scripts','my_login_logo');add_filter('login_headerurl','bs_login_logo_link');function bs_login_logo_link($url){return home_url();}add_filter('post_row_actions','wpcode_snippet_duplicate_post_link',10,2);add_filter('page_row_actions','wpcode_snippet_duplicate_post_link',10,2);if(!function_exists('wpcode_snippet_duplicate_post_link')){function wpcode_snippet_duplicate_post_link($actions,$post){if('lecciones'!==$post->post_type&&'cursos'!==$post->post_type){return $actions;}$post_type_object=get_post_type_object($post->post_type);if(null===$post_type_object||!current_user_can($post_type_object->cap->create_posts)){return $actions;}$url=wp_nonce_url(add_query_arg(array('action'=>'wpcode_snippet_duplicate_post','post_id'=>$post->ID,),'admin.php'),'wpcode_duplicate_post_'.$post->ID,'wpcode_duplicate_nonce');$actions['wpcode_duplicate']='<a href="'.$url.'" title="Duplicar elemento" rel="permalink">Duplicar</a>';return $actions;}}add_action('admin_action_wpcode_snippet_duplicate_post',function(){if(empty($_GET['post_id'])){wp_die('No se ha establecido el ID de la entrada para la acción de duplicar.');}$post_id=absint($_GET['post_id']);if(!isset($_GET['wpcode_duplicate_nonce'])||!wp_verify_nonce($_GET['wpcode_duplicate_nonce'],'wpcode_duplicate_post_'.$post_id)){wp_die('El enlace que has seguido ha caducado, por favor, inténtalo de nuevo.');}$post=get_post($post_id);if($post&&('lecciones'===$post->post_type||'cursos'===$post->post_type)){$current_user=wp_get_current_user();$new_post=array('comment_status'=>$post->comment_status,'menu_order'=>$post->menu_order,'ping_status'=>$post->ping_status,'post_author'=>$current_user->ID,'post_content'=>$post->post_content,'post_excerpt'=>$post->post_excerpt,'post_name'=>$post->post_name,'post_parent'=>$post->post_parent,'post_password'=>$post->post_password,'post_status'=>'draft','post_title'=>$post->post_title.' (copia)','post_type'=>$post->post_type,'to_ping'=>$post->to_ping,);$duplicate_id=wp_insert_post($new_post);$taxonomias=get_object_taxonomies($post->post_type);foreach($taxonomias as $taxonomia){$terminos_post=wp_get_object_terms($post_id,$taxonomia,array('fields'=>'slugs'));wp_set_object_terms($duplicate_id,$terminos_post,$taxonomia);}$meta_post=get_post_meta($post_id);foreach($meta_post as $clave_meta=>$valores_meta){if('_wp_old_slug'===$clave_meta||'wpcomplete'===$clave_meta){continue;}foreach($valores_meta as $valor_meta){add_post_meta($duplicate_id,$clave_meta,$valor_meta);}}wp_safe_redirect(add_query_arg(array('action'=>'edit','post'=>$duplicate_id),admin_url('post.php')));exit;}else{wp_die('Error al cargar la entrada para duplicar, por favor, inténtalo de nuevo.');}});



        }else{
            if(!empty($license_key) && !empty($this->license_message)){
               $this->show_message=true;
            }
            update_option($license_key,"") || add_option($license_key,"");
            add_action( 'admin_post_KitFunnelCN_el_activate_license', [ $this, 'action_activate_license' ] );
            add_action( 'admin_menu', [$this,'inactive_menu']);
        }
    }
    public function set_plugin_data(){
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( function_exists( 'get_plugin_data' ) ) {
			$data = get_plugin_data( $this->plugin_file );
			if ( isset( $data['Version'] ) ) {
				$this->plugin_version = $data['Version'];
			}
			if ( isset( $data['TextDomain'] ) ) {
				$this->text_domain = $data['TextDomain'];
			}
		}
    }
	private static function &get_server_array() {
		return $_SERVER;
	}
	private static function get_raw_domain(){
		if(function_exists("site_url")){
			return site_url();
		}
		if ( defined( "WPINC" ) && function_exists( "get_bloginfo" ) ) {
			return get_bloginfo( 'url' );
		} else {
			$server = self::get_server_array();
			if ( ! empty( $server['HTTP_HOST'] ) && ! empty( $server['SCRIPT_NAME'] ) ) {
				$base_url  = ( ( isset( $server['HTTPS'] ) && $server['HTTPS'] == 'on' ) ? 'https' : 'http' );
				$base_url .= '://' . $server['HTTP_HOST'];
				$base_url .= str_replace( basename( $server['SCRIPT_NAME'] ), '', $server['SCRIPT_NAME'] );
				
				return $base_url;
			}
		}
		return '';
	}
	private static function get_raw_wp(){
		$domain=self::get_raw_domain();
		return preg_replace("(^https?://)", "", $domain );
	}
	public static function get_lic_key_param($key){
		$raw_url=self::get_raw_wp();
		return $key."_s".hash('crc32b',$raw_url."vtpbdapps");
	}
	public function set_admin_style() {
        wp_register_style( "KitFunnelCNLic", plugins_url("_lic_style.css",$this->plugin_file),10,time());
        wp_enqueue_style( "KitFunnelCNLic" );
    }
	public function active_admin_menu(){
        
		add_menu_page (  "KitFunnelCN", "KitFunnel CN", "activate_plugins", $this->slug, [$this,"activated"], " dashicons-screenoptions ");
		//add_submenu_page(  $this->slug, "KitFunnelCN License", "License Info", "activate_plugins",  $this->slug."_license", [$this,"activated"] );

    }
	public function inactive_menu() {
        add_menu_page( "KitFunnelCN", "KitFunnel CN", 'activate_plugins', $this->slug,  [$this,"license_form"], " dashicons-screenoptions " );

    }
    function action_activate_license(){
        check_admin_referer( 'el-license' );
        $license_key=!empty($_POST['el_license_key'])?sanitize_text_field(wp_unslash($_POST['el_license_key'])):"";
        $license_email=!empty($_POST['el_license_email'])?sanitize_email(wp_unslash($_POST['el_license_email'])):"";
        update_option("KitFunnelCN_lic_Key",$license_key) || add_option("KitFunnelCN_lic_Key",$license_key);
        update_option("KitFunnelCN_lic_email",$license_email) || add_option("KitFunnelCN_lic_email",$license_email);
        update_option('_site_transient_update_plugins','');
        wp_safe_redirect(admin_url( 'admin.php?page='.$this->slug));
    }
    function action_deactivate_license() {
        check_admin_referer( 'el-license' );
        $message="";
	    $main_lic_key="KitFunnelCN_lic_Key";
	    $lic_key_name =Kit_Funnel_C_N_Base::get_lic_key_param($main_lic_key);
        if(Kit_Funnel_C_N_Base::remove_license_key(__FILE__,$message)){
            update_option($lic_key_name,"") || add_option($lic_key_name,"");
            update_option('_site_transient_update_plugins','');
        }
        wp_safe_redirect(admin_url( 'admin.php?page='.$this->slug));
    }
    function activated(){
        ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="KitFunnelCN_el_deactivate_license"/>
            <div class="el-license-container">
                <h3 class="el-license-title"><i class="dashicons-before dashicons-screenoptions"></i> <?php esc_html_e("KitFunnel CN","kitfunnel-cn");?> </h3>
                <hr>
                <ul class="el-license-info">
                <li>
                    <div>
                        <span class="el-license-info-title"><?php esc_html_e("Status","kitfunnel-cn");?></span>

                        <?php if ( $this->response_obj->is_valid ) : ?>
                            <span class="el-license-valid"><?php esc_html_e("Valid","kitfunnel-cn");?></span>
                        <?php else : ?>
                            <span class="el-license-valid"><?php esc_html_e("Invalid","kitfunnel-cn");?></span>
                        <?php endif; ?>
                    </div>
                </li>

                <li>
                    <div>
                        <span class="el-license-info-title"><?php esc_html_e("License Type","kitfunnel-cn");?></span>
                        <?php echo esc_html($this->response_obj->license_title,"kitfunnel-cn"); ?>
                    </div>
                </li>

               <li>
                   <div>
                       <span class="el-license-info-title"><?php esc_html_e("License Expired on","kitfunnel-cn");?></span>
                       <?php echo esc_html($this->response_obj->expire_date,"kitfunnel-cn");
                       if(!empty($this->response_obj->expire_renew_link)){
                           ?>
                           <a target="_blank" class="el-blue-btn" href="<?php echo esc_url($this->response_obj->expire_renew_link); ?>">Renew</a>
                           <?php
                       }
                       ?>
                   </div>
               </li>

               <li>
                   <div>
                       <span class="el-license-info-title"><?php esc_html_e("Support Expired on","kitfunnel-cn");?></span>
                       <?php
                           echo esc_html($this->response_obj->support_end,"kitfunnel-cn");;
                        if(!empty($this->response_obj->support_renew_link)){
                            ?>
                               <a target="_blank" class="el-blue-btn" href="<?php echo esc_url($this->response_obj->support_renew_link); ?>">Renew</a>
                            <?php
                        }
                       ?>
                   </div>
               </li>
                <li>
                    <div>
                        <span class="el-license-info-title"><?php esc_html_e("Your License Key","kitfunnel-cn");?></span>
                        <span class="el-license-key"><?php echo esc_attr( substr($this->response_obj->license_key,0,9)."XXXXXXXX-XXXXXXXX".substr($this->response_obj->license_key,-9) ); ?></span>
                    </div>
                </li>
                </ul>
                <div class="el-license-active-btn">
                    <?php wp_nonce_field( 'el-license' ); ?>
                    <?php submit_button('Desactivar'); ?>
                </div>
            </div>
        </form>
    <?php
    }

    function license_form() {
        ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="KitFunnelCN_el_activate_license"/>
        <div class="el-license-container">
            <h3 class="el-license-title"><i class="dashicons-before dashicons-screenoptions"></i> <?php esc_html_e("KitFunnel CN","kitfunnel-cn");?></h3>
            <hr>
            <?php
            if(!empty($this->show_message) && !empty($this->license_message)){
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo esc_html($this->license_message,"kitfunnel-cn"); ?></p>
                </div>
                <?php
            }
            ?>
            <p><?php esc_html_e("Ingresa tu clave de licencia y correo electrónico de compra para activar KitFunnel CN y habilitar las actualizaciones.","kitfunnel-cn");?></p>
			<p><br></p>

            <div class="el-license-field">
                <label for="el_license_key"><?php echo esc_html("Código de licencia","kitfunnel-cn");?></label>
                <input type="text" class="regular-text code" name="el_license_key" size="50" placeholder="xxxxxxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx" required="required">
            </div>
            <div class="el-license-field">
                <label for="el_license_key"><?php echo esc_html("Email","kitfunnel-cn");?></label>
                <?php
                    $purchase_email   = get_option( "KitFunnelCN_lic_email", get_bloginfo( 'admin_email' ));
                ?>
                <input type="text" class="regular-text code" name="el_license_email" size="50" value="<?php echo esc_html($purchase_email); ?>" placeholder="" required="required">
                <div><small><?php echo esc_html("Agrega el correo electrónico de registro cuando adquiriste KitFunnel.","kitfunnel-cn");?></small></div>
            </div>
            <div class="el-license-active-btn">
                <?php wp_nonce_field( 'el-license' ); ?>
                <?php submit_button('Activar ahora'); ?>
            </div>
        </div>
    </form>
        <?php
    }
}

new KitFunnelCN();