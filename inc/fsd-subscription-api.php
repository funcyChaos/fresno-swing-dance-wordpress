<?php

// CREATE TABLE `wp_subscription_members` (
//   ID INT AUTO_INCREMENT primary key NOT NULL,
//   first_name varchar(20) DEFAULT NULL,
//   last_name varchar(20) DEFAULT NULL,
//   phone char(10) DEFAULT NULL,
//   vouchers int(11) DEFAULT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
// INSERT INTO wp_subscription_members (first_name, last_name, phone, vouchers)
// VALUES ('Austin', 'Reilly', 5593600378, 3) 

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
					"INSERT INTO `{$wpdb->base_prefix}subscription_members` (first_name, last_name, phone, vouchers)
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
	register_rest_route('subscription/v1', '/renew-user', [
		[
			"methods"	=> "PATCH",
			"callback"	=> function(WP_REST_Request $req){
				global $wpdb;
				$current = $wpdb->get_results("SELECT vouchers FROM `{$wpdb->base_prefix}subscription_members` where phone = {$req->get_param('phone')}", ARRAY_N);
				if($current){
					$wpdb->query("BEGIN TRAN");
					$query = $wpdb->prepare(
						"UPDATE `{$wpdb->base_prefix}subscription_members`
						SET vouchers = 3
						WHERE phone = {$req->get_param('phone')}
					");
					$res = $wpdb->query($query);
					$wpdb->query("COMMIT");
				}else{
					return ['error' => 'cannot find user'];
				}
				if($res)return ['patch' => $res];
				else return ['error' => 'error'];
			},
			'permission_callback' => function(){
				return current_user_can('edit_others_posts');
			}
		]
	]);

	register_rest_route('subscription/v1', '/update-user', [
		[
			"methods"	=> "GET",
			"callback"	=> function(WP_REST_Request $req){
				global $wpdb;
				$current = $wpdb->get_results("SELECT first_name, last_name, phone, vouchers FROM `{$wpdb->base_prefix}subscription_members` where phone = '{$req->get_param('var')}' or first_name = '{$req->get_param('var')}' or last_name = '{$req->get_param('var')}'", ARRAY_N);
				if(!$current)return ['error'=>'no subscriber'];
				return ['subscriber'	=> $current];
			},
			'permission_callback' => function(){
				return current_user_can('edit_others_posts');
			}
		],
		[
			"methods"	=> "PATCH",
			"callback"	=> function(WP_REST_Request $req){
				global $wpdb;
				$current = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}subscription_members` where first_name = '{$req->get_param('current')}'", ARRAY_N);
				$wpdb->query("BEGIN TRAN");
				$query = $wpdb->prepare(
					"UPDATE `{$wpdb->base_prefix}subscription_members`
					SET first_name = '{$req->get_param('first_name')}', last_name = '{$req->get_param('last_name')}', phone = '{$req->get_param('phone')}', vouchers = '{$req->get_param('vouchers')}'
					WHERE ID = {$current[0][0]}
				");
				$res = $wpdb->query($query);
				$wpdb->query("COMMIT");
				$updated = $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}subscription_members` where ID = {$current[0][0]}", ARRAY_N);
				if($res)return ['patch' => $updated];
				return['error'=>'error'];
			},
			'permission_callback' => function(){
				return current_user_can('edit_others_posts');
			}
		]
	]);
});
