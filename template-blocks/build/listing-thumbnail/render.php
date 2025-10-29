<?php
    $listing_prv_img = directorist_get_listing_preview_image( get_the_ID() );
?>

<div <?php echo get_block_wrapper_attributes(); ?>>
    <img src="<?php echo wp_get_attachment_image_url( $listing_prv_img, 'full' ); ?>">
</div>
