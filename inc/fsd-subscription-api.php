<?php
add_action("rest_api_init", function(){
	register_rest_route('subscription/v1', '/by-number/(?P<phone>\d+)', [
		[
			"methods"	=> "POST",
			"callback"	=> function(WP_REST_Request $req){
				global $wpdb;
				$current = $wpdb->get_results("SELECT vouchers FROM `{$wpdb->base_prefix}subscription_members` where phone = {$req['phone']}", ARRAY_N);
				if(!$current)return ['error' => 'Subscriber does not exist'];
				if($current[0][0] == 0)return ['error' => 'Subscriber is out of vouchers'];
				$wpdb->query("BEGIN TRAN");
				$query = $wpdb->prepare(
					"UPDATE `{$wpdb->base_prefix}subscription_members`
					SET vouchers = vouchers - 1
					WHERE phone = {$req['phone']}
				");
				$wpdb->query($query);
				$wpdb->query("COMMIT");
				$res = $wpdb->get_results("SELECT first_name, vouchers FROM `{$wpdb->base_prefix}subscription_members` where phone = {$req['phone']}", ARRAY_N);
				return [
					'first_name'	=> $res[0][0],
					'vouchers' 		=> $res[0][1],
				];
			},
			'permission_callback' => function(){
				return current_user_can('edit_others_posts');
			}
		],
		[
			"methods"	=> "GET",
			"callback"	=> function(WP_REST_Request $req){
				global $wpdb;
				$current = $wpdb->get_results("SELECT first_name, vouchers FROM `{$wpdb->base_prefix}subscription_members` where phone = {$req['phone']}", ARRAY_N);
				if(!$current)return ['error' => 'Subscriber does not exist'];
				if($current[0][0] == 0)return ['error' => 'Subscriber is out of vouchers'];
				return [
					'first_name'	=> $current[0][0],
					'vouchers'		=> $current[0][1],
				];
			},
			'permission_callback' => function(){
				return current_user_can('edit_others_posts');
			}
		],
	]);

	register_rest_route('subscription/v1', '/new-user', [
		[
			"methods"	=> "POST",
			"callback"	=> function(WP_REST_Request $req){
				global $wpdb;
				$current = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}subscription_members` where phone = {$req->get_param('phone')} or (first_name = '{$req->get_param('first_name')}' and last_name = '{$req->get_param('last_name')}')", ARRAY_N);
				if($current) return [
					'error' 			=> 'Subscriber already exists',
					'subscriber'	=> $current,
				];
				$wpdb->query("BEGIN TRAN");
				$query = $wpdb->prepare(
					"INSERT INTO `{$wpdb->base_prefix}subscription_members`
					VALUES ('{$req->get_param('first_name')}', '{$req->get_param('last_name')}', '{$req->get_param('phone')}', 3)
				");
				$res = $wpdb->query($query);
				$wpdb->query("COMMIT");
				if($res == true){
					return ['success' => true];
				}
			},
			'permission_callback' => function(){
				return current_user_can('edit_others_posts');
			}
		]
	]);
});
