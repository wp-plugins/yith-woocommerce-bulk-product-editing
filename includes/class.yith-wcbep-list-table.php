<?php
if ( !defined( 'YITH_WCBEP' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if( !class_exists( 'YITH_WCBEP_List_Table' ) ) {
    /**
     * List table class
     *
     * @since 1.0.0
     * @author   Leanza Francesco <leanzafrancesco@gmail.com>
     */
    class YITH_WCBEP_List_Table extends WP_List_Table{

        public $columns;
        public $hidden;
        public $sortable;

        /**
         * Constructor
         *
         * @access public
         * @since 1.0.0
         */
        public function __construct( $columns = array(), $hidden = array(), $sortable = array() ){
            global $status, $page;

            $this->columns = $columns;
            $this->hidden = $hidden;
            $this->sortable = $sortable;

            //Set parent defaults
            parent::__construct(
                array(
                    'singular'  => 'yith_wcbep_product',
                    'plural'    => 'yith_wcbep_products',
                    'ajax'      => true,
                    'screen' => 'yith-wcbep-product-list'
                )
            );
        }

        public function get_columns(){
            $default = array(
                'cb'                    => '<input type="checkbox">',
                'ID'                    => __('ID', 'yith-wcbep'),
                'title'                 => __('Title', 'yith-wcbep'),
                'regular_price'         => __('Regular Price', 'yith-wcbep'),
                'sale_price'            => __('Sale Price', 'yith-wcbep'),
                'categories'            => __('Categories', 'yith-wcbep'),
                'date'                  => __('Date', 'yith-wcbep'),
            );
            return !empty( $this->columns) ? $this->columns: $default;
        }

        public function get_sortable(){
            $default = array(
                'title'             => array('title', false),
                'regular_price'     => array('regular_price', false),
                'sale_price'        => array('sale_price', false),
                'date'              => array('date', false),
            );
            return !empty( $this->sortable) ? $this->sortable: $default;
        }

        public function get_hidden(){
            $default = array('ID');
            return !empty( $this->hidden) ? $this->hidden: $default;
        }

        public function prepare_items( $items = array()) {
            $per_page = ! empty( $_REQUEST['f_per_page'] ) && intval($_REQUEST['f_per_page']) > 0 ? intval($_REQUEST['f_per_page']) : 10;

            $columns = $this->get_columns();
            $hidden = $this->get_hidden();
            $sortable = $this->get_sortable();

            $this->_column_headers = array($columns, $hidden, $sortable);

            $my_items = !empty($items) ? $items : $this->get_products();
            usort( $my_items, array( $this, 'usort_reorder' ) );

            $current_page = $this->get_pagenum();
            $total_items = count($my_items);

            $my_items = array_slice($my_items,(($current_page-1)*$per_page),$per_page);

            $this->items = $my_items;

            $this->set_pagination_args(
                array(
                    //WE have to calculate the total number of items
                    'total_items'   => $total_items,
                    //WE have to determine how many items to show on a page
                    'per_page'  => $per_page,
                    //WE have to calculate the total number of pages
                    'total_pages'   => ceil( $total_items / $per_page ),
                    // Set ordering values if needed (useful for AJAX)
                    'orderby'   => ! empty( $_REQUEST['orderby'] ) && '' != $_REQUEST['orderby'] ? $_REQUEST['orderby'] : 'date',
                    'order'     => ! empty( $_REQUEST['order'] ) && '' != $_REQUEST['order'] ? $_REQUEST['order'] : 'asc'
                )
            );
        }

        /**
         * @return array
         */
        public function get_products(){
            /*
            $filtered_categories = !empty($_POST['f_categories']) ? $_POST['f_categories'] : array();

            if (isset($_GET['product_cat'])){
                $filtered_categories[] = $_GET['product_cat'];
            }


            $f_regular_price_sel = !empty($_POST['f_reg_price_select']) ? $_POST['f_reg_price_select'] : 'mag';
            $f_regular_price_val = isset($_POST['f_reg_price_value']) ? $_POST['f_reg_price_value'] : NULL;

            $f_sale_price_sel = !empty($_POST['f_sale_price_select']) ? $_POST['f_sale_price_select'] : 'mag';
            $f_sale_price_val = isset($_POST['f_sale_price_value']) ? $_POST['f_sale_price_value'] : NULL;
            */

            $filtered_categories = !empty($_REQUEST['f_categories']) ? $_REQUEST['f_categories'] : array();

            $f_regular_price_sel = !empty($_REQUEST['f_reg_price_select']) ? $_REQUEST['f_reg_price_select'] : 'mag';
            $f_regular_price_val = isset($_REQUEST['f_reg_price_value']) ? $_REQUEST['f_reg_price_value'] : NULL;

            $f_sale_price_sel = !empty($_REQUEST['f_sale_price_select']) ? $_REQUEST['f_sale_price_select'] : 'mag';
            $f_sale_price_val = isset($_REQUEST['f_sale_price_value']) ? $_REQUEST['f_sale_price_value'] : NULL;

            $query_args = array(
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'post_type'      => 'product',
                'no_found_rows'  => 1
            );

            $products = get_posts($query_args);
            $items = array();
            foreach( $products as $prod ){
                $p = wc_get_product($prod->ID);
                if ($p->product_type == 'variable')
                    continue;
                $item = array();
                $item['ID']                 = $prod->ID;
                $item['title']              = $prod->post_title;
                $item['regular_price']      = $p->regular_price;
                $item['sale_price']         = $p->sale_price;

                $cats = get_the_terms($prod->ID, 'product_cat');
                $cats = !empty($cats) ? $cats : array();
                $cats_html = '';
                $loop = 0;
                $my_cats_id = array();
                foreach ($cats as $c){
                    $loop++;
                    //$cats_html .= '<a href="?page=yith_wcbep_panel&product_cat=' . $c->term_id .'">'. $c->name . '</a>';
                    $cats_html .= $c->name;
                    if ($loop < count($cats)){
                        $cats_html .= ', ';
                    }
                    $my_cats_id[] = $c->term_id;
                }

                // Filter Regular Price
                if (isset($f_regular_price_val) && is_numeric($f_regular_price_val)){
                    $return = false;
                    switch($f_regular_price_sel){
                        case 'mag':
                            $return = $p->regular_price > $f_regular_price_val;
                            break;
                        case 'min':
                            $return = $p->regular_price < $f_regular_price_val;
                            break;
                        case 'ug':
                            $return = $p->regular_price == $f_regular_price_val;
                            break;
                        case 'magug':
                            $return = $p->regular_price >= $f_regular_price_val;
                            break;
                        case 'minug':
                            $return = $p->regular_price <= $f_regular_price_val;
                            break;
                    }
                    if ( $return == false )
                        continue;
                }

                // Filter Sale Price
                if (isset($f_sale_price_val) && is_numeric($f_sale_price_val)){
                    $return = false;
                    switch($f_sale_price_sel){
                        case 'mag':
                            $return = $p->sale_price > $f_sale_price_val;
                            break;
                        case 'min':
                            $return = $p->sale_price < $f_sale_price_val;
                            break;
                        case 'ug':
                            $return = $p->sale_price == $f_sale_price_val;
                            break;
                        case 'magug':
                            $return = $p->sale_price >= $f_sale_price_val;
                            break;
                        case 'minug':
                            $return = $p->sale_price <= $f_sale_price_val;
                            break;
                    }
                    if ( $return == false )
                        continue;
                }

                //Filter categories [OR]
                if (!empty($filtered_categories)) {
                    $finded = false;
                    foreach ($filtered_categories as $fc) {
                        if (in_array($fc, $my_cats_id)) {
                            $finded = true;
                            continue;
                        }
                    }
                    if (!$finded) {
                        continue;
                    }
                }

                $item['categories']         =  $cats_html;
                $item['date']               = date_i18n( 'Y/m/d', strtotime( $prod->post_date ));

                $items[] = $item;
            }
            return $items;
        }

        function column_default( $item, $column_name ) {
            return $item[ $column_name ];
        }

        function column_cb( $item ) {
            return sprintf(
                '<input type="checkbox" value="%s" />', $item['ID']
            );
        }

        function usort_reorder( $a, $b )
        {
            // If no sort, default to title
            $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'date';
            // If no order, default to asc
            $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';

            // Determine sort order
            switch ($orderby) {
                case 'regular_price':
                case 'sale_price':
                    $result = $a[$orderby] >= $b[$orderby];
                    // Send final sort direction to usort
                    return ($order === 'asc') ? $result : !$result;
                    break;
                default:
                    $result = strcmp($a[$orderby], $b[$orderby]);
                    // Send final sort direction to usort
                    return ($order === 'asc') ? $result : -$result;
            }
        }

        public function print_column_headers( $with_id = true ) {
            list( $columns, $hidden, $sortable ) = $this->get_column_info();

            $current_url = set_url_scheme( admin_url() . '?page=yith_wcbep_panel' );
            $current_url = remove_query_arg( 'paged', $current_url );

            if ( isset( $_GET['orderby'] ) )
                $current_orderby = $_GET['orderby'];
            else
                $current_orderby = '';

            if ( isset( $_GET['order'] ) && 'desc' == $_GET['order'] )
                $current_order = 'desc';
            else
                $current_order = 'asc';

            if ( ! empty( $columns['cb'] ) ) {
                static $cb_counter = 1;
                $columns['cb'] = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __( 'Select All' ) . '</label>'
                    . '<input id="cb-select-all-' . $cb_counter . '" type="checkbox" />';
                $cb_counter++;
            }

            foreach ( $columns as $column_key => $column_display_name ) {
                $class = array( 'manage-column', "column-$column_key" );

                $style = '';
                if ( in_array( $column_key, $hidden ) )
                    $style = 'display:none;';

                $style = ' style="' . $style . '"';

                if ( 'cb' == $column_key )
                    $class[] = 'check-column';
                elseif ( in_array( $column_key, array( 'posts', 'comments', 'links' ) ) )
                    $class[] = 'num';

                if ( isset( $sortable[$column_key] ) ) {
                    list( $orderby, $desc_first ) = $sortable[$column_key];

                    if ( $current_orderby == $orderby ) {
                        $order = 'asc' == $current_order ? 'desc' : 'asc';
                        $class[] = 'sorted';
                        $class[] = $current_order;
                    } else {
                        $order = $desc_first ? 'desc' : 'asc';
                        $class[] = 'sortable';
                        $class[] = $desc_first ? 'asc' : 'desc';
                    }

                    $column_display_name = '<a href="' . esc_url( add_query_arg( compact( 'orderby', 'order' ), $current_url ) ) . '"><span>' . $column_display_name . '</span><span class="sorting-indicator"></span></a>';
                }

                $id = $with_id ? "id='$column_key'" : '';

                if ( !empty( $class ) )
                    $class = "class='" . join( ' ', $class ) . "'";

                echo "<th scope='col' $id $class $style>$column_display_name</th>";
            }
        }

        public function display() {

            wp_nonce_field( 'ajax-yith-wcbep-list-nonce', '_ajax_yith_wcbep_list_nonce' );

            echo '<input id="order" type="hidden" name="order" value="' . $this->_pagination_args['order'] . '" />';
            echo '<input id="orderby" type="hidden" name="orderby" value="' . $this->_pagination_args['orderby'] . '" />';

            parent::display();
        }

        function ajax_response() {

            check_ajax_referer( 'ajax-yith-wcbep-list-nonce', '_ajax_yith_wcbep_list_nonce' );

            $this->prepare_items();

            extract( $this->_args );
            extract( $this->_pagination_args, EXTR_SKIP );

            ob_start();
            if ( ! empty( $_REQUEST['no_placeholder'] ) )
                $this->display_rows();
            else
                $this->display_rows_or_placeholder();
            $rows = ob_get_clean();

            ob_start();
            $this->print_column_headers();
            $headers = ob_get_clean();

            ob_start();
            $this->pagination('top');
            $pagination_top = ob_get_clean();

            ob_start();
            $this->pagination('bottom');
            $pagination_bottom = ob_get_clean();

            $response = array( 'rows' => $rows );
            $response['pagination']['top'] = $pagination_top;
            $response['pagination']['bottom'] = $pagination_bottom;
            $response['column_headers'] = $headers;

            if ( isset( $total_items ) )
                $response['total_items_i18n'] = sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) );

            if ( isset( $total_pages ) ) {
                $response['total_pages'] = $total_pages;
                $response['total_pages_i18n'] = number_format_i18n( $total_pages );
            }

            die( json_encode( $response ) );
        }
    }
}
?>