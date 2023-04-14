<?php
namespace App\Concerns;

trait Access
{

    function hasRoleAdmin()
    {
        return in_array(1, auth()->user()->role()->get()->pluck('id')->toArray());
    }
}
