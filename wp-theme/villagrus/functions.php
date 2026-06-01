<?php
defined('ABSPATH') || exit;

/* ── Theme Setup ─────────────────────────────────────────────── */
function villagrus_setup() {
    load_theme_textdomain('villagrus', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'primary' => __('Primär meny', 'villagrus'),
        'footer'  => __('Footermeny', 'villagrus'),
    ]);
}
add_action('after_setup_theme', 'villagrus_setup');

/* ── Enqueue Assets ──────────────────────────────────────────── */
function villagrus_enqueue() {
    $ver = wp_get_theme()->get('Version');
    $uri = get_template_directory_uri();

    // Google Fonts
    wp_enqueue_style(
        'villagrus-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style('villagrus-style', $uri . '/assets/css/villagrus.css', ['villagrus-fonts'], $ver);

    // Main JS
    wp_enqueue_script('villagrus-main', $uri . '/assets/js/main.js', [], $ver, true);

    // Pass data to JS
    wp_localize_script('villagrus-main', 'villagrusData', [
        'ajaxUrl'  => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('villagrus_nonce'),
        'cartUrl'  => wc_get_cart_url(),
        'shopUrl'  => get_permalink(wc_get_page_id('shop')),
        'themeUri' => $uri,
    ]);
}
add_action('wp_enqueue_scripts', 'villagrus_enqueue');

/* ── WooCommerce ─────────────────────────────────────────────── */

// Remove default WC styles (we use our own)
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Remove WC wrappers — we build our own layout
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar',             'woocommerce_get_sidebar', 10);

// Products per page
add_filter('loop_shop_per_page', fn() => 24);

// Custom add-to-cart AJAX
add_action('wp_ajax_villagrus_add_to_cart',        'villagrus_ajax_add_to_cart');
add_action('wp_ajax_nopriv_villagrus_add_to_cart', 'villagrus_ajax_add_to_cart');

function villagrus_ajax_add_to_cart() {
    check_ajax_referer('villagrus_nonce', 'nonce');

    $product_id   = absint($_POST['product_id'] ?? 0);
    $variation_id = absint($_POST['variation_id'] ?? 0);
    $quantity     = absint($_POST['quantity'] ?? 1);

    if (!$product_id) wp_send_json_error('Ogiltig produkt');

    $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);

    if ($cart_item_key) {
        wp_send_json_success([
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
        ]);
    } else {
        wp_send_json_error('Kunde inte lägga till i kundvagn');
    }
}

// Cart count for header
add_action('wp_ajax_villagrus_cart_count',        'villagrus_cart_count');
add_action('wp_ajax_nopriv_villagrus_cart_count', 'villagrus_cart_count');

function villagrus_cart_count() {
    wp_send_json_success(['count' => WC()->cart->get_cart_contents_count()]);
}

/* ── Helper Functions ────────────────────────────────────────── */

function villagrus_logo() {
    $logo = get_template_directory_uri() . '/assets/images/logo.png';
    $home = home_url('/');
    echo '<a href="' . esc_url($home) . '" class="nav-logo" aria-label="VillaGrus — till startsidan">
        <img src="' . esc_url($logo) . '" alt="VillaGrus" width="120" height="40">
    </a>';
}

function villagrus_cart_icon() {
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $url   = wc_get_cart_url();
    echo '<a href="' . esc_url($url) . '" class="nav-cart" aria-label="Kundvagn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="20" height="20">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span class="nav-cart__count' . ($count > 0 ? ' is-visible' : '') . '" id="cartCount">' . esc_html($count) . '</span>
    </a>';
}

// Format price in Swedish style
function villagrus_format_price($price) {
    return number_format((float)$price, 0, ',', ' ') . ' kr';
}
