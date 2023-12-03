<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use Illuminate\Http\Request;

class ProvinciaController extends Controller {

    public function getAllProvincias() {

        return Provincia::all();
    }
}
