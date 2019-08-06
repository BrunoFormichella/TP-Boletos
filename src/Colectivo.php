<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {
  protected $linea;
  protected $numero;
  protected $empresa;

  /**
   * Construye un objeto de tipo Colectivo
   *
   * @param int $numero
   *   Numero del colectivo
   * @param string $linea
   *   Linea del colectivo
   * @param string $empresa
   *   Empresa del colectivo
   */

  public function __construct($numero, $linea, $empresa) {
    $this->linea = $linea;
    $this->numero = $numero;
    $this->empresa = $empresa;
  }

  /**
   * Devuelve la linea del colectivo al que se hace referencia
   *
   * @return string
   *   Linea del colectivo
   */
  public function linea() {
    return $this->linea;
  }

  /**
   * Devuelve la empresa del colectivo al que se hace referencia
   *
   * @return string
   *   Empresa del colectivo
   */
  public function empresa() {
    return $this->empresa;
  }

  /**
   * Devuelve el numero del colectivo al que se hace referencia
   *
   * @return int
   *   Numero del colectivo
   */
  public function numero() {
    return $this->numero;
  }

  /**
   * Determina si es posible realizar un pago y en caso de ser posible lo
   * efectua y genera un boleto que es devuelto por la funcion. En caso de
   * que no se pueda efectuar el pago, devuelve false
   *
   * @param TarjetaInterface $tarjeta
   *   Tarjeta con la que se quiere efectuar el pago
   *
   * @return BoletoInterface|FALSE
   *   Boleto generado por el pago | FALSE
   */
  public function pagarCon(TarjetaInterface $tarjeta) {
    $tarjeta->establecerLinea($this->linea);
    if ($tarjeta->pagarPasaje()) {
      return new Boleto($this, $tarjeta, new Tiempo());
    }
    else {
      return false;
    }

  }
}