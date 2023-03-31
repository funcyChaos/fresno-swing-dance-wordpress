<?php
	get_header('subscription');
?>

	<div class="subscription-grid">
		<div class="header-cell">
			<button id="renew_user">Renew</button>
			<button id="new_user">New Subscriber</button>
			<button id="check_vouchers">Check Vouchers</button>
			<button id="edit_user">Edit Subscriber</button>
		</div>
		<form class="main-cell subscription-tick-form main-hide" id="sub_tick_form">
			<input required autofocus="true" type="tel" name="user_phone" id="user_phone" placeholder="(559) 492-6313">
			<input type="submit" value="Submit">
		</form>
		<div class="main-cell main-hide" id="main_message">
			<h1 id="message_element"></h1>
		</div>
		<!-- <form class="main-cell main-hide" id="new_user_form"> -->
		<form class="main-cell new-user-form" id="new_user_form">
			<button id="nuf_close" type="button">X</button>
			<h1>New Subscriber</h1>
			<label for="first_name">First Name</label>
			<input type="text" name="first_name" id="first_name" maxlength="20" required>
			<label for="last_name">Last Name</label>
			<input type="text" name="last_name" id="last_name" maxlength="20" required>
			<label for="new_phone">Phone Number</label>
			<input type="tel" name="new_phone" id="new_phone" required>
			<input type="submit" value="Submit">
		</form>
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