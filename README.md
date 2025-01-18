# PSX-Stock-Prices-WordPress-Plugin

## Description
The **PSX Stock Prices** WordPress plugin fetches and displays live stock prices from the **Pakistan Stock Exchange (PSX)** website using a simple shortcode. This plugin allows you to easily embed stock prices on your WordPress site, making it ideal for financial bloggers, investors, and websites that track stock data.

### Features
- Fetches real-time stock prices from the PSX website.
- Simple shortcode support to display stock prices anywhere on your WordPress site.
- Caching mechanism to store stock prices for **720 minutes** (12 hours) to reduce unnecessary HTTP requests and improve page load performance.
- Sanitizes and validates user inputs to ensure security (prevents XSS and other vulnerabilities).
- Admin notification if cURL is not enabled on the server, as it is required for fetching data.

## Installation

### Option 1: Install via WordPress Admin
1. Go to **Plugins > Add New** in your WordPress dashboard.
2. Search for **PSX Stock Prices**.
3. Click **Install Now** and then activate the plugin.

### Option 2: Manual Installation
1. Download the plugin as a ZIP file.
2. Upload the ZIP file to the **wp-content/plugins/** directory on your WordPress installation.
3. Go to **Plugins > Installed Plugins** and activate **PSX Stock Prices**.

## Usage

To display the stock price on your site, use the following shortcode in any post or page:

```plaintext
[fetch_psx_stock_price_with_cache symbol="FABL"]
