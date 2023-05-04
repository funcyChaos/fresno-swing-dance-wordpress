<?php
	get_header('subscription');
?>

	<div class="subscription-grid">
		<div class="header-cell">
			<button id="new_user_btn">New Subscriber</button>
			<button id="renew_user_btn">Renew</button>
			<button id="check_vouchers_btn">Check Vouchers</button>
			<button id="edit_subscriber_btn">Edit Subscriber</button>
		</div>
		<form class="main-cell subscription-tick-form" id="sub_tick_form">
			<input required autofocus="true" type="tel" name="tick_user_phone" id="tick_user_phone" placeholder="(559) 492-6313">
			<input type="submit" value="Tick">
		</form>
		<form class="main-cell subscription-tick-form main-hide" id="sub_check_form">
			<button id="close_check_btn">Close</button>
			<h1>Check Users Vouchers</h1>
			<input required autofocus="true" type="tel" name="check_user_phone" id="check_user_phone" placeholder="(559) 492-6313">
			<input type="submit" value="Check">
		</form>
		<div class="main-cell main-hide" id="main_message">
			<h1 id="message_element"></h1>
		</div>
		<form class="main-cell new-user-form main-hide" id="new_user_form">
			<button id="nuf_close_btn" type="button">X</button>
			<h1>New Subscriber</h1>
			<label for="first_name">First Name</label>
			<input class="new-user-inputs" type="text" name="first_name" id="first_name" maxlength="20" required>
			<label for="last_name">Last Name</label>
			<input class="new-user-inputs" type="text" name="last_name" id="last_name" maxlength="20" required>
			<label for="new_phone">Phone Number</label>
			<input class="new-user-inputs" type="tel" name="new_phone" id="new_phone" required>
			<input type="submit" value="Submit">
		</form>
		<form class="main-cell new-user-form main-hide" id="update_user_form">
			<button id="uuf_close_btn" type="button">X</button>
			<h1>Edit Subscriber</h1>
			<label for="user_update_search">Search For User</label>
			<input class="new-user-inputs" type="text" name="user_update_search" id="user_update_search">
			<label for="upt_first_name">First Name</label>
			<input class="new-user-inputs" type="text" name="upt_first_name" id="upt_first_name" maxlength="20" required disabled>
			<label for="upt_last_name">Last Name</label>
			<input class="new-user-inputs" type="text" name="upt_last_name" id="upt_last_name" maxlength="20" required disabled>
			<label for="upt_phone">Phone Number</label>
			<input class="new-user-inputs" type="tel" name="upt_phone" id="upt_phone" required disabled>
			<label for="upt_vouchers">Vouchers</label>
			<input class="new-user-inputs" type="text" name="upt_vouchers" id="upt_vouchers" required disabled>
			<input type="submit" value="Update" disabled>
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