<?php
	get_header('subscription');
?>

	<div class="subscription-grid">
			<form class="main-cell subscription-tick-form" id="sub_tick_form">
				<input required autofocus="true" type="tel" name="users phone" id="user_phone" placeholder="(559) 492-6313">
				<input type="submit" value="Submit">
			</form>
		<div class="main-cell main-hide" id="form_success">
			<h1 id="success_message">You have 3 remaining vouchers!</h1>
		</div>
		<div class="main-cell main-hide" id="form_fail">
			<h1 id="error_message">That number doesn't have any vouchers</h1>
		</div>
	</div>

	<script>
		function temp(){
			fetch('<?=home_url()?>/wp-json/subscription/v1/by-number/1234567890',{
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce':		'<?=wp_create_nonce('wp_rest')?>',
				},
			})
			.then(res=>res.json())
			.then(obj=>console.log(obj))
		}
	</script>

<?php
	get_footer('subscription');