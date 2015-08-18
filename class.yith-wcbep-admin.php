<?php
/**
 * Admin class
 *
 * @author Yithemes
 * @package YITH WooCommerce Bulk Edit Products
 * @version 1.0.0
 */

if ( !defined( 'YITH_WCBEP' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WCBEP_Admin' ) ) {
    /**
     * Admin class.
	 * The class manage all the admin behaviors.
     *
     * @since 1.0.0
     * @author   Leanza Francesco <leanzafrancesco@gmail.com>
     */
    class YITH_WCBEP_Admin {
		
        /**
         * Single instance of the class
         *
         * @var \YITH_WCQV_Admin
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Plugin options
         *
         * @var array
         * @access public
         * @since 1.0.0
         */
        public $options = array();

        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version = YITH_WCBEP_VERSION;

        /**
         * @var $_panel Panel Object
         */
        protected $_panel;

        /**
         * @var string Premium version landing link
         */
        protected $_premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-bulk-product-editing/';

        /**
         * @var string Quick View panel page
         */
        protected $_panel_page = 'yith_wcbep_panel';

        /**
         * Various links
         *
         * @var string
         * @access public
         * @since 1.0.0
         */
        public $doc_url = 'http://yithemes.com/docs-plugins/yith-woocommerce-bulk-edit-products/';

        public $templates = array();

        /**
         * Returns single instance of the class
         *
         * @return \YITH_WCBEP
         * @since 1.0.0
         */
        public static function get_instance(){
            $self = __CLASS__ . ( class_exists(__CLASS__ . '_Premium') ? '_Premium' : '' );

            if( is_null( $self::$instance ) ){
                $self::$instance = new $self;
            }

            return $self::$instance;
        }

    	/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct(){

            add_action( 'admin_menu', array( $this, 'register_panel' ), 5) ;

            //Add action links
            add_filter('plugin_action_links_' . plugin_basename(YITH_WCBEP_DIR . '/' . basename(YITH_WCBEP_FILE)), array($this, 'action_links'));
            add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 4);

            // Enqueue Scripts
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

            //Bulk edit Tab
            add_action( 'yith_wcbep_bulk_edit_main_tab', array( $this, 'main_tab'));

            add_action( 'wp_ajax_yith_wcbep_bulk_edit_products', array( $this, 'bulk_edit_products'));
            //add_action( 'wp_ajax_yith_wcbep_get_table', array($this, 'get_table_ajax') );

            // AJAX TABLE
            add_action('wp_ajax__ajax_fetch_yith_wcbep_list', array($this, 'ajax_fetch_table_callback') );

            add_action('admin_init', array($this, 'redirect_to_bulk_edit_page'));

            // Premium Tabs
            add_action( 'yith_wcbep_premium_tab', array( $this, 'show_premium_tab' ) );
        }

        public function ajax_fetch_table_callback() {
            $table = new YITH_WCBEP_List_Table();
            $table->ajax_response();
        }

        /**
         * Get main-tab template
         *
         * @access public
         * @since 1.0.0
         */
        public function main_tab(){
            $args = array();
            yith_wcbep_get_template('main-tab.php', $args);
        }

        /**
         * Get table [AJAX]
         *
         * @access public
         * @since 1.0.0
         */
        public function get_table_ajax(){
            $table = new YITH_WCBEP_List_Table();
            $table->prepare_items();
            $table->display();
            die();
        }

        /**
         * Bulk Edit Products [AJAX]
         *
         * @access public
         * @since 1.0.0
         */
        public function bulk_edit_products(){
            if (isset($_POST['matrix_modify']) && isset($_POST['matrix_keys']) ){
                $matrix_modify = $_POST['matrix_modify'];
                $matrix_keys = $_POST['matrix_keys'];

                $id_index           = array_search('ID', $matrix_keys);
                $reg_price_index    = array_search('regular_price', $matrix_keys);
                $sale_price_index   = array_search('sale_price', $matrix_keys);

                $counter = 0;
                foreach ($matrix_modify as $single_modify){
                    $id                 = $single_modify[$id_index];
                    $reg_price          = $single_modify[$reg_price_index];
                    $sale_price         = $single_modify[$sale_price_index];

                    $prod = wc_get_product($id);
                    if($prod){
                        $counter++;

                        // EDIT REGULAR PRICE AND SALE PRICE
                        if ( isset( $reg_price ) ) {
                            update_post_meta( $id, '_regular_price', ( $reg_price === '' ) ? '' : wc_format_decimal( $reg_price ) );
                        }
                        if ( isset( $sale_price ) ) {
                            update_post_meta( $id, '_sale_price', ( $sale_price === '' ? '' : wc_format_decimal( $sale_price ) ) );
                        }

                        $date_from = get_post_meta($id, '_sale_price_dates_from', true );
                        $date_to = get_post_meta($id, '_sale_price_dates_to', true );

                        if ( is_numeric($sale_price) && '' !== $sale_price && empty($date_to) && empty($date_from) ) {
                            update_post_meta( $id, '_price', wc_format_decimal( $sale_price ) );
                        } else {
                            update_post_meta( $id, '_price', ( $reg_price === '' ) ? '' : wc_format_decimal( $reg_price ) );
                        }
                    }
                }
                echo sprintf(_n('%s product edited', '%s products edited', $counter, 'yith-wcbep'), $counter);
            }
            die();
        }

        /**
         * Action Links
         *
         * add the action links to plugin admin page
         *
         * @param $links | links plugin array
         *
         * @return   mixed Array
         * @since    1.0
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         * @return mixed
         * @use plugin_action_links_{$plugin_file_name}
         */
        public function action_links( $links ) {

            $links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'yith-wcbep' ) . '</a>';

            return $links;
        }

        /**
         * plugin_row_meta
         *
         * add the action links to plugin admin page
         *
         * @param $plugin_meta
         * @param $plugin_file
         * @param $plugin_data
         * @param $status
         *
         * @return   Array
         * @since    1.0
         * @use plugin_row_meta
         */
        public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

            if ( defined( 'YITH_WCBEP_FREE_INIT' ) && YITH_WCBEP_FREE_INIT == $plugin_file ) {
                $plugin_meta[] = '<a href="' . $this->doc_url . '" target="_blank">' . __( 'Plugin Documentation', 'yith-wcbep' ) . '</a>';
            }
            return $plugin_meta;
        }

        /**
         * Add a panel under YITH Plugins tab
         *
         * @return   void
         * @since    1.0
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         * @use     /Yit_Plugin_Panel class
         * @see      plugin-fw/lib/yit-plugin-panel.php
         */
        public function register_panel() {

            if ( ! empty( $this->_panel ) ) {
                return;
            }

            $admin_tabs_free = array(
                'bulk-edit'      => __( 'Bulk Product Editing', 'yith-wcbep' ),
                'premium'       => __( 'Premium Version', 'yith-wcbep' )
                );

            $admin_tabs = apply_filters('yith_wcbep_settings_admin_tabs', $admin_tabs_free);

            $args = array(
                'create_menu_page' => true,
                'parent_slug'      => '',
                'page_title'       => __( 'Bulk Product Editing', 'yith-wcbep' ),
                'menu_title'       => __( 'Bulk Product Editing', 'yith-wcbep' ),
                'capability'       => 'manage_options',
                'parent'           => '',
                'parent_page'      => 'yit_plugin_panel',
                'page'             => $this->_panel_page,
                'admin-tabs'       => $admin_tabs,
                'options-path'     => YITH_WCBEP_DIR . '/plugin-options'
            );

            /* === Fixed: not updated theme  === */
            if( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
                require_once( 'plugin-fw/lib/yit-plugin-panel-wc.php' );
            }

            $this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
            
            add_action( 'woocommerce_admin_field_yith_wcbep_upload', array( $this->_panel, 'yit_upload' ), 10, 1 );
            add_action( 'woocommerce_update_option_yith_wcbep_upload', array( $this->_panel, 'yit_upload_update' ), 10, 1 );

            add_submenu_page(
                'edit.php?post_type=product',
                'Bulk Product Editing',
                'Bulk Product Editing',
                'manage_woocommerce',
                'yith-wcbep-bulk-product-editing',
                '__return_empty_string'
            );
        }

        public function redirect_to_bulk_edit_page(){
            global $pagenow;
            /* Check current admin page. */
            if($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'product' && isset($_GET['page']) && $_GET['page'] == 'yith-wcbep-bulk-product-editing') {
                wp_redirect(admin_url("admin.php?page=yith_wcbep_panel"));
                exit;
            }
        }

        public function admin_enqueue_scripts() {
            $premium_suffix = defined( 'YITH_WCBEP_PREMIUM' ) ? '_premium' : '';

            wp_enqueue_style( 'yith-wcbep-admin-styles', YITH_WCBEP_ASSETS_URL . '/css/admin' . $premium_suffix . '.css');
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_style('jquery-ui-style-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css');
            wp_enqueue_style('googleFontsOpenSans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800,300');

            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
            
            $screen     = get_current_screen();

            $main_tab_js = 'main_tab'. $premium_suffix .'.js';
            $menu_slug = sanitize_title( __( 'YIT Plugins', 'yith-plugin-fw' ) );

            if( $menu_slug . '_page_yith_wcbep_panel' == $screen->id  ) {
                wp_enqueue_script( 'chosen' );
                wp_enqueue_style( 'chosen' );

                wp_enqueue_script( 'yith_wcbep_main_tab_js', YITH_WCBEP_ASSETS_URL .'/js/' . $main_tab_js, array('jquery'), '1.0.0', true );
                wp_localize_script( 'yith_wcbep_main_tab_js', 'ajax_object', array(
                        'no_product_selected'       => __('No Product Selected: to make a bulk edit, select one or more products', 'yith-wcbep'),
                        'leave_empty'               => __('- - - Empty - - -', 'yith-wcbep'),
                        'file'                      => __('file','yith-wcbep'),
                        'files'                     => __('files','yith-wcbep'),
                        'not_editable_variations'   => __('This field is not editable because this is a variation!', 'yith-wcbep'),
                        'not_editable_new_product'  => __('This field is not editable for now! Please save before try to edit this field.', 'yith-wcbep')
                    )
                );
            }

        }

        /**
         * Show premium landing tab
         *
         * @return   void
         * @since    1.0
         * @author   Leanza Francescp <leanzafrancesco@gmail.com>
         */
        public function show_premium_tab(){
            $landing = YITH_WCBEP_TEMPLATE_PATH . '/premium.php';
            file_exists( $landing ) && require( $landing );
        }

        /**
         * Get the premium landing uri
         *
         * @since   1.0.0
         * @author   Leanza Francescp <leanzafrancesco@gmail.com>
         * @return  string The premium landing link
         */
        public function get_premium_landing_uri() {
            return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing . '?refer_id=1030585';
        }
    }
}

/**
 * Unique access to instance of YITH_WCBEP_Admin class
 *
 * @return \YITH_WCBEP_Admin
 * @since 1.0.0
 */
function YITH_WCBEP_Admin(){
    return YITH_WCBEP_Admin::get_instance();
}
?>
