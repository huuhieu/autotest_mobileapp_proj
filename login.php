
<?php
	// Cau hinh Goolge API
	$google_client_id         = '586413670460-41t2maurd4v427k00vm54s7qpn52bhro.apps.googleusercontent.com';
	$google_client_secret     = 'RXWMygJYLKrtcb-2RFnlvsxF';
	$google_redirect_url     = 'http://localhost/testmobileapps/login.php'; // Sua lai bang duong link cua ban
	$google_developer_key     = 'spring-duality-438@appspot.gserviceaccount.com';
	 
	/* ==========================================================================================================*/
	 
	require_once 'src/Google_Client.php';
	require_once 'src/contrib/Google_Oauth2Service.php';
	session_start();
	$gClient = new Google_Client();
	$gClient->setApplicationName('Đăng nhập bằng tài khoản Google');
	$gClient->setClientId($google_client_id);
	$gClient->setClientSecret($google_client_secret);
	$gClient->setRedirectUri($google_redirect_url);
	$gClient->setDeveloperKey($google_developer_key);
	$google_oauthV2 = new Google_Oauth2Service($gClient);
	if (isset($_REQUEST['reset']))
	{
	    unset($_SESSION['token']);
	    $gClient->revokeToken();
	    header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
	}
	if (isset($_GET['code']))
	{
	    $gClient->authenticate($_GET['code']);
	    $_SESSION['token'] = $gClient->getAccessToken();
	    header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
	    return;
	}
	if (isset($_SESSION['token']))
	{
	    $gClient->setAccessToken($_SESSION['token']);
	}
	if ($gClient->getAccessToken())
	{
	    $user                 = $google_oauthV2->userinfo->get();
	    $user_id             = $user['id'];
	    $user_name             = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	    $email                 = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	    $profile_url         = filter_var($user['link'], FILTER_VALIDATE_URL);
	    $gender        		 = filter_var($user['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
	    $locale        		 = filter_var($user['locale'], FILTER_SANITIZE_SPECIAL_CHARS);
	    $_SESSION['token']     = $gClient->getAccessToken();
	}
	else
	{
	    $authUrl = $gClient->createAuthUrl();
	}
	echo '<!DOCTYPE html>';
	echo '<html>';
	echo '<head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo '<title>Đăng nhập</title>';
	echo '</head>';
	echo '<body>';
	echo '<h1>Để sử dụng dịch vụ, mời bạn đăng nhập với tài khoản Google</h1>';
	if(isset($authUrl))
	{
	    echo '<a class="login" href="'.$authUrl.'"><img src="images/google-login-button.png" title="Login with Google" width="250" height="60" /></a>';
	}
	else
	{
		header("location: index.php?user_name=$user_name&email=$email&gender=$gender&locale=$locale");
	}
	echo '</body></html>';
	$today = getdate();
	//print_r($today);

?>