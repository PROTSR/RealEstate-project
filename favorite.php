<?php
session_start(); require 'includes/config.php';
if(!isset($_SESSION['user_id'])){header("Location: login.php");exit;}
$uid=$_SESSION['user_id'];$pid=(int)($_POST['property_id']??0);
$stmt=$conn->prepare("SELECT id FROM favorites WHERE user_id=? AND property_id=?");$stmt->bind_param("ii",$uid,$pid);$stmt->execute();
if($stmt->get_result()->num_rows){$d=$conn->prepare("DELETE FROM favorites WHERE user_id=? AND property_id=?");$d->bind_param("ii",$uid,$pid);$d->execute();}
else{$i=$conn->prepare("INSERT INTO favorites(user_id,property_id) VALUES(?,?)");$i->bind_param("ii",$uid,$pid);$i->execute();}
$back=$_SERVER['HTTP_REFERER']??'properties.php';header("Location: ".$back);exit;