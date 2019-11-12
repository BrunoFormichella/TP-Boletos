<?php

namespace TrabajoPagos;

interface TrasbordoInterface {
  /**
   * decide si se cobra trasbordo o pasaje normal
   */
  public function esTrasbordo();

}