<?php

namespace TrabajoTarjeta;

class MedioBoleto extends Tarjeta {

  protected $tipo;
  protected $tiempo;
  protected $tiempoAux;
  protected $usos;

  /**
   * Contruye una tarjeta de tipo medio boleto
   *
   * @param TiempoInterface $tiempo
   *   Tiempo que utiliza la tarjeta (utilizar tiempo falso solo en testing)
   * @param int $tipo
   *   Tipo de la tarjeta. 0 -> Medio Boleto Universitario, en cualquier otro caso -> Medio Boleto Secundario
   */

  public function __construct(TiempoInterface $tiempo, $tipo = 1) {
    parent::__construct($tiempo);
    $this->precio /= 2;

    switch ($tipo) {
      case 0:
        $this->tipo = "Medio Boleto Universitario";
        break;
      default:
        $this->tipo = "Medio Boleto Secundario";
        break;
    }

    $this->tiempo = $tiempo;

    $this->tiempoAux = -4000;

    $this->precioOriginal = $this->precio;
  }

  /**
   * Determina si el tiempo entre el pasaje anterior y el pasaje que se esta pagando es mayor a 5 minutos
   *
   * @return bool
   *   Indica si pasaron los 5 minutos
   */

  protected function pasaron5Minutos() {
    return ($this->tiempo->time() - $this->tiempoAux) > 300;
  }

  /**
   * Abona un pasaje con el precio que tiene una una tarjeta que no es franquicia
   *
   * @return bool
   *   Indica si se pudo pagar el pasaje
   */

  protected function pasajeNormal() {
    $this->precio *= 2;
    $aux = parent::pagarPasaje();
    $this->precio /= 2;
    return $aux;
  }

  /**
   * Verifica si el tipo de franquicia es universitaria
   *
   * @return bool
   *   Indica si la franquicia es universitaria
   */

  protected function esUniversitario() {
    return $this->tipo == "Medio Boleto Universitario";
  }

  /**
   * Devuelve el tipo de la tarjeta que se esta utilizando
   *
   * @return string
   *   Tipo de la tarjeta
   */

  public function tipoTarjeta() {
    return $this->tipo;
  }

  /**
   * Reestablece la limitacion de los 2 medios boletos por dia para el boleto universitario
   * y en caso contrario verifica si se utilizaron los dos medios boletos en el dia.
   *
   * @return bool
   *   Verifica si se utilizaron los dos viajes en el dia
   */

  private function dosViajes() {
    if ($this->tiempo->time() - $this->tiempoAux > 86400) {
      $this->usos = 0;
    }
    if ($this->usos == 2) {
      return true;
    }
    return false;
  }

  /**
   * Efectua el pago del boleto con sus beneficios teniendo en cuenta las limitaciones por tiempo
   *
   * @return bool
   *   Indica si se pudo pagar el pasaje
   */

  public function pagarPasaje() {
    if ($this->obtenerSaldo() < 7.4) {
      return $this->pasajeNormal();
    }

    if ($this->esUniversitario()) {
      if ($this->dosViajes()) {
        return $this->pasajeNormal();
      }
    }

    if ($this->pasaron5Minutos()) {
      $this->tiempoAux = $this->tiempo->time();
      if ($this->esUniversitario()) {
        $this->usos++;
      }
      return parent::pagarPasaje();
    }
    else {
      return $this->pasajeNormal();
    }

  }

}