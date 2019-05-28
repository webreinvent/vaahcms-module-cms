<?php

function page_statuses()
{
    $list = [
        ["name" => 'Draft', "slug" => 'draft'],
        ["name" => 'Pending Review', "slug" => str_slug('Pending Review')],
    ];

    return $list;
}

//--------------------------------------------------
function page_visibilities()
{
    $list = [
        ["name" => 'Public', "slug" => 'public'],
        ["name" => 'Private', "slug" => str_slug('private')],
    ];

    return $list;
}
//--------------------------------------------------