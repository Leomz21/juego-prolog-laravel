<?php

namespace App\Http\Controllers;

use App\Services\PrologService;
use Illuminate\Http\Request;

class JuegoController extends Controller
{
    public function index(Request $request)
    {
        $modo = $request->query('modo', 'inicio');

        return view('juego.index', $this->datosVista($modo));
    }

    public function jugar(Request $request, PrologService $prolog)
    {
        $datos = $request->validate([
            'tipo' => ['required', 'string'],
            'personaje' => ['nullable', 'string'],
            'arma' => ['nullable', 'string'],
            'enemigo' => ['nullable', 'string'],
            'mision' => ['nullable', 'string'],
            'ataques' => ['nullable', 'integer', 'min:1', 'max:10'],
            'grupo' => ['nullable', 'array'],
            'grupo.*' => ['string'],
        ]);

        $tipo = $datos['tipo'];
        $personaje = $datos['personaje'] ?? '';
        $arma = $datos['arma'] ?? '';
        $enemigo = $datos['enemigo'] ?? '';
        $mision = $datos['mision'] ?? '';
        $ataques = (int) ($datos['ataques'] ?? 1);
        $grupo = $datos['grupo'] ?? [];

        $resultado = match ($tipo) {
            'perfil' => $prolog->perfil($personaje),
            'combate_individual' => $prolog->combateIndividual($personaje, $arma, $enemigo, $ataques),
            'combate_grupal' => $prolog->combateGrupal($grupo, $enemigo, $ataques),
            'mision_individual' => $prolog->misionIndividual($personaje, $mision),
            'mision_grupal' => $prolog->misionGrupal($grupo, $mision),
            default => 'Accion no disponible.',
        };

        $modo = match ($tipo) {
            'perfil' => 'estado',
            'combate_individual' => 'solitario',
            'combate_grupal' => 'grupo',
            'mision_individual' => 'mision_individual',
            'mision_grupal' => 'mision_grupal',
            default => 'inicio',
        };

        return view('juego.index', $this->datosVista($modo, $resultado));
    }

    private function datosVista(string $modo = 'inicio', ?string $resultado = null): array
    {
        return [
            'modo' => $modo,
            'resultado' => $resultado,

            'personajes' => [
                'Elara',
                'Kael',
                'Rin',
                'Luna',
                'Darius',
                'Milo',
            ],

            'armas' => [
                'espada' => 'Espada',
                'arco' => 'Arco',
                'varita' => 'Varita',
                'daga' => 'Daga',
                'hacha' => 'Hacha',
                'lanza' => 'Lanza',
            ],

            'enemigos' => [
                'bruja' => 'Bruja',
                'zombi' => 'Zombi',
                'vampiro' => 'Vampiro',
                'demonio' => 'Demonio',
                'dragon' => 'Dragon',
            ],

            'misiones' => [
                'm1' => 'Bosque de Sombras',
                'm2' => 'Cueva del Dragon',
                'm3' => 'Templo Antiguo',
                'm4' => 'Ruinas del Valle',
                'm5' => 'Fortaleza del Norte',
            ],
        ];
    }
}