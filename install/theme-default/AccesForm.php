<?php session_start();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>CPANEL::Administration</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="keywords" content="vtrace, krak"/>
	<meta name="description" content="krakCMS est un CMS dévéloppé par Armand Kouassi" />
	<meta name="robots" content="noindex,nofollow">
	
	<link rel="stylesheet" id="wp-admin-css" href="css/wp-css/wp-admin.css" type="text/css" media="all">
	<link rel="stylesheet" id="buttons-css" href="css/wp-css/buttons.css" type="text/css" media="all">
	<link rel="stylesheet" id="colors-fresh-css" href="css/wp-css/colors-fresh.css" type="text/css" media="all">
</head>
<body>

	<div id="accessForm">
		<div class="login login-action-login wp-core-ui">
			<div id="login">
				<div style="text-align:center;font-size:20px;margin-bottom:10px;color:orangered;">Administration</div>
				<div class="info"><?php //print $info; ?></div>
				<form id="loginform" action="verif.php" method="post">
					<p>
						<label for="user_login">Nom d'utilisateur<br>
						<input name="login" id="user_login" class="input" size="20" type="text"></label>
					</p>
					<p>
						<label for="user_pass">Mot de passe<br>
						<input name="pass" id="user_pass" class="input" value="" size="20" type="password"></label>
					</p>
					<p class="forgetmenot"><label for="rememberme"><input name="rememberme" id="rememberme" value="forever" type="checkbox">Se souvenir de moi</label></p>
					<p class="submit">
						<input name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Accéder" type="submit">
					</p>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>

</body>
</html>