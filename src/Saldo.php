<?php

namespace TrabajoTarjeta;

class Saldo {

    protected $credito;

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

    public static function validarRecarga($monto) {
        switch ($monto) {
          case 10:
          case 20:
          case 30:
          case 50:
          case 100:
            break;
          case 510.15:
            $monto += 81.93;
            break;
          case 962.59:
            $monto += 221.58;
            break;
          default:
            $monto = 0;
        }
        return $monto;
      }
}
