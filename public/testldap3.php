<?php

if(isset($_POST['username']) && isset($_POST['password'])){

    $adServer = "ldaps://it.telekom.yu";
	$admin_group = "CN=iotbill.admin,OU=iot-MM,OU=Security,OU=Grupe,DC=it,DC=telekom,DC=yu";
	$user_group = "CN=iotbill.user,OU=iot-MM,OU=Security,OU=Grupe,DC=it,DC=telekom,DC=yu";
	$readonly_group = "CN=iotbill.readonly,OU=iot-MM,OU=Security,OU=Grupe,DC=it,DC=telekom,DC=yu";
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
        ldap_sort($ldap,$result,"sn");
       $info = ldap_get_entries($ldap, $result);

       echo "<pre>";  print_r($info); echo"</pre>";



 //      echo($fullName);

       for ($i=0; $i<$info["count"]; $i++)
        {
            if($info['count'] > 1)
                break;

        $userDn = $info[$i]["distinguishedname"][0];
//	    $userDn = $info[$i]["dn"];


        }
	if ($info["count"]>0){
	$bind_user = @ldap_bind($ldap, $userDn, $password);
	if ($bind_user) {
		echo "<p>\nKorisnik se uspesno logovao\n</p>";
		$filter1="(&(sAMAccountName=$username)(memberof=$admin_group))";
		$result1 = ldap_search($ldap,$userDn,$filter1);
		$info1 = ldap_get_entries($ldap, $result1);
		if ( $info1["count"]>0 ){
			echo "<p> Korisnik je admin\n</p>";
		}
		else{
			$filter1="(&(sAMAccountName=$username)(memberof=$user_group))";
			$result1 = ldap_search($ldap,$userDn,$filter1);
			$info1 = ldap_get_entries($ldap, $result1);
			if ( $info1["count"]>0 ){
				echo "<p> Korisnik je user\n</p>";
			}
			else{
			$filter1="(&(sAMAccountName=$username)(memberof=$readonly_group))";
			$result1 = ldap_search($ldap,$userDn,$filter1);
			$info1 = ldap_get_entries($ldap, $result1);
			if ( $info1["count"]>0 ){
				echo "<p> Korisnik je readonly\n</p>";
			}
			else {
				echo "<p> Korisnik nije u odgovarajucoj grupi\n</p>";
			}
			}
		}

	}
	else{
		$msg = "Pogresan password";
        	echo $msg;
	}
	}
	else{
		echo "<p> Nepostojeci korisnik\n</p>";
	}


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
