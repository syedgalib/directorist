<?php
/**
 * Directorist Pricing Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Pricing_Field extends Base_Field {
    public $type = 'pricing';

    public function get_value( $posted_data ) {
        $configured_price_type = $this->get_price_type_prop( $posted_data );
        if ( $configured_price_type !== 'both' ) {
            $posted_data['atbd_listing_pricing'] = $configured_price_type;
        }

        if ( ! isset( $posted_data['atbd_listing_pricing'] ) && ( isset( $posted_data['price'] ) || isset( $posted_data['price_range'] ) ) ) {
            return [];
        }

        return [
            'price_type'  => sanitize_text_field( directorist_get_var( $posted_data['atbd_listing_pricing'] ) ),
            'price'       => round( (float) directorist_get_var( $posted_data['price'], 0 ), 2 ),
            'price_range' => sanitize_text_field( directorist_get_var( $posted_data['price_range'] ) )
        ];
    }

    public function validate( $posted_data ) {
        $value = $this->get_value( $posted_data );

        if ( ! empty( $value['price_type'] ) && ! in_array( $value['price_type'], $this->get_price_types(), true ) ) {
            /* translators: %s: Price type value */
            $this->add_error( sprintf( __( 'Invalid price type: %s', 'directorist' ), esc_html( $value['price_type'] ) ) );
        }

        if ( $value['price_type'] === 'range' && ! empty( $value['price_range'] ) && ! in_array( $value['price_range'], $this->get_price_ranges(), true ) ) {
            $this->add_error( __( 'Invalid price range.', 'directorist' ) );
        }

        if ( $this->has_error() ) {
            return false;
        }

        return true;
    }

    protected function get_price_types() {
        return [ 'price', 'range' ];
    }

    protected function get_price_ranges() {
        return [ 'skimming', 'moderate', 'economy', 'bellow_economy' ];
    }

    protected function get_price_type_prop( array $posted_data = [] ) {
        $pricing_type = $this->__get( 'pricing_type' );

        if ( 'conditional' === $pricing_type ) {
            $pricing_type = directorist_resolve_conditional_pricing_type(
                [
                    'pricing_type'         => 'conditional',
                    'pricing_type_mapping' => $this->__get( 'pricing_type_mapping' ),
                ],
                static function ( $field ) use ( $posted_data ) {
                    if ( in_array( $field, [ 'category', 'categories', 'admin_category_select[]', 'in_cat' ], true ) ) return $posted_data['admin_category_select'] ?? ( $posted_data['tax_input'][ ATBDP_CATEGORY ] ?? [] );
                    if ( in_array( $field, [ 'location', 'locations', 'tax_input[at_biz_dir-location][]', 'in_loc' ], true ) ) return $posted_data['tax_input'][ ATBDP_LOCATION ] ?? [];
                    if ( in_array( $field, [ 'tag', 'tags', 'tax_input[at_biz_dir-tags][]' ], true ) ) return $posted_data['tax_input'][ ATBDP_TAGS ] ?? [];
                    $normalized = preg_replace( '/^_/', '', $field );
                    return $posted_data[ $normalized ] ?? ( $posted_data[ $field ] ?? null );
                }
            );
        }

        if ( $pricing_type === 'price_unit' ) {
            return 'price';
        } elseif ( $pricing_type === 'price_range' ) {
            return 'range';
        } else {
            return 'both';
        }
    }
}

Fields::register( new Pricing_Field() );
