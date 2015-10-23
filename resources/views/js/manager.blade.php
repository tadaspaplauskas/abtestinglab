<?php
require(public_path('abtl_assets/js/jquery.min.js'));
require(public_path('abtl_assets/js/master.js'));
?>

websiteID = {{ $website->id }};

//entry point for token
if(window.location.hash.substring(0, 7) ==='#token=')
{
    setLocal('token', window.location.hash.replace('#token=', ''));
    window.location = window.location.href.replace(window.location.hash, '');
}
//in all other cases
else
{
    $(document).ready(function() {
        //read manager token if its set
        token = getLocal('token');
        //request manager
        if (token !== null)
        {
            loadJS('http://abtestinglab.dev/abtl_assets/js/manager.js');

        }
        else
        {
            <?php
            echo 'abtlData = ' . json_encode($tests, JSON_UNESCAPED_SLASHES) . ';';

            //abtl meat
            require(public_path('abtl_assets/js/visitor.js'));
            ?>
        }
    });
}