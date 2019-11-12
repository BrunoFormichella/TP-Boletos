<?php

namespace TrabajoPagos;

class MedioBoleto extends MedioDePago {

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

   public function __construct(TiempoInterface $tiempo, TrasbordoInterface $trasbordo) {
    parent::__construct($tiempo, $trasbordo);
    
    $this->precio /= 2;

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
  
    if ($this->pasaron5Minutos()) {
      $this->tiempoAux = $this->tiempo->time();
      $this->usos++;      
      return parent::pagarPasaje();
    }
  
    else {
      return $this->pasajeNormal();
    }
  
  }

}