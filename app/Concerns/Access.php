<?php
namespace App\Concerns;

trait Access
{

    function hasRoleAdmin()
    {
        return in_array(1, auth()->user()->roles()->get()->pluck('id')->toArray());
    }
}