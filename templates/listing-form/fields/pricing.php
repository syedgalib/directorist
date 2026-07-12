<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listing_id              = $listing_form->get_add_listing_id();
$price                   = get_post_meta( $listing_id, '_price', true );
$price_range             = get_post_meta( $listing_id, '_price_range', true );
$price_type              = get_post_meta( $listing_id, '_atbd_listing_pricing', true );
$allow_decimal           = get_directorist_option( 'allow_decimal', 1 );
$currency_symbol         = atbdp_currency_symbol( directorist_get_currency() );
$configured_pricing_type = $data['pricing_type'];
$resolved_pricing_type   = directorist_resolve_conditional_pricing_type( $data, static function ( $field ) use ( $listing_id ) { return $listing_id ? directorist_get_listing_conditional_value( $listing_id, $field ) : null; } );
$data['pricing_type']    = 'conditional' === $configured_pricing_type ? $resolved_pricing_type : $configured_pricing_type;
$pricing_rules           = $data['pricing_type_mapping'] ?? [];

// Get conditional logic attributes using centralized method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );
?>
<div class="directorist-form-group directorist-form-pricing-field price-type-<?php echo esc_attr( $data['pricing_type'] ); ?>"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?><?php if ( 'conditional' === $configured_pricing_type ) : ?> data-conditional-pricing="<?php echo esc_attr( wp_json_encode( $pricing_rules ) ); ?>"<?php endif; ?>>
    <?php $listing_form->field_label_template( $data ); ?>

    <?php if ( $data['pricing_type'] === 'both' || 'conditional' === $configured_pricing_type ) { ?>
        <div class="directorist-form-pricing-field__options" data-pricing-part="both">
            <div class="directorist-checkbox directorist_pricing_options">
                <input type="checkbox" id="price_selected" value="price" name="atbd_listing_pricing" <?php checked( $price_type, 'price' ); ?>>
                <label for="price_selected" class="directorist-checkbox__label" data-option="price"><?php echo esc_html( $data['price_unit_field_label'] );?></label>
            </div>

            <?php if ( ! empty( $price_unit_checkbox ) ) : ?>
                <span class="directorist-form-pricing-field__options__divider"><?php esc_html_e( 'Or', 'directorist' ); ?></span>
            <?php endif; ?>

            <div class="directorist-checkbox directorist_pricing_options">
                <input type="checkbox" id="price_range_selected" value="range" name="atbd_listing_pricing" <?php checked( $price_type, 'range' ); ?>>
                <label for="price_range_selected" class="directorist-checkbox__label" data-option="price_range"><?php echo esc_html( $data['price_range_label'] ); ?></label>
            </div>
        </div>
    <?php } ?>

    <?php if ( $data['pricing_type'] === 'both' || $data['pricing_type'] === 'price_unit' || 'conditional' === $configured_pricing_type ) { ?>
        <input data-pricing-part="price_unit" class="directorist-form-element directory_field directory_pricing_field" id="price" type="<?php echo esc_attr( $data['price_unit_field_type'] ); ?>" name="price" step="<?php echo esc_attr( $allow_decimal ? 'any' : 1 ); ?>" value="<?php echo esc_attr( $price ); ?>" placeholder="<?php echo esc_attr( ! empty( $data['price_unit_field_placeholder'] ) ? $data['price_unit_field_placeholder'] : '' ); ?>" />
    <?php } ?>

    <?php if ( $data['pricing_type'] === 'both' || $data['pricing_type'] === 'price_range' || 'conditional' === $configured_pricing_type ) { ?>
        <select data-pricing-part="price_range" class="directorist-form-element directory_field directory_pricing_field" id="price_range" name="price_range">
            <option value=""><?php echo esc_html( ! empty( $data['price_range_placeholder'] ) ? $data['price_range_placeholder'] : '' ); ?></option>
            <option value="skimming"<?php selected( $price_range, 'skimming' ); ?>><?php echo esc_html( sprintf( __( 'Ultra High (%s)', 'directorist' ), str_repeat( $currency_symbol, 4 ) ) );?></option>
            <option value="moderate" <?php selected( $price_range, 'moderate' ); ?>><?php echo esc_html( sprintf( __( 'Moderate (%s)', 'directorist' ), str_repeat( $currency_symbol, 3 ) ) );?></option>
            <option value="economy" <?php selected( $price_range, 'economy' ); ?>><?php echo esc_html( sprintf( __( 'Economy (%s)', 'directorist' ), str_repeat( $currency_symbol, 2 ) ) ); ?></option>
            <option value="bellow_economy" <?php selected( $price_range, 'bellow_economy' ); ?>><?php echo esc_html( sprintf( __( 'Cheap (%s)', 'directorist' ), str_repeat( $currency_symbol, 1 ) ) ); ?></option>
        </select>
    <?php } ?>

    <?php if ( $data['pricing_type'] === 'price_unit' ) :?>
        <input type="hidden" name="atbd_listing_pricing" value="price">
    <?php endif; ?>

    <?php if ( $data['pricing_type'] === 'price_range' ) :?>
        <input type="hidden" name="atbd_listing_pricing" value="range">
    <?php endif; ?>

    <?php if ( 'conditional' === $configured_pricing_type ) : ?>
        <input type="hidden" name="atbd_listing_pricing" value="<?php echo esc_attr( 'price_range' === $resolved_pricing_type ? 'range' : 'price' ); ?>" data-conditional-pricing-type>
    <?php endif; ?>
</div>
