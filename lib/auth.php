<?php
// Simple authentication helpers
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function app_root_url(){
  $s = $_SERVER['SCRIPT_NAME'] ?? '';
  $parts = ['/views/admin/','/incidents/','/registrations/','/admin/','/technicians/','/views/'];
  foreach($parts as $p){
    $pos = strpos($s,$p);
    if($pos !== false){ return substr($s,0,$pos); }
  }
  return rtrim(dirname($s),'/');
}

function require_admin(){ if(empty($_SESSION['admin_id'])){ header('Location: '.app_root_url().'/admin/login.php'); exit; } }
function require_tech(){ if(empty($_SESSION['tech_id'])){ header('Location: '.app_root_url().'/technicians/login.php'); exit; } }
function require_customer(){ if(empty($_SESSION['customer_id'])){ header('Location: '.app_root_url().'/registrations/login.php'); exit; } }
