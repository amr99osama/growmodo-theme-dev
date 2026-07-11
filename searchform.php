<?php
/**
 * Custom search form.
 *
 * @package realestate
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="s"><?php esc_html_e( 'Search for:', 'realestate' ); ?></label>
	<input type="search" id="s" class="search-form__input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'realestate' ); ?>">
	<button type="submit" class="btn btn--primary search-form__submit"><?php re_icon( 'search' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Search', 'realestate' ); ?></span></button>
</form>
