<?php
	
	header('Content-Type: application/json');
	
	require_once("../class/Consultas.class.php");
	require_once("../conf/params.conf.php");
	
	$consulta = new Consulta( $_SERVER['REQUEST_METHOD'], $_REQUEST , $connection);

	if((int)method_exists($consulta, $consulta->petition ) > 0){
		$result = $consulta->get_request_by_method();
		call_user_func_array( array($consulta, $consulta->petition), $result );		
	}else{
		$consulta->report_status(406);
	}
	$data = $consulta->get_response();

	if(is_array($data)){
		echo json_encode( $data );
	}

?>