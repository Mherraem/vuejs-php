<?php
require "conexion.php";
$user = new ActivadorDB();
// accion = mostrar, insertar, editar y eliminar
$accion = "mostrar";
$res = array("error"=>false);
if(isset($_GET['accion']))
  $accion = $_GET['accion'];

switch ($accion) {
  case 'mostrar':
    $u = $user->buscar("personajes","1");
    if($u):
      $res['personajes'] = $u;
      $res['mensaje'] = "Exito";
    else:
      $res['mensaje'] = "No se encontraron registros";
      $res['error'] = true;
    endif;
    break;

  case 'insertar':
    $nombre = $_POST['NOMBRE'];
    $raza = $_POST['RAZA'];
    $foto = $_FILES['FOTO']['name'];

    $target_dir = "img/";
    $target_file = $target_dir.basename($foto);
    move_uploaded_file($_FILES['FOTO']['tmp_name'], $target_file);

    $data = "'".$nombre."', '".$raza."', '".$foto."'";
    $u = $user->insertar("personajes",$data);

    if($u):
      $res['mensaje'] = "Inserccion satisfactoria";
    else:
      $res['mensaje'] = "No se encontraron registros";
      $res['error'] = true;
    endif;
    break;

  case 'editar':
    $id = $_POST['EID'];
    $nombre = $_POST['ENOMBRE'];
    $raza = $_POST['ERAZA'];
    $foto = "";

    if(isset($_FILES['EFOTO']['name'])):
      $foto = $_FILES['EFOTO']['name'];
      $target_dir = "img/";
      $target_file = $target_dir.basename($foto);
      move_uploaded_file($_FILES['EFOTO']['tmp_name'], $target_file);
      $foto = ", foto = '".$_FILES['EFOTO']['name']."'";
    endif;

    $data = "NOMBRE = '".$nombre."', RAZA = '".$raza."'".$foto;
    $u = $user->actualizar("personajes",$data, "id = ".$id);

    if($u):
      $res['mensaje'] = "Edicion satisfactoria";
    else:
      $res['mensaje'] = "Fallo al editar";
      $res['error'] = true;
    endif;
    break;
  case 'eliminar':
    $id = $_POST['did'];
    $u = $user->borrar("personajes", "id = ".$id);
  
    if($u):
      $res['mensaje'] = "Eliminacion satisfactoria";
    else:
      $res['mensaje'] = "Fallo al eliminar";
      $res['error'] = true;
    endif;
    break;
  default:

    break;
}

// Resultado en formato json
echo json_encode($res);
die();
?>
