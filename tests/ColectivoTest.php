<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

  public function testPagarCon() {
    $tarjeta = new Tarjeta(new Tiempo());
    $tarjeta->recargar(100);
    $colectivo = new Colectivo(null, null, null);
    $boleto = $colectivo->pagarCon($tarjeta);
    $this->assertNotFalse($boleto);
    $boleto = $colectivo->pagarCon($tarjeta);
    $this->assertNotFalse($boleto);
    $tarjeta = new Tarjeta(new Tiempo());
    $tarjeta->pagarPasaje();
    $tarjeta->pagarPasaje();
    $this->assertFalse($colectivo->pagarCon($tarjeta));
    $tarjeta->recargar(10);
    $this->assertFalse($colectivo->pagarCon($tarjeta));
  }

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
}
