<?php

namespace TrabajoPagos;

interface TiempoInterface {
  /**
   * decide si se cobra trasbordo o pasaje normal
   */
  public function esTrasbordo();

}