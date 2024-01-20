<?php
/*
Plugin Name: Restaurant Cards
Description: Custom post type for generating restaurant cards.
Version: 1.0
Author: Cup O Code
*/

// Enqueue Font Awesome library
function custom_restaurant_cards_enqueue_scripts() {
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' );
}
add_action( 'wp_enqueue_scripts', 'custom_restaurant_cards_enqueue_scripts' );

// Register the custom restaurant cards custom post type
function custom_restaurant_cards_register_post_type() {
    $labels = array(
        'name' => 'Restaurant Cards',
        'singular_name' => 'Card',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'supports' => array( 'title', 'thumbnail' ),
        'menu_icon' => 'dashicons-food', // Set the menu icon to a food icon
        'has_archive' => true,
        'rewrite' => array( 'slug' => 'restaurant-cards' ),
        'hierarchical' => false,
        'show_in_rest' => true,
        'orderby' => 'title', // Order posts alphabetically by title
        'order' => 'ASC'
    );

    register_post_type( 'restaurant-cards', $args );
}
add_action( 'init', 'custom_restaurant_cards_register_post_type' );


// Add custom meta box for restaurant card details
function custom_restaurant_cards_add_meta_box() {
    add_meta_box(
        'custom-restaurant-cards-details',
        'Details',
        'custom_restaurant_cards_meta_box_callback',
        'restaurant-cards',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'custom_restaurant_cards_add_meta_box' );

// Save restaurant card details meta box data
function custom_restaurant_cards_save_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['custom_restaurant_cards_nonce'] ) || ! wp_verify_nonce( $_POST['custom_restaurant_cards_nonce'], basename( __FILE__ ) ) ) {
        return;
    }

    // Customize the field names as needed
    if ( isset( $_POST['custom_restaurant_extra_info'] ) ) {
        update_post_meta( $post_id, 'custom_restaurant_extra_info', sanitize_text_field( $_POST['custom_restaurant_extra_info'] ) );
    }

    if ( isset( $_POST['custom_restaurant_location'] ) ) {
        update_post_meta( $post_id, 'custom_restaurant_location', sanitize_text_field( $_POST['custom_restaurant_location'] ) );
    }

    if ( isset( $_POST['custom_restaurant_website'] ) ) {
        update_post_meta( $post_id, 'custom_restaurant_website', sanitize_text_field( $_POST['custom_restaurant_website'] ) );
    }

    if ( isset( $_POST['custom_restaurant_facebook'] ) ) {
        update_post_meta( $post_id, 'custom_restaurant_facebook', sanitize_text_field( $_POST['custom_restaurant_facebook'] ) );
    }

    if ( isset( $_POST['custom_restaurant_instagram'] ) ) {
        update_post_meta( $post_id, 'custom_restaurant_instagram', sanitize_text_field( $_POST['custom_restaurant_instagram'] ) );
    }

    // Add the new field for Google Location Link
    if ( isset( $_POST['custom_google_location_link'] ) ) {
        update_post_meta( $post_id, 'custom_google_location_link', esc_url( $_POST['custom_google_location_link'] ) );
    }
}
add_action( 'save_post', 'custom_restaurant_cards_save_meta_box_data' );

// Callback function for the restaurant card details meta box
function custom_restaurant_cards_meta_box_callback($post) {
    wp_nonce_field(basename(__FILE__), 'custom_restaurant_cards_nonce');
    $custom_restaurant_extra_info = get_post_meta($post->ID, 'custom_restaurant_extra_info', true);
    $custom_restaurant_location = get_post_meta($post->ID, 'custom_restaurant_location', true);
    $custom_restaurant_website = get_post_meta($post->ID, 'custom_restaurant_website', true);
    $custom_restaurant_facebook = get_post_meta($post->ID, 'custom_restaurant_facebook', true);
    $custom_restaurant_instagram = get_post_meta($post->ID, 'custom_restaurant_instagram', true);
    $custom_google_location_link = get_post_meta($post->ID, 'custom_google_location_link', true); // Add this line

    ?>
    <p>
        <label for="custom_restaurant_location">Location:</label>
        <input type="text" name="custom_restaurant_location" id="custom_restaurant_location" value="<?php echo esc_attr($custom_restaurant_location); ?>">
    </p>
    <p>
        <label for="custom_google_location_link">Google Location Link:</label> <!-- Add this input field -->
        <input type="text" name="custom_google_location_link" id="custom_google_location_link" value="<?php echo esc_url($custom_google_location_link); ?>">
    </p>
    <p>
        <label for="custom_restaurant_website">Website:</label>
        <input type="text" name="custom_restaurant_website" id="custom_restaurant_website" value="<?php echo esc_attr($custom_restaurant_website); ?>">
    </p>

    <p>
        <label for="custom_restaurant_facebook">Facebook:</label>
        <input type="text" name="custom_restaurant_facebook" id="custom_restaurant_facebook" value="<?php echo esc_attr($custom_restaurant_facebook); ?>">
    </p>
    <p>
        <label for="custom_restaurant_instagram">Instagram:</label>
        <input type="text" name="custom_restaurant_instagram" id="custom_restaurant_instagram" value="<?php echo esc_attr($custom_restaurant_instagram); ?>">
    </p>
    <p>
        <label for="custom_restaurant_extra_info">Additional Info:</label>
        <input type="text" name="custom_restaurant_extra_info" id="custom_restaurant_extra_info" value="<?php echo esc_attr($custom_restaurant_extra_info); ?>">
    </p>

    <?php
}

// Shortcode to display restaurant cards
function custom_restaurant_cards_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => -1,
    ), $atts, 'custom_restaurant_cards');

    $args = array(
        'post_type' => 'restaurant-cards',
        'posts_per_page' => $atts['count'],
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $restaurant_cards = new WP_Query($args);

    ob_start();
    if ($restaurant_cards->have_posts()) {
        ?>
       <style>
    .custom-restaurant-cards-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-evenly;
    margin: -15px;
}

.custom-restaurant-card-card {
    width: 400px; /* Set a fixed width for the cards */
    background-color: #ffffff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    padding: 20px;
    margin: 15px;
    text-align: center;
    box-sizing: border-box; /* Ensure padding is included in width calculation */
}

.custom-restaurant-card-image {
    margin-bottom: 15px;
}

.custom-restaurant-card-image img {
    width: 100%;
    max-height: 250px; /* Use max-height to maintain consistency */
    object-fit: cover;
}

.custom-restaurant-card-content {
    margin-bottom: 15px;
}

.custom-restaurant-card-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px;
}

.custom-restaurant-card-location {
    margin-top: 5px;
}

.custom-restaurant-card-social {
    display: flex;
    justify-content: center;
    margin-top: 5px;
}

.custom-restaurant-card-social i {
    font-size: 1.5em;
    padding: 0 10px;
}

.custom-restaurant-card-content a {
    color: #DB7928;
}

a.custom-restaurant-card-website {
    background: #DB7928;
    color: #fff;
    padding: 6px;
    border-radius: 9px;
    white-space: nowrap;
    overflow: hidden;
}

a.custom-restaurant-card-website:hover,
a.custom-restaurant-card-website:active {
    border: 1px solid #DB7928;
    background: #fff;
    color: #DB7928;
}

.custom-restaurant-extra-info {
    font-style: italic;
}

/* Responsive styles for two columns */
@media (max-width: 768px) {
    .custom-restaurant-cards-wrapper {
        justify-content: center;
    }
    .custom-restaurant-card-card {
        width: calc(50% - 30px); /* Adjust the width for two columns */
    }
}

/* Responsive styles for one column */
@media (max-width: 649px) {
    .custom-restaurant-card-card {
        width: calc(100% - 30px); /* Adjust the width for one column */
    }
}

</style>

        <div class="custom-restaurant-cards-wrapper">
        <?php while ($restaurant_cards->have_posts()) : $restaurant_cards->the_post();
        ?>
            <div class="custom-restaurant-card-card">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="custom-restaurant-card-image">
                        
                            <?php the_post_thumbnail(); ?>
                      
                    </div>
                <?php endif; ?>
                <div class="custom-restaurant-card-content">
                    <h3 class="custom-restaurant-card-name"><?php the_title(); ?></h3>
                    <?php if (get_post_meta(get_the_ID(), 'custom_google_location_link', true)) : ?>
                        <p class="custom-restaurant-card-location">
                            <a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'custom_google_location_link', true)); ?>" target="_blank"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html(get_post_meta(get_the_ID(), 'custom_restaurant_location', true)); ?></a>
                        </p>
                    <?php endif; ?>

                    <?php if (get_post_meta(get_the_ID(), 'custom_restaurant_website', true)) : ?>
                        <a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'custom_restaurant_website', true)); ?>" target="_blank" class="custom-restaurant-card-website">Visit <?php the_title(); ?> Online </a>
                    <?php endif; ?>
                    <br>
                    <br>
                    <div class="custom-restaurant-card-social">
                        <?php if (get_post_meta(get_the_ID(), 'custom_restaurant_facebook', true)) : ?>
                            <a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'custom_restaurant_facebook', true)); ?>" target="_blank" data-id="<?php the_ID(); ?>"><i class="fab fa-facebook"></i></a>
                        <?php endif; ?>
                        <?php if (get_post_meta(get_the_ID(), 'custom_restaurant_instagram', true)) : ?>
                            <a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'custom_restaurant_instagram', true)); ?>" target="_blank" data-id="<?php the_ID(); ?>"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                    </div>
					<br>
                    <?php if (get_post_meta(get_the_ID(), 'custom_restaurant_extra_info', true)) : ?>
                        <p class="custom-restaurant-extra-info">
                            <?php echo esc_html(get_post_meta(get_the_ID(), 'custom_restaurant_extra_info', true)); ?></a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
        <?php
    }
    $output = ob_get_clean();
    wp_reset_postdata();
    return $output;
}

add_shortcode('custom_restaurant_cards', 'custom_restaurant_cards_shortcode');