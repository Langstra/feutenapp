<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('includes/start.php');
$server = array(
	'authenticate' => function($params) {
		try
		{
			$salt = 'frisenfruitig36';
			$cryptpass = crypt($params['password'],$salt);
			$board_members = q("SELECT id FROM board_members WHERE username=? AND password=?",array($params['username'],$cryptpass));
			if (n($board_members)>0)
			{
				$token = bin2hex(openssl_random_pseudo_bytes(16));
				$board_member = f($board_members);
				q("INSERT INTO auth_tokens (token, board_members_id) VALUES (?,?)",array($token,$board_member[0]['id']));
				return array('token' => $token);
			}
			else
			{
				return false;
			}
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'add_noob' => function($params) {
		try
		{
			$data = q("SELECT id FROM associations WHERE id = (SELECT associations_id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?))",array($params['token']));
			$association = f($data);
			q("INSERT INTO noobs (name, img_url, associations_id) VALUES (?,?,?)", array($params['name'],$params['img_url'],$association[0]['id']));
			return true;
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'add_points' => function($params) {
		try
		{
			$data = q("SELECT id FROM associations WHERE id = (SELECT associations_id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?))",array($params['token']));
			$association = f($data);
			q("INSERT INTO points (reason_text, reason_file, create_time, amount) VALUES (?,?,".time().",?)",array($params['reason_text'],$params['reason_file'],$params['amount']));
			$points_id = last();
			foreach ($params['noob_ids'] as $n)
			{
				q("INSERT INTO noobs_has_points (noobs_id, points_id) VALUES (?,?)",array($n,$points_id));
			}
			return true;
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'get_noob' => function($params) {
		try
		{
			$data = q("SELECT id FROM associations WHERE id = (SELECT associations_id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?))",array($params['token']));
			$association = f($data);
			$noobs = q("SELECT noobs.id, name, img_url, IFNULL(SUM(amount),0) as total FROM noobs LEFT JOIN noobs_has_points ON noobs.id=noobs_has_points.noobs_id LEFT JOIN points ON noobs_has_points.points_id=points.id WHERE associations_id=? AND noobs.id=?",array($association[0]['id'],$params['noob_id']));
			return f($noobs)[0];
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'get_noobs' => function($params) {
		try
		{
			$data = q("SELECT id FROM associations WHERE id = (SELECT associations_id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?))",array($params['token']));
			$association = f($data);
			$noobs = q("SELECT noobs.id, name, img_url, IFNULL(SUM(amount),0) as total FROM noobs LEFT JOIN noobs_has_points ON noobs.id=noobs_has_points.noobs_id LEFT JOIN points ON noobs_has_points.points_id=points.id WHERE associations_id=? GROUP BY noobs.id",array($association[0]['id']));
			return f($noobs);
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'get_points' => function($params) {
		try
		{
			$data = q("SELECT id FROM associations WHERE id = (SELECT associations_id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?))",array($params['token']));
			$association = f($data);
			$noobcheck = q("SELECT id FROM noobs WHERE associations_id=?",array($association[0]['id']));
			if (n($noobcheck)>0)
			{
				$points = q("SELECT id, amount, reason_text, reason_file, create_time FROM points WHERE id IN (SELECT points_id FROM noobs_has_points WHERE noobs_id=?)",array($params['noob_id']));
				return f($points);
			}
			else
			{
				return false;
			}
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'get_total_points' => function($params) {
		try
		{
			$data = q("SELECT id FROM associations WHERE id = (SELECT associations_id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?))",array($params['token']));
			$association = f($data);
			$total = q("SELECT SUM(points.amount) as total FROM noobs, noobs_has_points, points WHERE associations_id=? AND noobs.id=noobs_has_points.noobs_id AND noobs_has_points.points_id=points.id GROUP BY noobs.associations_id",array($association[0]['id']));
			return f($total)[0]['total'];
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'get_point_list' => function($params) {
		try
		{
			$data = q("SELECT id FROM associations WHERE id = (SELECT associations_id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?))",array($params['token']));
			$association = f($data);
			$point_list = q("SELECT noobs.id, SUM(points.amount) as total FROM noobs, noobs_has_points, points WHERE associations_id=? AND noobs.id=noobs_has_points.noobs_id AND noobs_has_points.points_id=points.id GROUP BY noobs.id",array($association[0]['id']));
			return f($points_list);
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'get_point_days' => function($params) {
		try
		{
			$data = q("SELECT id FROM associations WHERE id = (SELECT associations_id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?))",array($params['token']));
			$association = f($data);
			$days = q("SELECT FROM_UNIXTIME(create_time,'%Y-%m-%d') as day FROM points, noobs_has_points, noobs WHERE points.id=noobs_has_points.points_id AND noobs_has_points.noobs_id=noobs.id AND noobs.associations_id=? GROUP BY day",array($association[0]['id']));
			return f($days);
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'add_boardmember' => function($params) {
		try
		{
			$data = q("SELECT id FROM associations WHERE id = (SELECT associations_id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?))",array($params['token']));
			$association = f($data);
			$salt = 'frisenfruitig36';
			$cryptpass = crypt($params['password'],$salt);
			q("INSERT INTO board_members (username, password, associations_id) VALUES (?,?,?)",array($params['username'],$cryptpass,$association[0]['id']));
			return true;
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'edit_password' => function($params) {
		try
		{
			$user_data = q("SELECT id FROM board_members WHERE id = (SELECT board_members_id FROM auth_tokens WHERE token=?)",array($params['token']));
			$salt = 'frisenfruitig36';
			$crypt_current_pass = crypt($params['current_password'],$salt);
			$crypt_new_pass = crypt($params['new_password'],$salt);
			$check = q("UPDATE board_members SET password=? WHERE password=? AND id=?",array($crypt_new_pass,$crypt_current_pass,f($user_data)[0]['id']));
			if (n($check)>0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'register_association' => function($params) {
		try
		{
			q("INSERT INTO associations (name) VALUES (?)",array($params['association_name']));
			$salt = 'frisenfruitig36';
			$cryptpass = crypt($params['password'],$salt);
			q("INSERT INTO board_members (username, password, associations_id) VALUES (?,?,?)",array($params['username'],$cryptpass,last()));
			$token = bin2hex(openssl_random_pseudo_bytes(16));
			q("INSERT INTO auth_tokens (token, board_members_id) VALUES (?,?)",array($token,last()));
			return array('token' => $token);
		}
		catch (PDOException $ex)
		{
			return false;
		}
	},
	'get_user_association' => function($params) {
		try
		{
			$data = q("SELECT username, name as association_name FROM associations, auth_tokens, board_members WHERE token=? AND board_members_id=board_members.id AND associations_id=associations.id", array($params['token']));
			return f($data)[0];
		}
		catch (PDOException $ex)
		{
			return false;
		}
	}

	);

Tivoka\Server::provide($server)->dispatch();
?>