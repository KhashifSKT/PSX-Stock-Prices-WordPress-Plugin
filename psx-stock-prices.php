<?php
/*
Plugin Name: PSX Stock Prices
Description: Fetches and displays stock prices from the PSX website using a shortcode.
Version: 1.0
Author: Khashif
License: GPL-2.0-or-later
*/

// Function to fetch stock prices from the PSX website
function fetch_psx_stock_price($symbol) {
    // URL of the PSX page
    $url = "https://dps.psx.com.pk/company/" . $symbol;

    // Fetch the webpage using WordPress's HTTP API
    $response = wp_remote_get($url);

    // Check if there is an error in the response
    if (is_wp_error($response)) {
        return 'Error fetching data: ' . $response->get_error_message();
    }

    // Get the HTML content of the response
    $html = wp_remote_retrieve_body($response);

    // Use a regular expression to extract the stock price (removing Rs.)
    if (preg_match('/<div class="quote__close">Rs\.(\d+\.\d+)<\/div>/', $html, $matches)) {
        return $matches[1]; // Return the extracted price without the 'Rs.' prefix
    } else {
        return 'Price not found in HTML';
    }
}

// Function to fetch stock price with cache
function fetch_psx_stock_price_with_cache($symbol) {
    // Sanitize the stock symbol input
    $symbol = sanitize_text_field($symbol); // Ensures no harmful characters in the symbol

    // Validate the stock symbol format (only alphanumeric and optional dashes)
    if (!preg_match('/^[A-Za-z0-9\-]+$/', $symbol)) {
        return 'Invalid stock symbol'; // Reject any invalid symbols
    }

    // Check if the price is cached
    $cached_price = get_transient('psx_price_' . $symbol);

    if ($cached_price !== false) {
        return $cached_price; // Return cached value if exists
    }

    // Fetch the price if not cached
    $price = fetch_psx_stock_price($symbol);

    // Cache the result for 720 minutes (12 hours)
    set_transient('psx_price_' . $symbol, $price, 720 * MINUTE_IN_SECONDS);

    return $price;
}

// Shortcode to display the stock price
function psx_stock_shortcode($atts) {
    // Default attributes for the shortcode
    $atts = shortcode_atts(array(
        'symbol' => 'FABL' // Default stock symbol
    ), $atts);

    // Fetch the stock price with cache
    $price = fetch_psx_stock_price_with_cache($atts['symbol']);

    // Return the result to be displayed on the frontend
    return "<div>The stock price of <strong>{$atts['symbol']}</strong> is: <strong>" . $price . "</strong></div>";
}
add_shortcode('fetch_psx_stock_price_with_cache', 'psx_stock_shortcode');

// Admin notice if cURL is not enabled
function psx_stock_admin_notice() {
    if (!function_exists('curl_version')) {
        echo '<div class="notice notice-error"><p><strong>PSX Stock Prices:</strong> cURL is not enabled on your server. This plugin requires cURL to fetch stock prices.</p></div>';
    }
}
add_action('admin_notices', 'psx_stock_admin_notice');
