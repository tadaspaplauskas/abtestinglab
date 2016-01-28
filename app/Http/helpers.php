<?php

function protocolRelativeUrl($path = '/')
{
    return str_replace(['http://', 'https://'], '//', url($path));
}