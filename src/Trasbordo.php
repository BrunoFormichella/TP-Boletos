<?php

namespace TrabajoPagos;

class Trasbordo {

    protected $pagadoReciente = false;


    /**
    * Verifica si el pasaje a pagar es un trasbordo. Si es un trasbordo, cambia el precio del pasaje.
    */

    public function esTrasbordo($linea,$lineaAnterior, $minuto, $horaEnMinutos, $hora) {

        if ($lineaAnterior == $linea) {
            $this->pagadoReciente = true;
            return false;
        }
    
        $limitacionHora = 60;
    
        if ($this->verificarHora($hora)) {
            $limitacionHora = 120;
        }
    
        if ($this->verificarLimite($limitacionHora,$minuto,$horaEnMinutos)) {
            $this->pagadoReciente = true;
            return false;
        }

        if($this->pagadoReciente){
            return true;
        }
    }


    /**
    *
    * @return bool
    *   Indica si el pasaje se paga dentro del rango 22 a 6 am
    */

    public function verificarHora($hora) {
        return $hora >= 22 || $hora <= 6;
    }


    /**
    * Evalua si el pasaje cumple con las condiciones para ser trasbordo
    *
    * @param int $limitacionHora
    *   Cantidad de tiempo que dura el trasbordo
    *
    * @return bool
    *   Indica si el pasaje es trasbordo
    */

    private function verificarLimite($limitacionHora,$minuto,$horaEnMinutos) {
        $limitacion = ($horaEnMinutos - $minuto) > $limitacionHora;
        return $limitacion;
    }

    /**
    * Verifica si la linea del viaje anterior es la misma que la del viaje que se esta pagando ahora
    *
    * @return bool
    *   Indica si las lineas son diferentes
    */

}
