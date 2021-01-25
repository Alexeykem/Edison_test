<?php
session_start();
$medium_names=array('medium1','medium2');
if (!$_POST['Status'] or $_POST['Status']=="empty") {
	echo '<form method="POST" action='.$_SERVER['PHP_SELF'].'>';
	echo '<input type="submit" name="Status" value="Zagadal">';
	echo '<input type="submit" name="Status" value="Sbros">';
	echo '</form>';
	if ($_POST['Status']=="empty") {
		foreach ($medium_names as $name) {
			echo $name.' '.implode(";", $_SESSION[$name]['gueses_list']);
			Echo '<br>';
			echo $name.' точность  '.implode(";", $_SESSION[$name]['ratio_list']);
			echo 'Счёт: '.$_SESSION[$name]['credibility'];
		}
		Echo '<br>';
		echo 'Пользователь  '.implode(";", $_SESSION['User']);
		
	}
}

if ($_POST['Status']=='Zagadal') {
	
	//логика
	$medium_guesses=medium_guess(2);
	echo print_medium_gues($medium_guesses);
	$i=0;
	foreach ($medium_names as $name) {
		$_SESSION[$name]['gueses_list'][] = $medium_guesses[$i];
		$i++;
	}
	
	//конец логики
	
	echo '<form method="POST" action='.$_SERVER['PHP_SELF'].'>';
	echo '<input type="text" name="myNum" value="" size="2">';
	echo '<input type="submit" name="Status" value="Proverka">';
	echo '</form>';
}

if ($_POST['Status']=='Proverka') {
	
	//логика
	foreach ($medium_names as $name){
		
		$ratio=count_guess_ratio($_POST['myNum'],end($_SESSION[$name]['gueses_list']));
		$_SESSION[$name]['ratio_list'][] = round($ratio,3)*100;
		
		if (empty($_SESSION[$name]['credibility'])) {
				$credibility[$name]=100;
		} else {
			$credibility[$name]=$_SESSION[$name]['credibility'];
		}
		// можно поиграться с планкой точности и добавлять очки если не точное попадание а хотя бы 95%
		if ($ratio<>1) {
			$_SESSION[$name]['credibility'] = $credibility[$name]-1;
		}else {
			$_SESSION[$name]['credibility'] = $credibility[$name]+1;
		}
	}
	
	$_SESSION['User'][]=$_POST['myNum'];
	//конец логики
	
	echo '<form method="POST" action='.$_SERVER['PHP_SELF'].'>';
	echo '<input type="submit" name="Status" value="empty">';
	echo '</form>';
}
if ($_POST['Status']=='Sbros') {

	session_destroy();

}
//возвращает массив догадок медиумов в ответ на заданное число медиумов
function medium_guess($medium_num){
	for ($i=1;$i<=$medium_num;$i++){
		$result[]=random_int(0,99);
	}
	return $result;
}

//получает массив из случайных "догадок" возвращает сообщение типа Экстросенс №: число
function print_medium_gues($medium_guess){
	$i=1;
	foreach ($medium_guess as $val){
		$temp[]="Экстрасенс №".$i.':'.$val;
	}
	return implode("<br>", $temp);
}

function count_guess_ratio($user_num,$medium_guess){
	if ($user_num<=$medium_guess) {
		$result=$user_num/$medium_guess;
	} else {
		$result=$medium_guess/$user_num;
	}
	return $result;
}
//data_formt
/*
 $_SESSION['medium1']=array ("gueses_list"=> array(),"ratio_list"=> array(),credibility);
 $_SESSION['medium2']=array ("gueses_list"=> array(),"ratio_list"=> array(),credibility);
 $_SESSION['User']=array ();
 */