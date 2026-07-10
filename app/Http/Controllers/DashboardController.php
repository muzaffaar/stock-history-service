<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dashboard\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {}

    public function index()

    {

        return view('dashboard.index', [

            'data' => $this->dashboard->data(),

        ]);

    }

}