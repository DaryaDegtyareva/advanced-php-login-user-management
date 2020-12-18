<?php defined('FCPATH') OR exit('No direct script access allowed');
$assets_location = base_url().'/SiteGuard/'; ?><!doctype html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta http-equiv="Content-Language" content="en" />
<meta name="msapplication-TileColor" content="#2d89ef">
<meta name="theme-color" content="#4188c9">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<link rel="icon" type="image/png" href="<?php echo $assets_location; ?>images/shield.png"/>
<title><?php echo $title; ?> | <?php if(isset($siteGuard->settings['site_name']) && $siteGuard->settings['site_name'] != '' ) { echo $siteGuard->settings['site_name']; } else { ?> SiteGuard ® <?php } ?></title>
<link rel="stylesheet" href="<?php echo $assets_location; ?>fonts/fontawesome/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700&amp;subset=latin-ext">

<!-- Dashboard Core -->
<link href="<?php echo $assets_location; ?>css/dashboard.css" rel="stylesheet" />
<link href="<?php echo $assets_location; ?>plugins/sweetalert/sweetalert.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo $assets_location; ?>plugins/cropper/cropper.css">
<link rel="stylesheet" href="<?php echo $assets_location; ?>plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo $assets_location; ?>plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<link href="<?php echo $assets_location; ?>plugins/charts-c3/plugin.css" rel="stylesheet" />
<link href="<?php echo $assets_location; ?>plugins/datatables/datatables.min.css" rel="stylesheet" />
<link href="<?php echo $assets_location; ?>plugins/summernote/summernote-bs4.css" rel="stylesheet" />

<script src="<?php echo $assets_location; ?>js/vendors/jquery-3.2.1.min.js"></script>
<script src="<?php echo $assets_location; ?>js/vendors/bootstrap.bundle.min.js"></script>
<script src="<?php echo $assets_location; ?>js/pwstrength.js"></script>
<script src="<?php echo $assets_location; ?>plugins/select2/select2.min.js"></script>
<script src="<?php echo $assets_location; ?>plugins/datatables/datatables.min.js"></script>


</head>