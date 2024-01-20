# Restaurant Cards WordPress Plugin

## Description

The Restaurant Cards WordPress plugin is a custom post type for generating restaurant cards. It allows you to create and display restaurant cards on your WordPress website with additional details, such as location, website, social media links, and extra information.

## Plugin Details

- **Plugin Name:** Restaurant Cards
- **Version:** 1.0
- **Author:** Cup O Code

## Installation

1. Download the `restaurant-cards` folder.
2. Upload the `restaurant-cards` folder to your WordPress plugins directory (usually `wp-content/plugins`).
3. Activate the plugin through the WordPress admin dashboard.

## Usage

### Enqueue Font Awesome Library

The plugin enqueues the Font Awesome library to provide icons. You can customize this by modifying the URL in the `custom_restaurant_cards_enqueue_scripts` function.

### Custom Post Type

The plugin registers a custom post type called "Restaurant Cards" with the following features:

- Supports: Title and Thumbnail
- Menu Icon: Set to a food icon
- Has Archive: Enabled
- Rewrite Slug: 'restaurant-cards'
- Order Posts: Alphabetically by title

### Custom Meta Box

A custom meta box is added to the restaurant card edit screen. It allows you to enter additional information for each restaurant card, including:

- Location
- Google Location Link
- Website
- Facebook
- Instagram
- Additional Info

### Shortcode

You can use the `[custom_restaurant_cards]` shortcode to display restaurant cards on any page or post. You can specify the number of cards to display using the `count` attribute. Example: `[custom_restaurant_cards count="3"]`.

### Styling

The plugin provides CSS styles for restaurant cards, making them visually appealing. You can further customize the styles to match your website's design.

## Support and Issues

If you encounter any issues or need support, please contact me.

## Credits

This plugin was developed by [Cup O Code](https://cupocode.com/).

Enjoy using the Restaurant Cards WordPress plugin!
