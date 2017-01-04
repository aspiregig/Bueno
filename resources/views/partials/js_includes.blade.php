<!-- JS Customization -->
<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.8/clipboard.min.js"></script>
<?php
$asset_includes = json_decode(file_get_contents(env('ASSET_PATH')), true);

$js_files = $asset_includes['js_includes'];

foreach ($js_files as $key => $file)
{
?>

<script src="<?php echo url($file . '?v='.env('APP_VERSION')) ?>"></script>
<?php
}
?>


