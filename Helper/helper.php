<?php

/**
 * Returns full absolute url.
 */
function url($relativeUrl)
{
    return '/' . implode(DS, [trim(BASE_URI, '/'), ltrim($relativeUrl, '/')]);
}
