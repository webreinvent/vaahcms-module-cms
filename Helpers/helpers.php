<?php

function page_statuses()
{
    $list = [
        ["label" => 'Draft', "code" => 'draft'],
        ["label" => 'Pending Review', "code" => str_slug('Pending Review')],
    ];

    return $list;
}

//--------------------------------------------------
function page_visibilities()
{
    $list = [
        ["label" => 'Public', "code" => 'public'],
        ["label" => 'Private', "code" => str_slug('private')],
    ];

    return $list;
}
//--------------------------------------------------