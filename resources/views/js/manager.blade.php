<?php
$publicUrl = url('abtl_assets/js/');
require(public_path('abtl_assets/js/jquery.min.js'));
?>
var abtl = jQuery.noConflict(true);
<?php
require(public_path('abtl_assets/js/master_functions.js'));
?>

websiteID = {{ $website->id }};
abtlBackUrl = '{{ url(route('tests.manager.exit', ['id' => $website->id])) }}';
abtlUrl = '{{ url() }}';

//entry point for token
if(window.location.hash.substring(0, 7) === '#token=')
{
    if(window.FileReader)
    {
        abtl.setLocal('token', window.location.hash.replace('#token=', ''));
        window.location = window.location.href.replace(window.location.hash, '');
    }
    else
    {
        alert('Your browser is too old to edit tests.');
        window.location = abtlBackUrl;
    }
}
//in all other cases
else
{
    //read manager token if its set
    token = abtl.getLocal('token');
    //request manager
    if (token !== null)
    {
       abtl.loadJS('{{ $publicUrl }}/manager.js');
    }
    else
    {
        <?php
        echo 'abtlData = ' . json_encode($tests, JSON_UNESCAPED_SLASHES) . ';';

        //abtl meat
        require(public_path('abtl_assets/js/visitor.js'));
        ?>
    }
}