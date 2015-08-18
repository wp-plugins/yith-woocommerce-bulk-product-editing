<?php
/**
 * Premium Tab
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Bulk Product Editing
 * @version 1.0.0
 */

/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'YITH_WCBEP' ) ) {
	exit;
} // Exit if accessed directly
?>

<style>
.section{
    margin-left: -20px;
    margin-right: -20px;
    font-family: "Raleway",san-serif;
}
.section h1{
    text-align: center;
    text-transform: uppercase;
    color: #808a97;
    font-size: 35px;
    font-weight: 700;
    line-height: normal;
    display: inline-block;
    width: 100%;
    margin: 50px 0 0;
}
.section ul{
    list-style-type: disc;
    padding-left: 15px;
}
.section:nth-child(even){
    background-color: #fff;
}
.section:nth-child(odd){
    background-color: #f1f1f1;
}
.section .section-title img{
    display: table-cell;
    vertical-align: middle;
    width: auto;
    margin-right: 15px;
}
.section h2,
.section h3 {
    display: inline-block;
    vertical-align: middle;
    padding: 0;
    font-size: 24px;
    font-weight: 700;
    color: #808a97;
    text-transform: uppercase;
}

.section .section-title h2{
    display: table-cell;
    vertical-align: middle;
    line-height: 25px;
}

.section-title{
    display: table;
}

.section h3 {
    font-size: 14px;
    line-height: 28px;
    margin-bottom: 0;
    display: block;
}

.section p{
    font-size: 13px;
    margin: 25px 0;
}
.section ul li{
    margin-bottom: 4px;
}
.landing-container{
    max-width: 750px;
    margin-left: auto;
    margin-right: auto;
    padding: 50px 0 30px;
}
.landing-container:after{
    display: block;
    clear: both;
    content: '';
}
.landing-container .col-1,
.landing-container .col-2{
    float: left;
    box-sizing: border-box;
    padding: 0 15px;
}
.landing-container .col-1 img{
    width: 100%;
}
.landing-container .col-1{
    width: 55%;
}
.landing-container .col-2{
    width: 45%;
}
.premium-cta{
    background-color: #808a97;
    color: #fff;
    border-radius: 6px;
    padding: 20px 15px;
}
.premium-cta:after{
    content: '';
    display: block;
    clear: both;
}
.premium-cta p{
    margin: 7px 0;
    font-size: 14px;
    font-weight: 500;
    display: inline-block;
    width: 60%;
}
.premium-cta a.button{
    border-radius: 6px;
    height: 60px;
    float: right;
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/upgrade.png) #ff643f no-repeat 13px 13px;
    border-color: #ff643f;
    box-shadow: none;
    outline: none;
    color: #fff;
    position: relative;
    padding: 9px 50px 9px 70px;
}
.premium-cta a.button:hover,
.premium-cta a.button:active,
.premium-cta a.button:focus{
    color: #fff;
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/upgrade.png) #971d00 no-repeat 13px 13px;
    border-color: #971d00;
    box-shadow: none;
    outline: none;
}
.premium-cta a.button:focus{
    top: 1px;
}
.premium-cta a.button span{
    line-height: 13px;
}
.premium-cta a.button .highlight{
    display: block;
    font-size: 20px;
    font-weight: 700;
    line-height: 20px;
}
.premium-cta .highlight{
    text-transform: uppercase;
    background: none;
    font-weight: 800;
    color: #fff;
}

.section.one{
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/01-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.two{
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/02-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.three{
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/03-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.four{
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/04-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.five{
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/05-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.six{
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/06-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.seven{
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/07-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.eight{
    background: url(<?php echo YITH_WCBEP_ASSETS_URL?>/images/08-bg.png) no-repeat #fff; background-position: 85% 75%
}


@media (max-width: 768px) {
    .section{margin: 0}
    .premium-cta p{
        width: 100%;
    }
    .premium-cta{
        text-align: center;
    }
    .premium-cta a.button{
        float: none;
    }
}

@media (max-width: 480px){
    .wrap{
        margin-right: 0;
    }
    .section{
        margin: 0;
    }
    .landing-container .col-1,
    .landing-container .col-2{
        width: 100%;
        padding: 0 15px;
    }
    .section-odd .col-1 {
        float: left;
        margin-right: -100%;
    }
    .section-odd .col-2 {
        float: right;
        margin-top: 65%;
    }
}

@media (max-width: 320px){
    .premium-cta a.button{
        padding: 9px 20px 9px 70px;
    }

    .section .section-title img{
        display: none;
    }
}
</style>
<div class="landing">
    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Bulk Product Editing%2$s to benefit from all features!','yith-wcbep'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-wcbep');?></span>
                    <span><?php _e('to the premium version','yith-wcbep');?></span>
                </a>
            </div>
        </div>
    </div>
    <div class="one section section-even clear">
        <h1><?php _e('Premium Features','yith-wcbep');?></h1>
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/01.png" alt="<?php _e( 'PayPal','yith-wcbep') ?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/01-icon.png" alt="icon 01"/>
                    <h2><?php _e('Every WooCommerce field','yith-wcbep');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('No limit of action: the editing page of the plugin contains every detail, so that you will be free to %1$schange easily any information%2$s of the product, just like if you were in the WooCommerce page.%3$sStart sparing your time wisely, and apply the same modification with just one click on a huge number of your shop products.', 'yith-wcbep'), '<b>', '</b>','<br>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="two section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/02-icon.png" alt="icon 02" />
                    <h2><?php _e('Write before, after, replace...','yith-wcbep');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('A special way to edit some of the fields of your products, like title and description. %1$sYITH WooCommerce Bulk Product Editing%2$s lets you put a text before or after the original one, choose whether to give a brand new text for all products, or to replace the original one with the one you want to use.', 'yith-wcbep'), '<b>', '</b>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/02.png" alt="<?php _e( 'Write before, after, replace...','yith-wcbep') ?>" />
            </div>
        </div>
    </div>
    <div class="three section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/03.png" alt="<?php _e( 'Increase and decrease ','yith-wcbep') ?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/03-icon.png" alt="icon 03" />
                    <h2><?php _e( 'Increase and decrease ','yith-wcbep');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('How many times have you desired a tool to %1$sincrease%2$s or %1$sdecrease%2$s the price of your products? Perhaps a 20% off during holidays, or a small increment of a few dollars for your new market strategy. Now you can have it! ', 'yith-wcbep'), '<b>', '</b>');?>
                </p>
                <p>
                    <?php echo sprintf(__('Purchase the new version of the plugin, and choose between a fixed price variation, or a percentage one; you can also set a new %1$sprice%2$s for all products, if this is the solution that suits your needs. You couldn\'t ask for more!', 'yith-wcbep'), '<b>', '</b>');?>
                </p>
            </div>
        </div>
    </div>
    <div class="four section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/04-icon.png" alt="icon 04" />
                    <h2><?php _e('Filters','yith-wcbep');?></h2>
                </div>
                <p>
                    <?php echo sprintf(__('Thanks to the countless %1$sfilters%2$s that the plugin offers, you will have all the tools to get quickly the complete list of all products you want to edit in a clear and tidy screen. ', 'yith-wcbep'), '<b>', '</b>','<br>');?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/04.png" alt="<?php _e( 'Filters','yith-wcbep') ?>" />
            </div>
        </div>
    </div>
    <div class="five section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/05.png" alt="<?php _e( 'Permanent association','yith-wcbep') ?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/05-icon.png" alt="icon 05" />
                    <h2><?php _e('Variable products','yith-wcbep');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __( 'When we talk about a complete management of products, we mean a 360° control over all shop products. Even %1$svariable products%2$s are included in the editable product list of the plugin, with the freedom to customize the single variations of your shop. ','yith-wcbep' ),'<b>','</b>' ) ?>
                </p>
            </div>
        </div>
    </div>
    <div class="six section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/06-icon.png" alt="icon 06" />
                    <h2><?php _e('New product creation','yith-wcbep');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Web experience teaches us that customers are very satisfied when they can perform many actions on the same page. And just because satisfaction is one of our priorities, we offer you to create a new product from the page pf the plugin, without %1$sno difficulties%2$s if compared with the WooCommerce classic system.','yith-wcbep'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/06.png" alt="<?php _e( 'New product creation','yith-wcbep') ?>" />
            </div>
        </div>
    </div>
    <div class="seven section section-even clear">
        <div class="landing-container">
            <div class="col-1">
                <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/07.png" alt="<?php _e( 'Product removal','yith-wcbep') ?>" />
            </div>
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/07-icon.png" alt="icon 07" />
                    <h2><?php _e('Product removal','yith-wcbep');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('Select all those products you don\'t want anymore in your %1$sshop%2$s and delete them directly from the plugin configuration page. Keep your shop organized, and don\'t forget to delete those products you don\'t need anymore.','yith-wcbep'),'<b>','</b>'); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="eight section section-odd clear">
        <div class="landing-container">
            <div class="col-2">
                <div class="section-title">
                    <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/08-icon.png" alt="icon 08" />
                    <h2><?php _e('Import/export','yith-wcbep');?></h2>
                </div>
                <p>
                    <?php echo sprintf( __('The complete list of selected product can be exported in a text file with just one click. Take advantage of this feature to import at the same time a previously %1$sexported list of products%2$s you may need in a new WordPress installation.','yith-wcbep'),'<b>','</b>','<br>'); ?>
                </p>
                <p>
                    <?php echo sprintf( __('The last great idea of a plugin conceived to improve your experience with a multiple management of your shop products! ','yith-wcbep'),'<b>','</b>','<br>'); ?>
                </p>
            </div>
            <div class="col-1">
                <img src="<?php echo YITH_WCBEP_ASSETS_URL?>/images/08.png" alt="<?php _e( 'Click duration','yith-wcbep') ?>" />
            </div>
        </div>
    </div>

    <div class="section section-cta section-odd">
        <div class="landing-container">
            <div class="premium-cta">
                <p>
                    <?php echo sprintf( __('Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Bulk Product Editing%2$s to benefit from all features!','yith-wcbep'),'<span class="highlight">','</span>' );?>
                </p>
                <a href="<?php echo $this->get_premium_landing_uri() ?>" target="_blank" class="premium-cta-button button btn">
                    <span class="highlight"><?php _e('UPGRADE','yith-wcbep');?></span>
                    <span><?php _e('to the premium version','yith-wcbep');?></span>
                </a>
            </div>
        </div>
    </div>
</div>
