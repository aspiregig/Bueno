<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700"/>
<link href="{{ asset('assets/fonts/ionicons/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" >
<?php
$asset_includes = json_decode(file_get_contents(env('ASSET_PATH')), true);

$css_files = $asset_includes['css_includes'];


foreach ($css_files as $key => $file)
{
?>
<link rel="stylesheet" type="text/css" href="<?php echo url($file . '?v='.env('APP_VERSION')) ?>">
<?php
}
?>