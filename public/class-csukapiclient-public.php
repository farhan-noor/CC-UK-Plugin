<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://linkedin.com/in/farhan-noor
 * @since      1.0.0
 *
 * @package    Csukapiclient
 * @subpackage Csukapiclient/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Csukapiclient
 * @subpackage Csukapiclient/public
 * @author     Farhan Noor <farhan.noor@wpreloaded.com>
 */
class Csukapiclient_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/csukapiclient-public.css', array(), $this->version, 'all' );
                wp_enqueue_style( $this->plugin_name.'-modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.2/jquery.modal.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/csukapiclient-public.js', array( 'jquery' ), $this->version, false );
                wp_enqueue_script( $this->plugin_name.'-modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.2/jquery.modal.min.js', array( 'jquery' ), $this->version, false );
                $aol_js_vars = array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'ccuk_nonce' => wp_create_nonce('the_best_aol_ad_security_nonce', 'wp_nonce', true, false),
                );
                wp_localize_script (
                    $this->plugin_name,
                    'ccuk_public', 
                    $aol_js_vars
                );
	}
        
        function security_check($nonce = NULL){
            
            $nonce = is_null($nonce) ? $_POST['wp_nonce'] : $nonce;

            if(!wp_verify_nonce($nonce, 'the_best_aol_ad_security_nonce')){
                header( "Content-Type: application/json" );
                echo json_encode( array( 'success' => false, 'error' => __( 'Session Expired, please reload page and try again.', 'ccuk' ) ));
                exit;
            }
        }
        
        function search_form($content){
            ob_start(); ?>
                <!-- Modal HTML -->
                <div id="ccuk-modal" class="modal">
                  <a href="#" rel="modal:close"><?php _e('Close', 'ccuk'); ?></a>
                </div>

                <form id="ccuk_search_form">
                    <input type="text" name="ccuk_search_term" /> 
                    <select name="ccuk_search_type"> 
                        <option value="name">By Name</option>
                        <option value="keyword">By Keyword</option>
                        <option value="id">By Registration</option>
                    </select>
                    <input type="submit" class="ccuk_search_button" value="Search Charities" />
                    <input type="hidden" name="action" value="ccuk_fetch_charities" />
                    <?php wp_nonce_field('the_best_aol_ad_security_nonce', 'wp_nonce'); ?>
                </form>
                <p id="ccuk_form_status"></p>
                <table id="ccuk_output" style="display:none">
                    <thead>
                        <tr>
                            <th>Charity Name</th>
                            <th>Registration</th>
                            <th colspan="2">Status</th>
                        </tr>                        
                    </thead>
                    <tbody></tbody>
                </table>
            <?php
            
            return $content.ob_get_clean();
        }

        function get_charities($content){
            $this->security_check();
            $term = sanitize_text_field($_POST['ccuk_search_term']);
            if(empty($term)){
                $response = json_encode( array( 'success' => false, 'error' => 'Please provide a search term.' ));    //generate the error response.
                //response output
                header( "Content-Type: application/json" );
                die($response);
                exit;            
            }
            //$defaults = array('name' => 'GetCharitiesByName', 'id'=>'GetCharityByRegisteredCharityNumber', 'keyword' => 'GetCharitiesByKeyword');
            $type = sanitize_key($_POST['ccuk_search_type']);
            //$type = array_key_exists($type, $defaults) ? $defaults[$type] : 'GetCharitiesByName';
            switch ($type) {
                case 'id':
                    $action = 'GetCharityByRegisteredCharityNumber';
                    $action_term = 'registeredCharityNumber';
                    break;
                case 'keyword':
                    $action = 'GetCharitiesByKeyword';
                    $action_term = 'strSearch';
                    break;
                case 'name':
                    $action = 'GetCharitiesByName';
                    $action_term = 'strSearch';
            }
            $url = 'https://apps.charitycommission.gov.uk/Showcharity/API/SearchCharitiesV1/SearchCharitiesV1.asmx';
            $header = array( 'Content-Type' => 'text/xml');
            
            ob_start(); ?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                  <soap:Body>
                    <<?php echo $action; ?> xmlns="http://www.charitycommission.gov.uk/">
                      <APIKey><?php echo sanitize_text_field(get_option('ccuk_api_key')); ?></APIKey>
                      <<?php echo $action_term; ?>><?php echo $term; ?></<?php echo $action_term; ?>>
                    </<?php echo $action; ?>>
                  </soap:Body>
                </soap:Envelope>
            <?php
            
            $body = ob_get_clean();
            $response = wp_remote_post($url,  array(
                'headers' => $header,
                //'timeout'   => 30,
                'body' => $body,
                )
            );
           $response = str_replace('soap:', '', $response['body']);
           $xml = new SimpleXMLElement($response);
           $action_response = $action.'Response';
           $action_result = $action.'Result';
           $body = ($type == 'id') ? array('CharityList' => array($xml->xpath('//Body')[0]->GetCharityByRegisteredCharityNumberResponse->GetCharityByRegisteredCharityNumberResult)) : $xml->xpath('//Body')[0]->$action_response->$action_result;
           //$array = json_decode(json_encode((array)$body), TRUE); 

           $response = json_encode( array( 'success' => true, 'message' => __('All Done', 'CCUK'), 'data' => $body ));
           //response output
           header( "Content-Type: application/json" );
           die($response);
           exit;
        }
        
        function get_charity(){
            $this->security_check();
            $url = 'https://apps.charitycommission.gov.uk/Showcharity/API/SearchCharitiesV1/SearchCharitiesV1.asmx';
            $header = array( 'Content-Type' => 'text/xml');
            
            ob_start(); ?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                  <soap:Body>
                    <GetCharityByRegisteredCharityNumber  xmlns="http://www.charitycommission.gov.uk/">
                      <APIKey><?php echo sanitize_text_field(get_option('ccuk_api_key')); ?></APIKey>
                      <registeredCharityNumber><?php echo sanitize_text_field($_POST['id']); ?></registeredCharityNumber>
                    </GetCharityByRegisteredCharityNumber >
                  </soap:Body>
                </soap:Envelope>
            <?php
            
            $body = ob_get_clean();
            $response = wp_remote_post($url,  array(
                'headers' => $header,
                //'timeout'   => 30,
                'body' => $body,
                )
            );
           $response = str_replace('soap:', '', $response['body']);
           $xml = new SimpleXMLElement($response);
           $body = $xml->xpath('//Body')[0]->GetCharityByRegisteredCharityNumberResponse->GetCharityByRegisteredCharityNumberResult;
           $body = json_decode(json_encode((array)$body), TRUE);
           $return_data = array(
               'CharityNumber',
               'CharityName',
               'WorkingNames',
               'ContactName',
               'Trustees',
               'Address',
               'PublicTelephoneNumber',
               'EmailAddress',
               'WebsiteAddress',
               'AreaOfBenefit',
               'GoverningDocument',
               'CharitableObjects',
               'Activities',
               'AreaOfOperation',
               );
           //$body = array_intersect_key($return_data, $body);
           $final_body = array();
           foreach($return_data as $key){
               $final_body[$key] = $this->maybe_array($body[$key]);
           }
           //print_rich($body); die();
           //response output
           header( "Content-Type: application/json" );
           die(json_encode( $final_body ));
           exit;            
        }
        
        function maybe_array($value){
            $result = null;
            if(is_array($value)){
                foreach($value as $key => $val){
                    $result .= is_array($val) ? $this->maybe_array($val) : $key. ': ' .$val.'<br />';
                }
            } else{
                $result = $value;
            }
            return '<p>'.$result.'</p>';
        }
}
