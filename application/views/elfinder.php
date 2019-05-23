<!DOCTYPE html>
<html lang="en">
<head>
    <script>var connector_url = '<?php echo $connector ?>';</script>
    <meta charset="UTF-8">
    <title>CKEDitor 4 with elFinder</title>
    <link href="<?php echo base_url('biq-assets/vendor/jquery-ui/jquery-ui.min.css') ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('biq-assets/vendor/elFinder/css/elfinder.full.css')?>" rel="stylesheet"/>
</head>
<body>

<h3>CKEDITOR using elFinder with S3 adapter</h3>
<div class="loading">Loading...</div>
<div id="s3-adapter"></div>

<script src="<?php echo base_url('biq-assets/vendor/jquery.js') ?>"></script>
<script src="<?php echo base_url('biq-assets/vendor/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo base_url('biq-assets/vendor/ckeditor/ckeditor.js') ?>"></script>
<script src="<?php echo base_url('biq-assets/vendor/elFinder/js/elfinder.full.js') ?>"></script>

<script src="<?php echo base_url('biq-assets/app.js') ?>"></script>

</body>
</html>
