<?php

namespace App\Services;

use App\Models\Numera;

class NumeraService
{
    public function getCodigo($codigo)
    {
        return Numera::where('codigo', $codigo)->get()->first();
    }

    public function getCorrelativo($codigo)
    {
   
        $numera = Numera::where('codigo', $codigo)->get()->first();
        $sigla = $numera->sigla;
        $ultimo_numero = $numera->ultimo_numero;
        $ultimo_numero = $ultimo_numero + 1;
        return $sigla . "-" .  str_pad($ultimo_numero, 4, "0", STR_PAD_LEFT);
    }

    public function updateCorrelativo($codigo)
    {
        $numera = Numera::where('codigo', $codigo)->get()->first();
        $numera->ultimo_numero=($numera->ultimo_numero+1);
        $numera->save();
        return $numera;
    }
}
