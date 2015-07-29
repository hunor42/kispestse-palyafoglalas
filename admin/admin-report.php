<?php
	require_once($base_path.'classes/checkframework.php');

	if(is_array($_POST) && count($_POST)>0){
		$from_date = $_POST['from_date'];
		$to_date = $_POST['to_date'];
		$courts = $_POST['courts'];
		$username = $_POST['username'];

		$court_names = array();
		if(is_array($courts) && count($courts)>0)
			$court_names = Court::get_names($courts);
		else
			$court_names = array('összes');
		
		$ret = array();
		try{
			$reservations = Reservation::get_report($from_date,$to_date,$courts,$username);
		}catch(Exception $e){
			$ret = array('success'=>false,'error'=>'invalid_date');
		}
		
		if(count($ret)==0){
			$date = date('Y-m-d');

			$ret['adminUser'] = $admin_username;
			$ret['date'] = $date;
			$ret['dateFrom'] = $from_date;
			$ret['dateTo'] = $to_date;
			$ret['courts'] = $court_names;
			$ret['reservationsCount'] = count($reservations);
			$ret['reservations'] = array();
			$income = 0;
			$users_tmp = array();
			foreach($reservations as $res){
				$users_tmp[$res->username] = 0;
				$ret['reservations'][] = $res->get_array();
				$income+=$res->price;
			}
			$ret['reservationsIncome'] = $income;
			$users = array_keys($users_tmp);
			sort($users);
			if(count($users)==0 && $username!="")
				$users = array($username);
			$ret['users'] = $users;

			$date_created = date('Y-m-d H:i:s');
			$date_created_fn = date('Ymd_His');

			if($_POST['xml']=="1"){
				require_once ('../api/classes/phpexcel/PHPExcel.php');

				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getProperties()->setCreator("Pályafoglalás KISPESTSE")
											->setLastModifiedBy("Pályafoglalás KISPESTSE")
											->setTitle("Pályafoglalás riport, ".$date_created);

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);

				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', 'Készítette:')
							->setCellValue('B1', $admin_username);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A2', 'Dátum:')
							->setCellValue('B2', $date_created);
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A3', 'Riport intervallum:')
							->setCellValue('B3', $from_date)
							->setCellValue('C3', $to_date);

				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A5', 'Felhasználónév')
							->setCellValue('B5', 'Foglalás dátuma')
							->setCellValue('C5', 'Pálya')
							->setCellValue('D5', 'Összeg');

				$objPHPExcel->getActiveSheet()->getStyle("A1:A3")->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle("A5:D5")->getFont()->setBold(true);

				$row=6;
				foreach($reservations as $res){
					$objPHPExcel->getActiveSheet()
								->setCellValue('A'.$row, $res->username)
								->setCellValue('B'.$row, $res->add_date)
								->setCellValue('C'.$row, $res->timeunit->court->name)
								->setCellValue('D'.$row, $res->price);
					$row++;
				}
				$objPHPExcel->getActiveSheet()
							->setCellValue('A'.$row, 'Összesen:')
							->setCellValue('C'.$row, $ret['reservationsCount'].' db')
							->setCellValue('D'.$row, $ret['reservationsIncome'].' Ft');
				$objPHPExcel->getActiveSheet()->getStyle("A".$row.":D".$row)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()
					->getStyle("C".$row.":D".$row)
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$rnd = STRING::Random(10);
				$bfn = 'report_'.$date_created_fn.'.xlsx';
				$fn = 'reports/'.$bfn;
				$objWriter->save($fn);

				header('Content-disposition: filename="'.$bfn.'"');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				echo file_get_contents($fn);
			}else{
				header("Access-Control-Allow-Origin: *");
				header('Content-Type: application/json');
				echo json_encode($ret);
			}
		}else{
			header("Access-Control-Allow-Origin: *");
			header('Content-Type: application/json');
			echo json_encode($ret);
		}
	}else{
		$courts = Court::get_all();
		$smarty->assign('courts',$courts);

		if ($_GET['did']) {
			$smarty->assign('default_user', $_GET['did']);
		}

		$from_date = date('Y-m-01');
		$to_date = date('Y-m-d');
		
		$smarty->assign('from_date',$from_date);
		$smarty->assign('to_date',$to_date);
		
		$smarty->display('admin-report.tpl');
	}
?>