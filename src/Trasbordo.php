<?php

namespace TrabajoPagos;

class Trasbordo implements TrasbordoInterface {
    
    protected $ultimaLinea;
    

    /**
    * Verifica si el pasaje a pagar es un trasbordo. Si es un trasbordo, cambia el precio del pasaje.
    */
    
    public function esTrasbordo($linea, $minuto, $horaEnMinutos, $hora) {

        if ($this->fueTrasbordo || $this->plusAbonados != 0) {
            $this->fueTrasbordo = FALSE;
            return;
        }

        if ($this->verificarLinea($linea)) {
            return;
        }
        $ultimaLinea = $linea;

        $limitacionHora = 60;

        if ($this->verificarHora($hora)) {
            $limitacionHora = 120;
        }
        
        if ($this->verificarLimite($limitacionHora,$minuto,$horaEnMinutos)) {
            $this->precio = 0;
            $this->fueTrasbordo = TRUE;
        }

    }


    /**
    *
    * @return bool
    *   Indica si el pasaje se paga dentro del rango 22 a 6 am
    */
    
    public function verificarHora($hora) {
        return $hora >= 22 || $hora() <= 6;
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
  
    private function verificarLimite($limitacionHora,$minutos,$horaEnMinutos) {
        $limitacion = ($horaEnMinutos - $minutos) < $limitacionHora;
        return $limitacion;
    }

    /**
    * Verifica si la linea del viaje anterior es la misma que la del viaje que se esta pagando ahora
    *
    * @return bool
    *   Indica si las lineas son diferentes
    */
  
    private function verificarLinea($linea) {
        $mismaLinea = $this->ultimaLinea == $linea;
        return isset($linea) && isset($this->ultimaLinea) && $mismaLinea;
    }