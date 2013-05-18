<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Оперативная сводка за день</title>
<style type="text/css">
	body {
		background-color: #F4F6F7;
	}
	
	td {
    padding: 10px;
	}
	
	#centerLayer {
    width: 900px;
	margin-left:auto;
	margin-right:auto;
	font-family: Ubuntu, Tahoma, sans-serif;
	font-size: 16px;
	background-color: #FFFFFF;
	border: 1px solid rgba(0, 0, 0, 0.15);
	border-radius: 4px 4px 4px 4px;
	box-shadow: 0 0 6px rgba(0, 0, 0, 0.05);
	padding: 20px;
   }
   
   .onefirm {
   padding: 10px;
   }
   
   .header {
   font-size: 18px;
   }
</style>
</head>

<body>
<div id="centerLayer">
<?php

// типичные ошибки: употребление одного и того же условия в case дважды, функция explode, организация правильной вставки div

$current_date = date("d.m.Y");
$dbserver = "localhost";
$user = "";			//enter usen_name
$passw = ""; 		//enter passwd	
$database = "careerday";
$table_name = "companies";
$need_fields = "timestamp, company_name, person, phone, email, vacancies, additional, city_rnd, rnd_ff, rnd_pi, rnd_iarchi, city_taganrog, city_novoshakht";
echo "<h1>Оперативная сводка</h1>";
echo "<p>Сегодня - $current_date</p>";

$conn = mysqli_connect($dbserver, $user, $passw, $database);
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}

if (!mysqli_set_charset($conn, "utf8")) {
	printf("Ошибка при загрузке набора символов utf8: %s\n", mysqli_error($conn));
	} 
	
	else {
		$sql_pre="SELECT `timestamp` FROM `companies`";
		$result1=mysqli_query($conn,$sql_pre);
		while($row1=mysqli_fetch_array($result1,MYSQLI_NUM)) {
			foreach($row1 as $key1=>$value1)
			$value1 = explode(" ", $value1);
			$days[] = $value1[0];
		}
		$days=array_values(array_unique($days));
		// print_r ($days);
		mysqli_free_result($result1);
		foreach ($days as $value_day)
		{
		echo '<h2>'.$value_day.'</h2>';
			$sql="SELECT $need_fields FROM $table_name WHERE `timestamp` LIKE '$value_day %'";
			$result=mysqli_query($conn,$sql);
			while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				$a=0; $b=0; $c=0;
				// print_r ($row);
				echo '<div class="onefirm">';
				foreach($row as $key=>$value) 
				{
					switch ($key) {
						case "timestamp":
						$value = explode(" ", $value);
						$value1 = explode(":", $value[1]);
						echo '<span class="header"><b>'.$value1[0].':'.$value1[1].' </b>';
						break;
						
						case "company_name":
						echo "$value</span></br>";
						break;
						
						case "vacancies":
						echo "<b>Вакансии:</b> ".$value."</br>";
						break;
						
						////////////////////////////////////////////////////////////////
						case "city_rnd":
						if ($value==0) break;
						elseif ($value==1) {
							$c++; $text.="<br>Ростов-на-Дону - ";
							}
						break;
						
						case "rnd_ff":
						if ($value==1) {$b++; $a++; $text.=" физический факультет ";}
						break;
						
						case "rnd_pi":
						if ($value==1) {$b++; $a++; $text.=" пединститут ";}
						break;
						
						case "rnd_iarchi":
						if ($value==1) {$a++; $b++; $text.=" ИАРХИ ";}
						break;
						
						case "city_taganrog":
						if ($value==1) {$c++; $b++; $text.="<br>Таганрог";}
						break;
						
						case "city_novoshakht":
						if ($value==1) {$c++; $b++; $text.="Новошахтинск";}
						break;
						
						case "additional":
						if (!$value) break;
						else echo "<b>Комментарий для нас:</b> ".$value."</br>";
						break;
						////////////////////////////////////////////////////////////////
					default:
					echo "$value</br>";
					}	
				}
				echo "Участвует на $b площадках: $text</br>";
				$text='';
				echo "</div>";
			}
		}
	}
	
	// echo "Количество городов - $c, количество площадок - $b, количество площадок в Ростове - $a</br>";
	
mysqli_free_result($result);
mysqli_close($conn);
?>
</tr>
</table>
</div>
</body>
</html>