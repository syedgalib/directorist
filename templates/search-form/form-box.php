<?php
/**
 * @author  wpWax
 * @since   7.2.2
 * @version 7.7.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-form-box directorist-search-form__box">

	<div class="directorist-search-form-top directorist-flex directorist-align-center directorist-search-form-inline directorist-search-form__top directorist-search-modal directorist-search-modal--basic">
		<div class="directorist-search-modal__contents"></div>
		<?php
		foreach ( $searchform->form_data[0]['fields'] as $field ){
			$searchform->field_template( $field );
		}
		?>
	</div>
	<?php
		$searchform->more_buttons_template();
	?>

</div>

<div class="directorist-search-form-action__modal">
	<a href="#" class="directorist-btn directorist-btn-light directorist-search-form-action__modal__btn-search directorist-modal-btn directorist-modal-btn--basic">

		<?php directorist_icon( 'las la-search' ); ?>

		<?php echo esc_html( $searchform->search_button_text );?>

	</a>
	<a href="#" class="directorist-search-form-action__modal__btn-advanced directorist-modal-btn directorist-modal-btn--advanced">

		<?php directorist_icon( 'fas fa-sliders-h' ); ?>

	</a>
</div>

<div class="directorist-search-modal directorist-search-modal--advanced">
	<?php $searchform->advanced_search_form_advanced_fields_template();?>
</div>