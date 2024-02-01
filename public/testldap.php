<?php

if(isset($_POST['username']) && isset($_POST['password'])){

    $adServer = "ldaps://it.telekom.yu";
	ldap_set_option(NULL, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_ALLOW);
    $ldap = ldap_connect($adServer);
	if (!$ldap)
        echo 'Could not connect to LDAP server';
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_adm = "CN=svc.iotbilling,OU=ServisniNalozi,OU=SpecijalniNalozi,DC=it,DC=telekom,DC=yu";
    $pass_adm = "Pet.2022!";

    $ldaprdn = 'mydomain' . "\\" . $username;

    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($ldap, $user_adm, $pass_adm);
	ldap_get_option($ldap, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);
	
	if (!empty($extended_error))
        {
			echo $extended_error;
		}

    if ($bind) {
        $filter="(sAMAccountName=$username)";
        $result = ldap_search($ldap,"OU=Direkcije,DC=it,DC=telekom,DC=yu",$filter);
 //       ldap_sort($ldap,$result,"sn");
        $info = ldap_get_entries($ldap, $result);
        for ($i=0; $i<$info["count"]; $i++)
        {
            if($info['count'] > 1)
                break;
            echo "<p>You are accessing <strong> ". $info[$i]["sn"][0] .", " . $info[$i]["givenname"][0] ."</strong><br /> (" . $info[$i]["distinguishedname"][0] .")\n</p>\n";
			for ($j=0; $j<$info[$i]["memberof"]["count"]; $j++)
			{
				echo "(" . $info[$i]["memberof"][$j] .")\n";
			}
 //           echo '<pre>';
 //          var_dump($info);
 //           echo '</pre>';
        $userDn = $info[$i]["distinguishedname"][0]; 
//	    $userDn = $info[$i]["dn"];
	    echo $userDn;
	    echo $password;
        }

//	$bind_user = @ldap_bind($ldap, $userDn, $password);
//	if ($bind_user) {
//		echo "YO";
//	}
//	else{
//		$msg = "Pogresno";
 //       	echo $msg;
//	}

        @ldap_close($ldap);
	
    } else {
        $msg = "Invalid email address / password";
        echo $msg;
    }

}else{
?>
    <form action="#" method="POST">
        <label for="username">Username: </label><input id="username" type="text" name="username" /> 
        <label for="password">Password: </label><input id="password" type="password" name="password" />        <input type="submit" name="submit" value="Submit" />
    </form>
<?php } ?> 