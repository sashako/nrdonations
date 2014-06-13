<?php
	
	$args = array(
		'numberposts' => 100,
		'posts_per_page' => -1,
		'orderby' => 'post_date',
		'post_type' => 'donation'
	);

	$donations = $wp_query->query($args);

?>
<div class="wrap">
	<h2>Donations</h2>
	<p>This table is just an overview of the most recent 100 donations. For more detailed information check out your Stripe account.</p>
	<table class="wp-list-table widefat">
		<thead>
			<tr>
				<th><b>Date</b></th>
				<th><b>Name</b></th>
				<th><b>Email</b></th>
				<th><b>Amount</b></th>
				<th><b>Subscribed to<br />the newsletter</b></th>
			</tr>
		</thead>
		<tbody id="the-list">
<?php if(!empty($donations)) : ?>
<?php 	foreach($donations as $k=>$d) : ?>
<?php $class = $k%2==0 ? ' class="alternate"':''; ?>
		<tr<?php echo $class; ?>>
			<td><?php echo $d->post_date; ?></td>
			<td><?php echo get_post_meta($d->ID, 'donator', true); ?></td>
			<td><?php echo get_post_meta($d->ID, 'email', true); ?></td>
			<td><?php echo '$'.get_post_meta($d->ID, 'amount', true); ?></td>
			<td><?php echo get_post_meta($d->ID, 'subscribed', true)==='true' ? 'yes' : 'no' ;?></td>
		</tr>	
<?php 	endforeach; ?>
<?php else : ?>
			<tr class="no-items">
				<td><i>No donations found</i></td>
			</tr>
<?php endif; ?>		
		</tbody>
	</table>
</div>