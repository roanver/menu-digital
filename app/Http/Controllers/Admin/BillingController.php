<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;

class BillingController extends AdminController
{
    public function show(): View
    {
        $restaurant = auth()->user()->restaurant;

        return view('admin.billing.show', compact('restaurant'));
    }
}
