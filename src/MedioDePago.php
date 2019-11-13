<?php

namespace TrabajoPagos;

class MedioDePago implements MedioDePagoInterface {
  protected $saldo;
  protected $precio;
  protected $cantPlus;
  protected $id;
  protected $plusAbonados;
  protected $tiempo;
  protected $precioOriginal;
  protected $contarTrasbordos;
  protected $linea;
  protected $lineaAnterior;
  protected $pagadoReciente;
  protected $actualTrasbordo;
  protected $trasbordo;
  protected $minutos;

  /**
   * Construye un objeto de tipo tarjeta e inicializa sus variables
   *
   * @param TiempoInterface $tiempo
   *   Tipo de tiempo que va a utilizar la tarjeta (utilizar tiempo falso solo en caso de testing)
   */

   public function __construct(TiempoInterface $tiempo, $trasbordo, $recarga) {
    static $ID = 0;
    $ID++;
    $this->saldo = 0;
    $this->recarga= $recarga;
    $this->precio = 32.50;
    $this->cantPlus = 0;
    $this->id = $ID;
    $this->plusAbonados = 0;
    $this->tiempo = $tiempo;
    $this->minutos = -10000;
    $this->contarTrasbordos = TRUE;
    $this->precioOriginal = $this->precio;
    $this->trasbordo=$trasbordo;
    $pagadoReciente = FALSE;
  }

  /**
   * Efectua la recarga de dinero en caso de que sea posible y establece la cantidad de pasjaes
   * plus que se pueden abonar con esa recarga
   *
   *  Mover la logica de recarga a la clase saldo
   *
   * @param int $monto
   *   Cantidad de dinero que se quiere recargar
   *
   * @return bool
   *   Indica si se pudo recargar la tarjeta
   */

   public function recargar($monto) {
    $monto = $this->recarga->recargaValida($monto);
    if ($monto == 0) {
      return false;
    }
    $this->saldo += $monto;
    return TRUE;
  }

  /**
   * Devuelve el saldo que le queda a la tarjeta.
   *
   * @return float
   *   Saldo de la tarjeta
   */

   public function obtenerSaldo() {
    return $this->saldo;
  }

  /**
   * Iguala el atributo linea a la linea del colectivo
   *
   * @param string $linea
   *   Linea del colectivo en la que se utiliza la tarjeta
   */

   public function establecerLinea($linea) {
    $this->linea = $linea;
  }

  /**
   * Devuelve el precio del pasaje que se va a abonar
   *
   * @return int
   *   Precio del pasaje
   */

   public function obtenerPrecio() {
    return $this->precio;
  }

  /**
   * Devuelve la cantidad de plus que se van a abonar en el proximo pasaje
   *
   * @return int
   *   Cantidad de pasajes plus
   */

   public function obtenerCantPlus() {
    return $this->cantPlus;
  }

  /**
   * Devuelve el identificador de la tarjeta
   *
   * @return int
   *   ID de la tarjeta
   */

   public function obtenerID() {
    return $this->id;
  }

  /**
   * Indica que la cantidad de pasajes plus que se van a abonar es 0
   */

   public function reestablecerPlus() {
    $this->plusAbonados = 0;
  }

  /**
   * Devuelve la cantidad de plus que se deben pagar en el proximo pasaje
   *
   * @return int
   *   Cantidad de plus abonados
   */

   public function obtenerPlusAbonados() {
    return $this->plusAbonados;
  }

  /**
   * Desactiva la posibilidad de abonar un trasbordo
   */

   public function noContarTrasbordos() {
    $this->contarTrasbordos = false;
  }

  /**
   * Verifica y en caso de ser posible efectua el pago de un pasaje,
   *  descontando el precio del pasaje abonado y establece la fecha del pasaje.
   * En caso de no ser posible, devuelve false. En caso de ser necesario, abona un pasaje plus.
   * Tambien verifica si el pasaje que se va a pagar es un trasbordo
   *
   * @return bool
   *   indica si se ha podido pagar el pasaje
   */

   public function pagarPasaje() {
    $horaMinutos = $this->horaEnMinutos();
    $hora = $this->hora();
    $this->actualTrasbordo = $this->trasbordo->esTrasbordo($linea,$minutos,$horaMinutos,$hora);

    if ($this->actualTrasbordo) {
      $this->precio = 0;
    }

    if ($this->saldo >= (-$this->precio)) {
      $this->saldo = (float) number_format($this->saldo - $this->precio, 2);
      if ($this->saldo < 0) {
        $this->cantPlus++;
      }
      $this->dia = $this->dia();
      $this->hora = (int) date("H", $this->tiempo->time());
      $this->lineaAnterior = $this->linea;
      return TRUE;
    }

    $this->minutos = $this->horaEnMinutos();
    $this->fueTrasbordo = false;

    return FALSE;
  }

  /**
   * Avanza el tiempo si este es de tipo TiempoFalso
   *
   *  Mandar esta logica a Tiempo.php
   *
   * @param int $segundos
   *
   * @return bool
   *   Indica si se pudo avanzar el tiempo
   */

   public function avanzarTiempo($segundos) {
    if ($this->tiempo instanceof TiempoFalso) {
      $this->tiempo->avanzar($segundos);
      return TRUE;
    }
    return FALSE;
  }




  /**
   * Establece el precio al precio normal de un pasaje (32.50)
   */


   public function reestablecerPrecio() {
    $this->precio = $this->precioOriginal;
  }


  /**
   * Devuelve el dia en el que se abona un pasaje
   *
   * @return string
   *   Dia
   */

  protected function dia() {
    return date("l", $this->tiempo->time());
  }

     /**
   * Devuelve la hora del dia en minutos
   *
   * @return int
   *   Hora en minutos
   */
  protected function horaEnMinutos() {
    return $this->tiempo->time() / 60;
  }

  /**
   * Devuelve la hora del dia en formato 24h
   *
   * @return int
   *   Hora
   */
  protected function hora() {
    return (int) date("H", $this->tiempo->time());
  }
}
