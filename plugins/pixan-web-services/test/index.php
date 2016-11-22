<html>
<head>
	<title></title>
</head>
<body>


<?php require_once '../../../../wp-load.php'; ?>


<?php define('DABBA_API_AUTH_KEY', '62c7T5ljHphf83abXs0o2zDDO687P6DF'); ?>


	<h2>Login</h2>
Url: <code><?php bloginfo('url'); ?>/api/login</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/login" method="post" target="_blank">



<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>

<label for="type">type [post]</label>
<select name="type" id="type">
	<option value="site">site</option>
	<option value="facebook">facebook</option>
	<option value="google">google</option>
</select><br/>


<label for="user_login">user_login [post] - (optional for FB type)</label>
<input type="text" name="user_login" id="user_login" value=""/><br/>

<label for="user_password">user_password [post] - optional</label>
<input type="text" name="user_password" id="user_password" value=""/><br/>


<label for="facebook_uid">facebook_uid [post] - optional</label>
<input type="text" name="facebook_uid" id="facebook_uid" value=""/><br/>

<label for="google_uid">google_uid [post] - optional</label>
<input type="text" name="google_uid" id="google_uid" value=""/><br/>


<input type="submit" value="Enviar"/>



</form>


<h2>Zones</h2>
Url: <code><?php bloginfo('url'); ?>/api/get_zones</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/get_zones" method="post" target="_blank">



<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>

<input type="submit" value="Enviar"/>



</form>


<h2>Register</h2>
Url: <code><?php bloginfo('url'); ?>/api/register</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/register" method="post" target="_blank">



<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>


<label for="type">type [post]</label>
<select name="type" id="type">
	<option value="site">site</option>
	<option value="facebook">facebook</option>
	<option value="google">google</option>
</select><br/>


<label for="user_email">user_email [post] - (optional for FB type)</label>
<input type="text" name="user_email" id="user_email" value=""/><br/>



<label for="user_password">user_password [post] - optional</label>
<input type="text" name="user_password" id="user_password" value=""/><br/>


<label for="facebook_uid">facebook_uid [post] - optional</label>
<input type="text" name="facebook_uid" id="facebook_uid" value=""/><br/>

<label for="google_uid">google_uid [post] - optional</label>
<input type="text" name="google_uid" id="google_uid" value=""/><br/>


<input type="submit" value="Enviar"/>

</form>



<h2>Today Menu</h2>
Url: <code><?php bloginfo('url'); ?>/api/today_menu</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/today_menu" method="post" target="_blank">



<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>


<input type="submit" value="Enviar"/>

</form>



<h2>Weekend Menu</h2>
Url: <code><?php bloginfo('url'); ?>/api/weekend_menu</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/weekend_menu" method="post" target="_blank">

<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>

<input type="submit" value="Enviar"/>

</form>



<h2>Orders</h2>
Url: <code><?php bloginfo('url'); ?>/api/orders</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/orders" method="post" target="_blank">

<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>

<label for="user_id">user_id [int]</label>
<input type="text" name="user_id" id="user_id" value=""/><br/>

<label for="limit">limit [int]</label>
<input type="text" name="limit" id="limit" value="10"/><br/>

<label for="page">page [int] (page start at 0)</label>
<input type="text" name="page" id="page" value="0"/><br/>

<input type="submit" value="Enviar"/>

</form>


<h2>Lost password</h2>
Url: <code><?php bloginfo('url'); ?>/api/lost_password</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/lost_password" method="post" target="_blank">

<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>

<label for="user_email">user_email [post]</label>
<input type="text" name="user_email" id="user_email" value=""/><br/>


<input type="submit" value="Enviar"/>

</form>



<h2>Get coupon</h2>
Url: <code><?php bloginfo('url'); ?>/api/get_coupon</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/get_coupon" method="post" target="_blank">

<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>

<label for="coupon_code">coupon_code [varchar]</label>
<input type="text" name="coupon_code" id="coupon_code" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>


<h2>Get coupon by user</h2>
Url: <code><?php bloginfo('url'); ?>/api/get_coupon_by_user</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/get_coupon_by_user" method="post" target="_blank">

<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>

<label for="user_id">user_id [int]</label>
<input type="text" name="user_id" id="user_id" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>


<h2>Change password</h2>
Url: <code><?php bloginfo('url'); ?>/api/change_password</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/change_password" method="post" target="_blank">

<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo DABBA_API_AUTH_KEY; ?>"/><br/>

<label for="user_login">user_login [post] </label>
<input type="text" name="user_login" id="user_login" value=""/><br/>


<label for="old_password">old_password [post] </label>
<input type="text" name="old_password" id="old_password" value=""/><br/>

<label for="new_password">new_password [post] </label>
<input type="text" name="new_password" id="new_password" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>

</body>
</html>