<?php

namespace TrabajoPagos;

class Saldo {

    /**
   * Verifica si una recarga puede ser valida y devuelve el valor de la recarga.
   *  En caso que no sea valida devuelve 0 como valor de la recarga
   *
   * @param float $monto
   *   Monto de la recarga
   *
   * @return float
   *   Monto de la recarga
   */

  public function recargaValida($monto) {
    switch ($monto) {
      case 10:    
        return $monto;
      case 20:   
         return $monto;
      case 30:    
        return $monto;
      case 50:    
        return $monto;
      case 100:    
        return $monto;
      case 1119.90:
        $monto += 180.10;
        return $monto;
      case 2114.11:
        $monto += 485.89;
        return $monto;
      default:
        $monto = 0;
        return $monto;
    }
  }
}
