<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$label = $section_data['label'] ?? '';
$id    = str_replace( ' ', '-', strtolower( $label ) );
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $section_data );
?>
<section class="directorist-form-section directorist-content-module multistep-wizard__single" id="add-listing-content-<?php echo esc_attr( $id ?? '' ); ?>"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
    <header class="directorist-content-module__title">
        <?php echo esc_html( ! empty( $section_data['icon'] ) ?  directorist_icon( $section_data['icon'] ) : '' ); ?>
        <h2><?php echo esc_html( $section_data['label'] );?></h2>
    </header>

    <section class="directorist-content-module__contents">

        <?php 
        foreach ( $section_data['fields'] as $field ) {
            $listing_form->field_template( $field );
        }
        ?>

    </section>
</section>