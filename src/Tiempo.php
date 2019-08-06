<?php

namespace TrabajoTarjeta;

class Tiempo implements TiempoInterface {

  /**
   * Devuelve el tiempo en el que se esta ejecutando el programa
   *
   * @return int
   *   Tiempo
   */
  public function time() {
    return time();
  }

}

class TiempoFalso implements TiempoInterface {

  protected $tiempo;

  /**
   * Contruye un objeto de tipo TiempoFalso
   *
   * @param int $inicio
   *   Tiempo desde el cual se quiere comenzar
   */

  public function __construct($inicio = 0) {
    $this->tiempo = $inicio;
  }

  /**
   * Avanza el tiempo una cantidad determinada de segundos
   *
   * @param int $segundos
   *   Segundos que se quiere avanzar el tiempo
   */
  public function avanzar($segundos) {
    $this->tiempo += $segundos;
  }

  /**
   * Devuelve el tiempo que se ha creado artificialmente
   *
   * @return int
   *   Tiempo falso en segundos
   */
  public function time() {
    return $this->tiempo;
  }
}