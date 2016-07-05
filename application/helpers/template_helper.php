<?php
defined('BASEPATH') OR exit('No direct script access allowed');
defined('PUBLICPATH') OR exit('No direct script access allowed');

function asset($asset_path)
{
    return PUBLICPATH . 'assets/' . $asset_path;
}

function asset_admin($asset_path)
{
    return PUBLICPATH . 'assets/admin/' . $asset_path;
}

function asset_client($asset_path)
{
    return PUBLICPATH . 'assets/client/' . $asset_path;
}