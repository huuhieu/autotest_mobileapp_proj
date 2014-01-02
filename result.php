<?php
	if (isset($_POST['upload'])) {
		$email = $_GET["email"];
		if (!file_exists('Projects')) {
			mkdir("Projects");
		}
		$createuserfol = "cd Projects & mkdir $email";
		exec($createuserfol,$status,$string);

		if ($_FILES["file"]["error"] > 0) {
			$er = "ERROR Return Code: " . $_FILES["file"]["error"] . "<br />";
			echo "$er";
		} else {
			//lấy link git của feature và tên project từ form
			$feature = $_POST["feature"];
			$projname = $_POST["projname"];			

			$createprojfolder = "cd Projects/$email & mkdir $projname";	//Tạo thư mục chứa project của người dùng
			exec($createprojfolder,$status,$string);
		move_uploaded_file($_FILES["file"]["tmp_name"], "Projects/$email/$projname/" . $_FILES["file"]["name"]);
		/*	echo "Upload: " .  $_FILES["file"]["name"] . "<br />";
			echo "Type: " .  $_FILES["file"]["type"] . "<br />";
			echo "Size: " .  $_FILES["file"]["size"] . "<br />";
			echo "Temp file: " .  $_FILES["file"]["tmp_name"] . "<br />";
			echo "Upload:" .  $_FILES["file"]["name"] . "<br />";
			
		//	echo "Stored in: " . "Projects/" . $_FILES["file"]["name"];
		*/

			//Clone thư mục features về máy
			$clonefeatures = "cd Projects/$email/$projname & git clone $feature";
			exec($clonefeatures,$status,$string);
			//Xử lí chuỗi để lấy được tên thư mục repository và đổi tên thành features
			if (substr($feature, 0, 5) == 'https') {
				$pos = strrpos($feature, '/'); 
				$str = substr($feature, $pos+1); 
			} else {
				$pos1 = strrpos($feature, '/'); //vị trí của kí tự '/'
				$pos2 = strrpos($feature, '.');  //vị trí của kí tự '.'
				$str = substr($feature, $pos1+1, -4); //lấy tên của repository
			}
			$rnfolder = "cd Projects/$email/$projname & rename $str features";
			exec($rnfolder,$status,$string);

			//Chạy lệnh test với calabash và in ra kết quả 
			$command = "cd Projects/$email/$projname & calabash-android run " . $_FILES["file"]["name"] . ">test.txt";
			passthru($command,$err);
		//	foreach ($status as $result) {
		//		echo $result . "<br />";
		//	}
			echo "Kết quả sẽ được gửi lại cho bạn sau";
			//Hiển thị các screenshot
		/*	$dir = "./Projects/$projname/";
			if (is_dir($dir)) {
    			if ($dh = opendir($dir)) {
       				while (($file = readdir($dh)) !== false) {
       					if (substr($file, -3) == 'png') {	//chọn ra các file ảnh
            				echo "Filename: $file <br>";
            				echo "<img src='http://localhost/testmobileapps/Projects/$projname/$file' />";
            			}
        			}
        			closedir($dh);
    			}
			
			}*/
		}
	}
?>