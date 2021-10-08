<?php
if( isset( $_GET[ 'Login' ] ) ) { // Для прохождения такой проверки нужно отправить запрос методом GET с параметром go в теле запроса
	// Get username
	$user = $_GET[ 'username' ]; // После использования get запроса в дампе окажутся логин и пароль в явном виде
	// Get password
	$pass = $_GET[ 'password' ];
	$pass = md5( $pass ); //Не безопасное хэширование пароля. 
	// На данный момент существуют несколько видов «взлома» хешей MD5 — подбора сообщения с заданным хешем 
	// 1. Перебор по словарю
	// 2. Brute-force
	// 3. RainbowCrack
	// 4. Коллизия хеш-функции
	
	$query  = "SELECT * FROM `users` WHERE user = '$user' AND password = '$pass';"; // SQL injection - инъекция появляется из входящих данных, которые не фильтруются. ( CWE-89: Improper Neutralization of Special Elements used in an SQL Command ('SQL Injection') )
	$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );
	if( $result && mysqli_num_rows( $result ) == 1 ) {
		$row    = mysqli_fetch_assoc( $result );
		$avatar = $row["avatar"];
		$html .= "<p>Welcome to the password protected area {$user}</p>";
		$html .= "<img src=\"{$avatar}\" />";
	}
	else {
		$html .= "<pre><br />Username and/or password incorrect.</pre>";
	}
	((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}
?>