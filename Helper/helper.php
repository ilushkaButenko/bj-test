<?php

/**
 * Returns full absolute url.
 */
function url($relativeUrl)
{
    return '/' . ltrim(implode('/', [trim(BASE_URI, '/'), ltrim($relativeUrl, '/')]), '/');
}
