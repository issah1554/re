<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if ($action == 'login') {
	$login = $crud->login();
	if ($login)
		echo $login;
}
if ($action == 'logout') {
	$logout = $crud->logout();
	if ($logout)
		echo $logout;
}
if ($action == 'logout2') {
	$logout = $crud->logout2();
	if ($logout)
		echo $logout;
}
if ($action == 'signup') {
	$save = $crud->signup();
	if ($save)
		echo $save;
}
if ($action == 'update_account') {
	$save = $crud->update_account();
	if ($save)
		echo $save;
}
if ($action == "save_settings") {
	$save = $crud->save_settings();
	if ($save)
		echo $save;
}
if ($action == "save_category") {
	$save = $crud->save_category();
	if ($save)
		echo $save;
}


if ($action == "edit_category") {
	$save = $crud->edit_category();
	if ($save)
		echo $save;
}

if ($action == "delete_category") {
	$delete = $crud->delete_category();
	if ($delete)
		echo $delete;
}
if ($action == "save_house") {
	$save = $crud->save_house();
	if ($save)
		echo $save;
}
if ($action == "delete_house") {
	$save = $crud->delete_house();
	if ($save)
		echo $save;
}

if ($action == "save_tenant") {
	$save = $crud->save_tenant();
	if ($save)
		echo $save;
}
if ($action == "delete_tenant") {
	$save = $crud->delete_tenant();
	if ($save)
		echo $save;
}
if ($action == "get_tdetails") {
	$get = $crud->get_tdetails();
	if ($get)
		echo $get;
}

if ($action == "save_payment") {
	$save = $crud->save_payment();
	if ($save)
		echo $save;
}
if ($action == "delete_payment") {
	$save = $crud->delete_payment();
	if ($save)
		echo $save;
}

// ADMIN USER MANAGEMENT
//--------------------------------------------------

if ($action == "create_user") {
	$create = $crud->create_user();
	if ($create)
		echo $create;
}
if ($action == 'get_user_id') {
	$get = $crud->get_user_id();
	if ($get)
		echo $get;
}
if ($action == 'update_user') {
	$save = $crud->update_user();
	if ($save)
		echo $save;
}

if ($action == 'delete_user') {
	$save = $crud->delete_user();
	if ($save)
		echo $save;
}


ob_end_flush();
