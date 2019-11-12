<?php

namespace TrabajoPagos;

class FranquiciaCompleta extends MedioDePago {

  /**
   * Construye una MedioDePago de tipo Franquicia completa
   *
   * @param TiempoInterface $tiempo
   *   Tiempo que utiliza la tarjeta (utilizar tiempo falso solo en caso de testing)
   */

  public function __construct(TiempoInterface $tiempo) {
    parent::__construct($tiempo);
    $this->precio = 0;
  }

  /**
   * Sobreescribe el metodo pagarPasaje de
   * la clase padre para que siempre se pueda
   * pagar un pasaje
   *
   * @return true
   *   Indica que el pasaje se ha podido pagar correctamente
   *
   *  public function pagarPasaje() {
   *    return true;
   *  }
   *
   * No complía con L, sobreescribia el metodo pagarPasaje, al borrar el metodo
   * usa la definicion del padre, usando como precio del pasaje a 0, lo cual cumple con la franquicia completa.
   */
}