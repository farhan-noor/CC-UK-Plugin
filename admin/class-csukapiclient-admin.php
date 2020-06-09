<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://linkedin.com/in/farhan-noor
 * @since      1.0.0
 *
 * @package    Csukapiclient
 * @subpackage Csukapiclient/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Csukapiclient
 * @subpackage Csukapiclient/admin
 * @author     Farhan Noor <farhan.noor@wpreloaded.com>
 */
class Csukapiclient_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Csukapiclient_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Csukapiclient_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/csukapiclient-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Csukapiclient_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Csukapiclient_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/csukapiclient-admin.js', array( 'jquery' ), $this->version, false );

	}
        
        function admin_menu(){
            add_menu_page( __('Charity', 'ApplyOnline'), _x('Charity', 'Admin Menu', 'ApplyOnline'), 'manage_options', 'ccukapi-settings', array($this, 'tab_general'), 'dashicons-heart',31 );
        }
        
        public function registers_settings(){
            register_setting( 'ccukapi_settings', 'ccuk_page' );
            register_setting( 'ccukapi_settings', 'ccuk_project_id' );
            register_setting( 'ccukapi_settings', 'ccuk_project_name' );
            register_setting( 'ccukapi_settings', 'ccuk_api_key' );
        }

    public function tab_general(){
        ?>
            <form action="options.php" method="post" name="">
                <table class="form-table">
                <?php
                    settings_fields( 'ccukapi_settings' ); 
                    do_settings_sections( 'ccukapi_settings' );
                ?>
                    <tr>
                        <th><label for="thanks-page"><?php _e('Charity Listing Page', 'ApplyOnline'); ?></label></th>
                        <td>
                            <select id="thank-page" class="aol-select2" style="width: 330px" name="ccuk_page">
                                <option value=""><?php _e('Not selected', 'ApplyOnline'); ?></option> 
                                <?php 
                                $selected = get_option('ccuk_page');

                                 $pages = get_pages();
                                 foreach ( $pages as $page ) {
                                     $attr = null;
                                     if($selected == $page->ID) $attr = 'selected';

                                       $option = '<option value="' . (int)$page->ID . '" '.$attr.'>';
                                       $option .= sanitize_text_field($page->post_title);
                                       $option .= '</option>';
                                       echo $option;
                                 }
                                ?>
                           </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="ccuk_project_id"><?php _e('Project ID', 'ApplyOnline'); ?></label></th>
                        <td>
                            <input type="text" id="ccuk_project_id" class="regular-text" name="ccuk_project_id" value="<?php echo (int)get_option('ccuk_project_id', 0); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="ccuk_project_name"><?php _e('Project Name', 'ApplyOnline'); ?></label></th>
                        <td>
                            <input type="text" id="ccuk_project_name" class="regular-text" name="ccuk_project_name" value="<?php echo sanitize_text_field(get_option('ccuk_project_name')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="ccuk_api_key"><?php _e('API Key', 'ApplyOnline'); ?></label></th>
                        <td>
                            <input type="text" id="ccuk_api_key" class="regular-text" name="ccuk_api_key" value="<?php echo sanitize_text_field(get_option('ccuk_api_key')); ?>">
                            <p class="description"><?php _e('Please check your Charity Commission UK account for the API key', 'ApplyOnline'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <?php 
    }
        
}
