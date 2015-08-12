jQuery(function($){
	var categories_filter_select            = $('#yith-wcbep-categories-filter'),
        table                               = $('#yith-wcbep-table-wrap .wp-list-table'),
        custom_input                        = $('#yith-wcbep-custom-input'),
        selected                            = null,
        current_cell                        = null,
        matrix                              = new Array(),
        current_matrix                      = new Array(),
        current_matrix_keys                 = new Array(),
        cell_matrix                         = new Array(),
        get_products_btn                    = $('#yith-wcbep-get-products'),
        filter_form                         = $('#yith-wcbep-filter-form'),
        f_categories                        = $('#yith-wcbep-categories-filter'),
        f_reg_price_select                  = $('#yith-wcbep-regular-price-filter-select'),
        f_reg_price_value                   = $('#yith-wcbep-regular-price-filter-value'),
        f_sale_price_select                 = $('#yith-wcbep-sale-price-filter-select'),
        f_sale_price_value                  = $('#yith-wcbep-sale-price-filter-value'),
        f_per_page                          = $('#yith-wcbep-per-page-filter'),
        f_reset_btn                         = $('#yith-wcbep-reset-filters'),
        table_wrap                          = $('#yith-wcbep-table-wrap'),
        bulk_edit_btn                       = $('#yith-wcbep-bulk-edit-btn'),
        bulk_editor                         = $('#yith-wcbep-bulk-editor'),
        bulk_cancel_btn                     = $('#yith-wcbep-bulk-cancel'),
        bulk_apply_btn                      = $('#yith-wcbep-bulk-apply'),
        b_reg_price_sel                     = $('#yith-wcbep-regular-price-bulk-select'),
        b_reg_price_val                     = $('#yith-wcbep-regular-price-bulk-value'),
        b_sale_price_sel                    = $('#yith-wcbep-sale-price-bulk-select'),
        b_sale_price_val                    = $('#yith-wcbep-sale-price-bulk-value'),
        save_btn                            = $('#yith-wcbep-save'),
        my_checked_rows                     = new Array(),
        modified_rows                       = new Array(),
        message                             = $('#yith-wcbep-message'),
        block_params 						= {
                                                message: 	null,
                                                overlayCSS: {
                                                    background: '#000',
                                                    opacity: 	0.6
                                                },
                                                ignoreIfBlocked: true
                                            },
        block_params2 						= {
                                                message: 	null,
                                                overlayCSS: {
                                                    background: '#000 url()',
                                                    opacity: 	0.6,
                                                    cursor: 'default'
                                                }
                                            },

        custom_input_hide                   = function(hide){
                                                hide = hide || false;
                                                if (hide) {
                                                    custom_input.hide();
                                                }
                                                if (selected){
                                                    selected.html(custom_input.html());
                                                    custom_input.html('');
                                                    selected = null;
                                                }
                                                controller_test();
                                            },
        controller_test                     = function(){
                                                var row = 0;
                                                modified_rows = new Array();
                                                table.find('tbody tr').each(function(){
                                                    var item    = $(this).find('td'),
                                                        modified = false;

                                                    if (item.length > 0) {
                                                        var col = 1;
                                                        item.each(function () {
                                                            var val = $(this).html();
                                                            if (val !=  matrix[row][col]){
                                                                $(this).addClass('yith-wcbep-table-modified-td');
                                                                modified = true;
                                                            }else{
                                                                $(this).removeClass('yith-wcbep-table-modified-td');
                                                            }
                                                            col++;
                                                        });
                                                        if (modified){
                                                            modified_rows.push(row);
                                                        }
                                                        row++;
                                                    }
                                                });
                                            },
        table_init                          = function(){
                                                table = $('#yith-wcbep-table-wrap .wp-list-table');
                                                //carico i dati iniziali in una matrice
                                                matrix_init();

                                                table
                                                    .on('click','td.regular_price, td.sale_price', function(event){
                                                        event.stopPropagation();
                                                        custom_input_hide(false);

                                                        selected = $(event.target);
                                                        current_cell = $(event.target);

                                                        custom_input.width(selected.width());
                                                        custom_input.height(selected.height());

                                                        custom_input.show();
                                                        custom_input.offset(selected.offset());
                                                        custom_input.html(selected.html());
                                                    });
                                                },
        matrix_init                         = function(){
                                                    //carico i dati iniziali in una matrice
                                                    matrix = new Array();
                                                    cell_matrix = new Array();
                                                    table.find('tbody tr').each(function(){
                                                        var item = $(this).find('td');
                                                        var cell_cols = [$(this).find('th')];
                                                        if (item.length > 0) {
                                                            var cols = [false];
                                                            item.each(function () {
                                                                cols.push($(this).html());
                                                                cell_cols.push($(this));
                                                            });
                                                            matrix.push(cols);
                                                            cell_matrix.push(cell_cols);
                                                        }
                                                    });
                                                },
        create_current_matrix               = function(){
                                                var new_matrix = new Array();
                                                table.find('tbody tr').each(function(){
                                                    var item = $(this).find('td');
                                                    if (item.length > 0) {
                                                        var cols = [false];
                                                        item.each(function () {
                                                            cols.push($(this).html());
                                                        });
                                                        new_matrix.push(cols);
                                                    }
                                                });
                                                return new_matrix;
        },
        create_current_matrix_keys          = function(){
                                                var new_matrix = new Array();
                                                table.find('thead tr th').each(function(){
                                                    new_matrix.push( $(this).attr('id') );
                                                });
                                                return new_matrix;
        },
        checked_rows                        = function(){
                                                var row = 0;
                                                var result = new Array();
                                                table.find('tbody tr').each(function(){
                                                    var item = $(this).find('th input:checked');
                                                    if (item.length > 0) {
                                                        result.push(row);
                                                    }
                                                    row++;
                                                });
                                                return result;
        },
        reset_bulk_editor                   = function(){
                                                bulk_editor.find('input.is_resetable').each(function(){
                                                    $(this).val('');
                                                });
                                                bulk_editor.find('select.is_resetable').each(function(){
                                                    $(this).prop('selectedIndex', 0 );
                                                });
        },
        reset_filters                       = function(){
                                                filter_form.find('input.is_resetable').each(function(){
                                                    $(this).val('');
                                                });
                                                filter_form.find('select.is_resetable').each(function(){
                                                    $(this).prop('selectedIndex', 0 );
                                                });
                                                categories_filter_select.chosen('destroy');
                                                categories_filter_select.prop('selectedIndex', -1 );
                                                categories_filter_select.chosen({width: '95%'});
                                                //get_products_btn.trigger('click');
        },
        go_to_next_cell                     = function(){
                                                if(current_cell){
                                                    for (index in cell_matrix){
                                                        var row = cell_matrix[index];
                                                        for (index_col in row){
                                                            if($(row[index_col])[0] == current_cell[0]){
                                                                if ( typeof cell_matrix[parseInt(index)+1] != 'undefined' ) {
                                                                    $(cell_matrix[parseInt(index) + 1][index_col]).trigger('click');
                                                                    custom_input.selectText();
                                                                    return;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
        };

    $.fn.selectText = function(){
        var doc = document;
        var element = this[0];
        //console.log(this, element);
        if (doc.body.createTextRange) {
            var range = document.body.createTextRange();
            range.moveToElementText(element);
            range.select();
        } else if (window.getSelection) {
            var selection = window.getSelection();
            var range = document.createRange();
            range.selectNodeContents(element);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    };


    // I N I T
    categories_filter_select.chosen({width: '95%'});
    custom_input.offset(new Array(0,0));
    table_init();

    bulk_editor.draggable();

    $('html').on('click', function(){
        custom_input_hide(true);
    });

    custom_input
        .on('click', function(event) {
            event.stopPropagation();
        })

        .keypress(function(e) {
            if(e.which == 13) {
                custom_input_hide(true);
                e.stopPropagation();
                setTimeout(go_to_next_cell, 0);
            }
        });

    get_products_btn.on('click', function(){
        var data = {
            f_categories: f_categories.val(),
            f_reg_price_select: f_reg_price_select.val(),
            f_reg_price_value: f_reg_price_value.val(),
            f_sale_price_select: f_sale_price_select.val(),
            f_sale_price_value: f_sale_price_value.val(),
            f_per_page: f_per_page.val()
        };
        list.update( data );
    });

    bulk_edit_btn.on('click', function(){
        // get selected ID
        var checked_array = table.find('tbody th input:checked');
        var checked_ids = new Array();
        checked_array.each(function(){
            checked_ids.push($(this).val());
        });

        if (checked_ids.length < 1){
            alert(ajax_object.no_product_selected);
            return;
        }

        // open bulk editor
        bulk_editor.fadeIn();
        $('#wpwrap').block(block_params2);
        my_checked_rows = checked_rows();
        //console.log(checked_rows());
    });

    bulk_cancel_btn.on('click',function(){
        bulk_editor.fadeOut();
        $('#wpwrap').unblock();
    });

    bulk_editor.keypress(function(e) {
        if(e.which == 13) {
            bulk_apply_btn.trigger('click');
        }
    });

    bulk_apply_btn.on('click', function(){
        var reg_price_s     = b_reg_price_sel.val(),
            reg_price_v     = b_reg_price_val.val(),
            sale_price_s    = b_sale_price_sel.val(),
            sale_price_v    = b_sale_price_val.val();

        for (index in my_checked_rows) {
            var ckd = my_checked_rows[index];
            var reg_price_cell          = $(cell_matrix[ckd][3]),
                reg_price_old_value     = parseFloat(matrix[ckd][3]),
                reg_price_new           = 0,
                sale_price_cell          = $(cell_matrix[ckd][4]),
                sale_price_old_value     = parseFloat(matrix[ckd][4]),
                sale_price_new           = 0;

            // REGULAR PRICE
            if ( !isNaN(reg_price_v) && reg_price_v!= ''){
                switch (reg_price_s){
                    case 'new':
                        reg_price_new = parseFloat(reg_price_v);
                        break;
                    case 'inc':
                        reg_price_old_value = !isNaN(reg_price_old_value) ? reg_price_old_value : 0;
                        reg_price_new = reg_price_old_value + parseFloat(reg_price_v);
                        break;
                    case 'dec':
                        reg_price_new = reg_price_old_value - parseFloat(reg_price_v);
                        break;
                    case 'incp':
                        reg_price_new = reg_price_old_value + reg_price_old_value*parseFloat(reg_price_v)/100;
                        break;
                    case 'decp':
                        reg_price_new = reg_price_old_value - reg_price_old_value*parseFloat(reg_price_v)/100;
                        break;
                }
                if(reg_price_new < 0 || isNaN(reg_price_new)){
                    reg_price_new = 0;
                }
                reg_price_cell.html(reg_price_new);
            }

            // SALE PRICE
            if ( (!isNaN(sale_price_v)&& sale_price_v!= '') || sale_price_s == 'del'){
                switch (sale_price_s){
                    case 'new':
                        sale_price_new = parseFloat(sale_price_v);
                        break;
                    case 'inc':
                        sale_price_old_value = !isNaN(sale_price_old_value) ? sale_price_old_value : 0;
                        sale_price_new = sale_price_old_value + parseFloat(sale_price_v);
                        break;
                    case 'dec':
                        sale_price_new = sale_price_old_value - parseFloat(sale_price_v);
                        break;
                    case 'incp':
                        sale_price_new = sale_price_old_value + sale_price_old_value*parseFloat(sale_price_v)/100;
                        break;
                    case 'decp':
                        sale_price_new = sale_price_old_value - sale_price_old_value*parseFloat(sale_price_v)/100;
                        break;
                    case 'del':
                        sale_price_new = '';
                        break;
                }
                if(sale_price_new < 0 || isNaN(sale_price_new)){
                    sale_price_new = 0;
                }
                sale_price_cell.html(sale_price_new);
            }
        }

        bulk_editor.fadeOut();
        reset_bulk_editor();
        $('#wpwrap').unblock();
    });

    f_reset_btn.on('click', function(){
        reset_filters();
    });

    save_btn.on('click', function(){
        if(modified_rows.length > 0){
            //BLOCK
            table.block(block_params);
            save_btn.prop('disabled', true);
            bulk_edit_btn.prop('disabled', true);

            current_matrix = create_current_matrix();
            current_matrix_keys = create_current_matrix_keys();

            var matrix_modify = new Array();
            for (mod_row in modified_rows){
                var index = modified_rows[mod_row];
                matrix_modify.push(current_matrix[index]);
            }

            var post_data = {
                matrix_keys:    current_matrix_keys,
                matrix_modify:          matrix_modify,
                action:                 'yith_wcbep_bulk_edit_products'
            };

            $.ajax({
                type       : "POST",
                data       : post_data,
                url        : ajaxurl,
                success:function( response ){
                    message.html('<p>' + response + '</p>');
                    var dismiss_btn = $('<button type="button" class="notice-dismiss" />');
                    dismiss_btn.appendTo(message);
                    message.fadeIn();
                    dismiss_btn.on('click', function(){
                       message.fadeOut();
                    });
                    get_products_btn.trigger('click');
                }
            });

        }
    });

    // AJAX WP_TABLE_LIST
    list = {

        init: function() {

            // This will have its utility when dealing with the page number input
            var timer;
            var delay = 500;

            // Pagination links, sortable link
            $('.tablenav-pages a, .manage-column.sortable a, .manage-column.sorted a').on('click', function(e) {
                // We don't want to actually follow these links
                e.preventDefault();
                // Simple way: use the URL to extract our needed variables
                var query = this.search.substring( 1 );

                var data = {
                    paged: list.__query( query, 'paged' ) || '1',
                    order: list.__query( query, 'order' ) || 'asc',
                    orderby: list.__query( query, 'orderby' ) || 'date',
                    f_categories: f_categories.val(),
                    f_reg_price_select: f_reg_price_select.val(),
                    f_reg_price_value: f_reg_price_value.val(),
                    f_sale_price_select: f_sale_price_select.val(),
                    f_sale_price_value: f_sale_price_value.val(),
                    f_per_page: f_per_page.val()
                };
                list.update( data );
            });

            // Page number input
            $('input[name=paged]').on('keyup', function(e) {

                // If user hit enter, we don't want to submit the form
                // We don't preventDefault() for all keys because it would
                // also prevent to get the page number!
                if ( 13 == e.which )
                    e.preventDefault();

                // This time we fetch the variables in inputs
                var data = {
                    paged: parseInt( $('input[name=paged]').val() ) || '1',
                    order: $('input[name=order]').val() || 'asc',
                    orderby: $('input[name=orderby]').val() || 'date'
                };

                // Now the timer comes to use: we wait half a second after
                // the user stopped typing to actually send the call. If
                // we don't, the keyup event will trigger instantly and
                // thus may cause duplicate calls before sending the intended
                // value
                window.clearTimeout( timer );
                timer = window.setTimeout(function() {
                    list.update( data );
                }, delay);
            });
        },

        /** AJAX call
         *
         * Send the call and replace table parts with updated version!
         *
         * @param    object    data The data to pass through AJAX
         */
        update: function( data ) {
            table.block(block_params);
            save_btn.prop('disabled', true);
            bulk_edit_btn.prop('disabled', true);

            $.ajax({
                // /wp-admin/admin-ajax.php
                url: ajaxurl,
                // Add action and nonce to our collected data
                data: $.extend(
                    {
                        _ajax_yith_wcbep_list_nonce: $('#_ajax_yith_wcbep_list_nonce').val(),
                        action: '_ajax_fetch_yith_wcbep_list',
                    },
                    data
                ),
                // Handle the successful result
                success: function( response ) {

                    // WP_List_Table::ajax_response() returns json
                    var response = $.parseJSON( response );

                    //console.log(response);

                    // Add the requested rows
                    if ( response.rows.length )
                        $('#the-list').html( response.rows );
                    // Update column headers for sorting
                    if ( response.column_headers.length )
                        $('thead tr, tfoot tr').html( response.column_headers );
                    // Update pagination for navigation
                    if ( response.pagination.bottom.length )
                        $('.tablenav.top .tablenav-pages').html( $(response.pagination.top).html() );
                    if ( response.pagination.top.length )
                        $('.tablenav.bottom .tablenav-pages').html( $(response.pagination.bottom).html() );

                    // Init back our event handlers
                    list.init();
                    // UNBLOCK
                    table.unblock();
                    save_btn.prop('disabled', false);
                    bulk_edit_btn.prop('disabled', false);
                    matrix_init();
                }
            });
        },

        /**
         * Filter the URL Query to extract variables
         *
         * @see http://css-tricks.com/snippets/javascript/get-url-variables/
         *
         * @param    string    query The URL query part containing the variables
         * @param    string    variable Name of the variable we want to get
         *
         * @return   string|boolean The variable value if available, false else.
         */
        __query: function( query, variable ) {

            var vars = query.split("&");
            for ( var i = 0; i <vars.length; i++ ) {
                var pair = vars[ i ].split("=");
                if ( pair[0] == variable )
                    return pair[1];
            }
            return false;
        },
    }

// Show time!
    list.init();

});