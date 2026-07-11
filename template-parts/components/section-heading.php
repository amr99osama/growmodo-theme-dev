<?php
/**
 * Section heading block: eyebrow + title + optional sub-copy.
 *
 * @param array $args {
 *   @type string $eyebrow
 *   @type string $heading
 *   @type string $subcopy
 *   @type bool   $center
 *   @type string $tag     Heading tag (default h2).
 * }
 * @package realestate
 */

$re_eyebrow = $args['eyebrow'] ?? '';
$re_heading = $args['heading'] ?? '';
$re_subcopy = $args['subcopy'] ?? '';
$re_center  = ! empty( $args['center'] );
$re_tag     = $args['tag'] ?? 'h2';

if ( ! $re_eyebrow && ! $re_heading && ! $re_subcopy ) {
	return;
}
?>
<div class="section-head<?php echo $re_center ? ' section-head--center' : ''; ?>">
	<?php if ( $re_eyebrow ) : ?>
		<span class="eyebrow"><?php echo esc_html( $re_eyebrow ); ?></span>
	<?php endif; ?>
	<?php if ( $re_heading ) : ?>
		<<?php echo tag_escape( $re_tag ); ?>><?php echo esc_html( $re_heading ); ?></<?php echo tag_escape( $re_tag ); ?>>
	<?php endif; ?>
	<?php if ( $re_subcopy ) : ?>
		<p><?php echo esc_html( $re_subcopy ); ?></p>
	<?php endif; ?>
</div>
