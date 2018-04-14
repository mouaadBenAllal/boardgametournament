<?php

/**
 * Function to determine to set active or not.
 * @param $path,           The path where the url navigates to.
 * @return string,         Active whenever active, empty string otherwise.
 */
function setActive($path)
{
    // Return the active state:
    return Request::is($path) ? 'active' :  '';
}