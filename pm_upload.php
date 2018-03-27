<?php
date_default_timezone_set("Asia/Kolkata");

function replace_string_in_file($filename, $string_to_replace, $replace_with)
{
    $content=file_get_contents($filename);
    $content_chunks=explode($string_to_replace, $content);
    $content=implode($replace_with, $content_chunks);
    file_put_contents($filename, $content);
}

$time = date("Y-m-d H:i:s");

require 'query/conn.php';

	// $username = 'root';
	// $password = '';
	// $db_name = 'neon';
	$outlet = 'aundh';

	$tables = array('menu','users','master');

	foreach ($tables as $table) {
		
		switch ($table) {
			case 'users':
				$p_id = 'user_id';
				break;
			case 'menu':
				$p_id = 'id';
				break;
			case 'master':
				$p_id = 'm_id';
				break;			
			default:
				$p_id = 'error';
				break;
		}		


		if($p_id != 'error'){

			$filename ='upload/'.$outlet.'_'.$table.'.sql';

			$sql0="SELECT `id` FROM `upload` WHERE `name` = '$table';";
			$row0= mysqli_fetch_assoc(mysqli_query($conn,$sql0));
			$id = $row0['id'];

			if ($id == 0 ) {

				$target_url = 'http://pumpmastertest.greenboxinnovations.in/neon_get_last_id.php?p_id='.$p_id.'&outlet='.$outlet.'&table='.$table;
		 		
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$target_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$result=curl_exec ($ch);
				curl_close ($ch);
				$id =  $result;

			}

			if ($id != 0) 
			{
				$sql0="SELECT 1 FROM `$table` WHERE `$p_id` > '".$id."';";
				$exe = mysqli_query($conn,$sql0);
				$row_count = mysqli_num_rows($exe);
				if($row_count > 0){
			
					
					exec ("C:/xampp/mysql/bin/mysqldump -u\"".$user_name."\" --password=\"".$password."\" -t \"".$db_name."\" \"".$table."\"  --where=\"".$p_id." > '".$id."' \">".$filename);
																

					$string_to_replace = $table;
				 	$replace_with = $outlet."_".$table;

				 	replace_string_in_file($filename, $string_to_replace, $replace_with);


				 	if (file_exists($filename)) {

				 		$target_url = 'http://neon.greenboxinnovations.in/neon_savefile.php';
				 		$file_name_with_full_path = realpath($filename);
						// $post = array('extra_info' => '123456','file_contents'=>'@'.$file_name_with_full_path);
						$post['file_contents'] = new CurlFile($file_name_with_full_path, 'text/plain');
						$post['outlet'] = $outlet;
						$post['table'] 	= $table;
						$post['id'] 	= $id;
						$post['p_id'] 	= $p_id;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL,$target_url);
						curl_setopt($ch, CURLOPT_POST,1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$result=curl_exec ($ch);
						curl_close ($ch);
						echo $result;


						$sql0="UPDATE `upload` SET `id` = '".$result."' WHERE `name` = '".$table."';";
						$exe = mysqli_query($conn,$sql0);
				 	}
				
				}
				else
				{
					echo 'No new data';
				}
				
			}//if not
		}//if not error
	}//foreach

?>