<?php
if ( !defined( 'YITH_WCBEP' ) ) { exit; } // Exit if accessed directly

?>
<div id="yith-wcbep-custom-input" contenteditable="true"></div>
<div class="yith-wcbep-filter-wrap"><h2><?php  _e('Filters', 'yith-wcbep'); ?></h2>
    <form id="yith-wcbep-filter-form" method="post">
    <table style="width:50%">
        <?php

        $cat_args = array(
            'hide_empty'    => true,
            'order'       => 'ASC'
        );
        $categories = get_terms('product_cat', $cat_args);


        if (!empty( $categories )){
            ?>
            <tr>
                <td class="yith-wcbep-filter-form-label-col">
                    <label><?php _e('Filter Categories', 'yith-wcbep') ?></label>
                </td>
                <td class="yith-wcbep-filter-form-content-col">
                    <select id="yith-wcbep-categories-filter" name="yith-wcbep-categories-filter[]" class="chosen is_resetable" multiple xmlns="http://www.w3.org/1999/html">
                    <?php
                    foreach($categories as $c){
                        ?>
                        <option value="<?php echo $c->term_id; ?>"><?php echo $c->name; ?></option>
                    <?php
                    }
                    ?>
                    </select>
                </td>
            </tr>
        <?php
        }?>
        <tr>
            <td class="yith-wcbep-filter-form-label-col">
                <label><?php _e('Regular Price', 'yith-wcbep') ?></label>
            </td>
            <td class="yith-wcbep-filter-form-content-col">
                <select id="yith-wcbep-regular-price-filter-select" name="yith-wcbep-regular-price-filter-select" class="yith-wcbep-miniselect is_resetable">
                    <option value="mag"> > </option>
                    <option value="min"> < </option>
                    <option value="ug"> == </option>
                    <option value="magug"> >= </option>
                    <option value="minug"> <= </option>
                </select>
                <input type="text" id="yith-wcbep-regular-price-filter-value" name="yith-wcbep-regular-price-filter-value" class="yith-wcbep-minifield is_resetable">
            </td>
        </tr>
        <tr>
            <td class="yith-wcbep-filter-form-label-col">
                <label><?php _e('Sale Price', 'yith-wcbep') ?></label>
            </td>
            <td class="yith-wcbep-filter-form-content-col">
                <select id="yith-wcbep-sale-price-filter-select" name="yith-wcbep-sale-price-filter-select" class="yith-wcbep-miniselect is_resetable">
                    <option value="mag"> > </option>
                    <option value="min"> < </option>
                    <option value="ug"> == </option>
                    <option value="magug"> >= </option>
                    <option value="minug"> <= </option>
                </select>
                <input type="text" id="yith-wcbep-sale-price-filter-value" name="yith-wcbep-sale-price-filter-value" class="yith-wcbep-minifield is_resetable">
            </td>
        </tr>
        <tr>
            <td class="yith-wcbep-filter-form-label-col">
                <label><?php _e('Products per page', 'yith-wcbep') ?></label>
            </td>
            <td class="yith-wcbep-filter-form-content-col">
                <input type="text" id="yith-wcbep-per-page-filter" name="yith-wcbep-per-page-filter" class="" value="10">
            </td>
        </tr>
    </table>
        <input id="yith-wcbep-get-products" type="button" class="button button-primary button-large" value="<?php _e('Get products', 'yith-wcbep')?>">
        <input id="yith-wcbep-reset-filters" type="button" class="button button-secondary button-large" value="<?php _e('Reset filters', 'yith-wcbep')?>">
    </form>
</div>

<h2><?php _e('Products', 'yith-wcbep') ?></h2>
<input id="yith-wcbep-save" type="button" class="button button-primary button-large" value="<?php _e('Save', 'yith-wcbep')?>">
<input id="yith-wcbep-bulk-edit-btn" type="button" class="button button-secondary button-large" value="<?php _e('Bulk editing', 'yith-wcbep')?>">
<div id="yith-wcbep-message" class="updated notice">
    <p></p>
</div>
<div id="yith-wcbep-table-wrap">
    <?php
        $table = new YITH_WCBEP_List_Table();
        $table->prepare_items();
        $table->display();
    ?>
</div>

<div id="yith-wcbep-bulk-editor">
    <div id="yith-wcbep-bulk-editor-container">
        <h2><?php _e('Bulk editing', 'yith-wcbep') ?></h2>
        <table id="yith-wcbep-bulk-editor-table">
            <tr>
                <td class="yith-wcbep-bulk-form-label-col">
                    <label><?php _e('Regular Price', 'yith-wcbep') ?></label>
                </td>
                <td class="yith-wcbep-bulk-form-content-col">
                    <select id="yith-wcbep-regular-price-bulk-select" name="yith-wcbep-regular-price-bulk-select" class="yith-wcbep-miniselect is_resetable">
                        <option value="new"><?php _e('Set new', 'yith-wcbep') ?></option>
                        <option value="inc"><?php _e('Increase by value', 'yith-wcbep') ?></option>
                        <option value="dec"><?php _e('Decrease by value', 'yith-wcbep') ?></option>
                        <option value="incp"><?php _e('Increase by percentage', 'yith-wcbep') ?></option>
                        <option value="decp"><?php _e('Decrease by percentage', 'yith-wcbep') ?></option>
                    </select>
                    <input type="text" id="yith-wcbep-regular-price-bulk-value" name="yith-wcbep-regular-price-bulk-value" class="yith-wcbep-minifield is_resetable">
                </td>
            </tr>
            <tr>
                <td class="yith-wcbep-bulk-form-label-col">
                    <label><?php _e('Sale Price', 'yith-wcbep') ?></label>
                </td>
                <td class="yith-wcbep-bulk-form-content-col">
                    <select id="yith-wcbep-sale-price-bulk-select" name="yith-wcbep-sale-price-bulk-select" class="yith-wcbep-miniselect is_resetable">
                        <option value="new"><?php _e('Set new', 'yith-wcbep') ?></option>
                        <option value="inc"><?php _e('Increase by value', 'yith-wcbep') ?></option>
                        <option value="dec"><?php _e('Decrease by value', 'yith-wcbep') ?></option>
                        <option value="incp"><?php _e('Increase by percentage', 'yith-wcbep') ?></option>
                        <option value="decp"><?php _e('Decrease by percentage', 'yith-wcbep') ?></option>
                        <option value="del"><?php _e('Delete', 'yith-wcbep') ?></option>
                    </select>
                    <input type="text" id="yith-wcbep-sale-price-bulk-value" name="yith-wcbep-sale-price-bulk-value" class="yith-wcbep-minifield is_resetable">
                </td>
            </tr>
        </table>
    </div>
    <div id="yith-wcbep-bulk-button-wrap">
        <input id="yith-wcbep-bulk-apply" type="button" class="button button-primary button-large" value="<?php _e('Apply', 'yith-wcbep')?>">
        <input id="yith-wcbep-bulk-cancel" type="button" class="button button-secondary button-large" value="<?php _e('Cancel', 'yith-wcbep')?>">
    </div>
</div>