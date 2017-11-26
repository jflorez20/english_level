<?php
require_once("rest/API.class.php");

class Consulta extends API{

	public function __construct($request_method, $request, $param_conection){
		parent::__construct($request_method, $request, $param_conection);
	}

	public function usuario(){

		switch ( $this->get_request_method() ) {

			case 'GET':
				$where = '';					
				if( $this->detail != null ){
					$where.= ' WHERE id='.$this->detail;
				}
				
				$sql = "SELECT * FROM usuario".$where;
				if( $result = $this->myConnect->query( $sql ) ){
					$this->response['data'] = $result;
					$this->report_status(200) ; 
				}else{
					$this->report_status(0);
				}

			break;

			case 'POST':

				$p =  ( isset($_POST) ) ? (object)$_POST : null;
				$g =  ( isset($_GET) )  ? (object)$_GET  : null;
				
				if( !isset($g->detail) ){
					$sql = "INSERT INTO usuario (nombre, apellidos, mail, contrasena) values ( '$p->nombre', '$p->apellidos', '$p->mail', '$p->contrasena_1')";
				
					if( $this->myConnect->query( $sql ) ){
						$this->response['data'] = $this->myConnect->connection->insert_id;
						$this->report_status(200); 
					}else{
						$this->report_status(0);
					}
				}
			break;

			default:
				$this->report_status(0);
			break;
		}		
	}

	public function login()
	{
		$p =  ( isset($_POST) ) ? (object)$_POST : null;

		$sql = "SELECT * FROM usuario WHERE mail='".$p->mail."' AND contrasena='".$p->contrasena."'";
		if( $result = $this->myConnect->query( $sql ) ){
			$this->response['data'] = $result;
			$this->report_status(200) ; 
		}else{
			$this->report_status(0);
		}
	}

	public function selet_juego()
	{
		$p =  ( isset($_POST) ) ? (object)$_POST : null;

		$sql = "SELECT * FROM juego WHERE id_usuario=".$p->id_u." AND id_dificultad=".$p->id_d;
		if( $result = $this->myConnect->query( $sql ) ){
			$this->response['data'] = $result;
			$this->report_status(200) ; 
		}else{
			$sql = "INSERT INTO juego (id_usuario, aciertos, errores, id_dificultad) values ( $p->id_u, 0, 0, $p->id_d)";
			if( $this->myConnect->query( $sql ) ){
				$this->response['data'] = $this->myConnect->connection->insert_id;

				$sql = "SELECT * FROM juego WHERE id_usuario=".$p->id_u." AND id_dificultad=".$p->id_d;
				if( $result = $this->myConnect->query( $sql ) ){
					$this->response['data'] = $result;
					$this->report_status(200) ; 
				}
			}
		}
	}

	public function pregunta()
	{
		$sql = "SELECT * FROM pregunta WHERE id_dificultad=".$this->detail;
		if( $result = $this->myConnect->query( $sql ) ){
			$max = count($result)-1;
			$this->response['data'] = $result[rand(0,$max)];
			$this->report_status(200) ; 
		}else{
			$this->report_status(0);
		}
	}

	public function sumar()
	{
		$p =  ( isset($_POST) ) ? (object)$_POST : null;

		$sql = "UPDATE juego SET aciertos=aciertos+".$p->a.", errores=errores+".$p->e." WHERE id_juego=".$this->detail;
		if( $result = $this->myConnect->query( $sql ) ){
			$this->response['data'] = $result;
			$this->report_status(200) ; 
		}
	}

	public function progreso()
	{
		$sql = "SELECT * FROM juego WHERE id_usuario=".$this->detail;
		if( $result = $this->myConnect->query( $sql ) ){
			$this->response['data'] = $result;
			$this->report_status(200) ; 
		}
	}

}

?>