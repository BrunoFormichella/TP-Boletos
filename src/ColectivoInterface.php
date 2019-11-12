<?php

namespace TrabajoPagos;

interface ColectivoInterface {

  /**
   * Devuelve el nombre de la linea. Ejemplo '142 Negro'
   *
   * @return string
   */
  public function linea();

  /**
   * Devuelve el nombre de la empresa. Ejemplo 'Semtur'
   *
   * @return string
   */
  public function empresa();

  /**
   * Devuelve el numero de unidad. Ejemplo: 12
   *
   * @return int
   */
  public function numero();

  /**
   * Paga un viaje en el colectivo con una tarjeta en particular.
   *
   * @param MedioDePagoInterface $mediodepago
   *
   * @return BoletoInterface|FALSE
   *  El boleto generado por el pago del viaje. O FALSE si no hay saldo
   *  suficiente en la tarjeta.
   */
  public function pagarCon(MedioDePagoInterface $mediodepago);

}
