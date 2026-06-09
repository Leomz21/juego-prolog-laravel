<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class PrologService
{
    public function perfil(string $personaje): string
    {
        return $this->ejecutar(
            'perfil_personaje(' . $this->atomo($personaje) . ', Mensaje)'
        );
    }

    public function combateIndividual(string $personaje, string $arma, string $enemigo, int $ataques): string
    {
        return $this->ejecutar(
            'combate_individual(' .
            $this->atomo($personaje) . ', ' .
            $this->atomo($arma) . ', ' .
            $this->atomo($enemigo) . ', ' .
            $this->numero($ataques) . ', Mensaje)'
        );
    }

    public function combateGrupal(array $grupo, string $enemigo, int $ataques): string
    {
        return $this->ejecutar(
            'combate_grupal(' .
            $this->lista($grupo) . ', ' .
            $this->atomo($enemigo) . ', ' .
            $this->numero($ataques) . ', Mensaje)'
        );
    }

    public function misionIndividual(string $personaje, string $mision): string
    {
        return $this->ejecutar(
            'mision_individual(' .
            $this->atomo($personaje) . ', ' .
            $this->atomo($mision) . ', Mensaje)'
        );
    }

    public function misionGrupal(array $grupo, string $mision): string
    {
        return $this->ejecutar(
            'mision_grupal(' .
            $this->lista($grupo) . ', ' .
            $this->atomo($mision) . ', Mensaje)'
        );
    }

    private function ejecutar(string $consulta): string
    {
        $binario = env('PROLOG_BIN', 'swipl');
        $archivo = base_path(env('PROLOG_FILE', 'prolog/base_conocimiento.pl'));

        if (! file_exists($archivo)) {
            return 'No se encontró el archivo del juego.';
        }

        $goal = "($consulta -> writeln(Mensaje) ; writeln('La acción no produjo resultado.')), halt.";

        $proceso = new Process([
            $binario,
            '-q',
            '-s',
            $archivo,
            '-g',
            $goal,
        ]);

        $proceso->setTimeout(10);
        $proceso->run();

        if (! $proceso->isSuccessful()) {
            $error = trim($proceso->getErrorOutput());

            return $error !== ''
                ? 'Error al ejecutar la acción: ' . $error
                : 'Error al ejecutar la acción.';
        }

        $salida = trim($proceso->getOutput());

        return $salida !== '' ? $salida : 'La acción no generó resultado.';
    }

    private function atomo(string $valor): string
    {
        $valor = trim($valor);
        $valor = str_replace('\\', '\\\\', $valor);
        $valor = str_replace("'", "\\'", $valor);

        return "'" . $valor . "'";
    }

    private function lista(array $valores): string
    {
        if (empty($valores)) {
            return '[]';
        }

        $atomos = array_map(fn ($valor) => $this->atomo($valor), $valores);

        return '[' . implode(',', $atomos) . ']';
    }

    private function numero(int $valor): int
    {
        if ($valor < 1) {
            return 1;
        }

        if ($valor > 10) {
            return 10;
        }

        return $valor;
    }
}