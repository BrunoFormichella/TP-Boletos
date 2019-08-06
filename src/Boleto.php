<?php

namespace TrabajoTarjeta;

class Boleto implements BoletoInterface {

  protected $colectivo;
  protected $tarjeta;
  protected $fecha;
  protected $tipo;
  protected $linea;
  protected $total;
  protected $saldo;
  protected $id;
  protected $descripcion;
  protected $valor;

  /**
   * Construye un objeto de tipo Boleto, guarda los datos que necesita el boleto y en caso de que se haya utilizado un trasbordo
   * se encarga de reestablecer el precio de la tarjeta.
   *
   * @param ColectivoInterface $colectivo
   *   Colectivo en el que se realizo el viaje
   * @param TarjetaInterface $tarjeta
   *   Tarjeta con la que se pago el pasaje
   * @param TiempoInterface $tiempo
   *   Tipo de tiempo utilizado (utilizar solo cuando se realice testing)
   */

  public function __construct($colectivo, $tarjeta, TiempoInterface $tiempo) {
    $this->colectivo = $colectivo;
    $this->tarjeta = $tarjeta;

    $this->establecerTipo($tarjeta);

    $this->linea = $colectivo->linea();

    $this->total = $tarjeta->obtenerPrecio() + $tarjeta->obtenerPlusAbonados() * $tarjeta->obtenerPrecio();

    $this->saldo = $tarjeta->obtenerSaldo();

    $this->id = $tarjeta->obtenerID();

    $this->fecha = date("d/m/Y H:i:s", $tiempo->time());

    $this->descripcion = $this->establecerDescripcion($tarjeta->obtenerCantPlus(), $tarjeta->obtenerPlusAbonados(), $tarjeta);

    $tarjeta->reestablecerPrecio();
  }

  /**
   * Devuelve la descripcion del boleto indicando que precio se esta pagando por el pasaje
   * y a que se debe ese precio.
   *
   * @param int $cantPlus
   *   Cantidad de plus que se estan utilzando
   * @param int $plusAbonados
   *   Cantidad de pasajes plus que se estan pagando
   * @param TarjetaInterface $tarjeta
   *   Objeto tarjeta con la que se pago el pasaje
   *
   * @return string
   *   Descripcion del pasaje
   *
   */

  private function establecerDescripcion($cantPlus, $plusAbonados, $tarjeta) {

    $this->valor = 0;

    switch ($cantPlus) {
      case 1:
        return "$0 Viaje Plus";
      case 2:
        return "$0 Ultimo Plus";
    }

    $abona = "";

    switch ($plusAbonados) {
      case 1:
        $abona = " Abona un Viaje Plus";
        break;
      case 2:
        $abona = " Abona dos Viajes Plus";
        break;
    }

    $tarjeta->reestablecerPlus();

    $this->valor = $tarjeta->obtenerPrecio() + 14.8 * $plusAbonados;

    return "$" . $this->valor . $abona;
  }

  /**
   * Indica el tipo de tarjeta que se esta utilizando
   *
   * @param TarjetaInterface $tarjeta
   *   Objeto tarjeta con el que se pago el pasaje
   *
   * @return string
   *   Tipo de tarjeta
   */

  private function establecerTipo($tarjeta) {
    switch ($tarjeta->obtenerPrecio()) {
      case 14.80:
        $this->tipo = "Normal";
        break;
      case 7.4:
        $this->tipo = "Medio Boleto";
        break;
      case 0:
        $this->tipo = "Franquicia completa";
        break;
      default:
        $this->tipo = $this->tipoTrasbordo($tarjeta->obtenerPrecio());
        $tarjeta->reestablecerPrecio();
        break;
    }
  }

  /**
   * Verifica que tipo de trasbordo se esta pagando
   *
   * @param int $precio
   *   Precio del pasaje
   *
   * @return string
   *   Tipo de trasbordo
   */

  public function tipoTrasbordo($precio) {
    switch ($precio) {
      case (14.8 / 3):
        return "Trasbordo";
      case (7.4 / 3):
        return "Medio Trasbordo";
    }
    return "";
  }

  /**
   * Devuelve el precio del boleto.
   *
   * @return int
   *   Precio del boleto
   */
  public function obtenerValor() {
    return $this->valor;
  }

  /**
   * Devuelve un objeto que respresenta el colectivo donde se viajó.
   *
   * @return ColectivoInterface
   *   Colectivo en el que se realizo el viaje
   */
  public function obtenerColectivo() {
    return $this->colectivo;
  }

  /**
   * Devuelve la fecha en la cual se pago el pasaje
   *
   * @return string
   *   Fecha en la que se abono el pasaje
   */
  public function obtenerFecha() {
    return $this->fecha;
  }

  /**
   * Devuelve el tipo del pasaje (Medio Boleto, Franquicia completa, normal)
   *
   * @return string
   *   Tipo de boleto
   */
  public function obtenerTipo() {
    return $this->tipo;
  }

  /**
   * Devuelve la linea del colectivo en la que viajo el pasajero.
   *
   * @return string
   *   Linea del colectivo
   */
  public function obtenerLinea() {
    return $this->linea;
  }

  /**
   * Devuelve la cantidad de dinero total abonado con el pasaje
   *
   * @return int
   *   Precio abonado con el pasaje
   */
  public function obtenerTotalAbonado() {
    return $this->total;
  }

  /**
   * Devuelve el saldo restante de la tarjeta con la que se pago el pasaje
   *
   * @return int
   *   Saldo restante de la tarjeta
   */
  public function obtenerSaldo() {
    return $this->saldo;
  }

  /**
   * Devuelve el id de la tarjeta con la que se abono el pasaje
   *
   * @return int
   *   Id de la tarjeta
   */
  public function obtenerID() {
    return $this->id;
  }

  /**
   * Devuelve una frase que tiene el valor del pasaje y una pequeña descripcion que indica que pasajes abona con este boleto
   *
   * @return string
   *   Descripcion del boleto
   */
  public function obtenerDescripcion() {
    return $this->descripcion;
  }

}
