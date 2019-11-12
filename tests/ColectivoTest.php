<?php

namespace TrabajoPagos;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

  public function testDatosColectivo() {
    $colectivo = new Colectivo(143, "143 Rojo", "Semtur");
    $this->assertEquals($colectivo->linea(), "143 Rojo");
    $this->assertEquals($colectivo->numero(), 143);
    $this->assertEquals($colectivo->empresa(), "Semtur");
    $colectivo = new Colectivo(113, "113", "empresa");
    $this->assertEquals($colectivo->linea(), "113");
    $this->assertEquals($colectivo->numero(), 113);
    $this->assertEquals($colectivo->empresa(), "empresa");
  }

/**
 *
 * Cambiar este test para que Boleto sea lo que se muestra en el visor de la maquina del colectivo
 *
 */
  public function testPagarCon() {
    $mediodepago = new MedioDePago(new Tiempo(), new Trasbordo(), new Saldo());
    $mediodepago->recargar(100);
    $colectivo = new Colectivo(null, null, null);
    $boleto = $colectivo->pagarCon($mediodepago);
    $this->assertNotFalse($boleto);
    $boleto = $colectivo->pagarCon($mediodepago);
    $this->assertNotFalse($boleto);
    $mediodepago = new MedioDePago(new Tiempo(), new Trasbordo(), new Saldo());
    $mediodepago->pagarPasaje();
    $mediodepago->pagarPasaje();
    $this->assertFalse($colectivo->pagarCon($mediodepago));
    $mediodepago->recargar(10);
    $this->assertFalse($colectivo->pagarCon($mediodepago));
  }


}
