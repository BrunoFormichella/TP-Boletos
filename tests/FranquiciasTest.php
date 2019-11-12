<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class FranquiciasTest extends TestCase {

  public function testMedioBoleto() {
    $tarjeta = new MedioBoleto(new TiempoFalso(900));
    $tarjeta->recargar(100);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 83.75);
    $tarjeta->avanzarTiempo(8000);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 67.5);
    $tarjeta->avanzarTiempo(8000);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 51.25);
    $tarjeta->avanzarTiempo(8000);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 35);

  }

  public function testFranquiciaCompleta() {

    $tarjeta = new FranquiciaCompleta(new Tiempo());
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertTrue($tarjeta->pagarPasaje());
  }

  public function testLimitacion5mins() {
    $tarjeta = new MedioBoleto(new TiempoFalso(700));
    $tarjeta->noContarTrasbordos();
    $tarjeta->recargar(100);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 83.75);
    $tarjeta->avanzarTiempo(200);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 51.25);
    $tarjeta->avanzarTiempo(200);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 35);
  }

  public function testViajePlus() {
    $tarjeta = new MedioBoleto(new TiempoFalso(900));
    $tarjeta->noContarTrasbordos();
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), -32.5);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), -65);
    $tarjeta->recargar(100);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 18.75);
  }

  /*
  public function testLimitacionUniversitario() {
    $tarjeta = new MedioBoleto(new TiempoFalso(900), 0);
    $tarjeta->noContarTrasbordos();
    $tarjeta->recargar(100);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 92.6);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 77.8);
    $tarjeta->avanzarTiempo(900);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 70.4);
    $tarjeta->avanzarTiempo(900);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 63);
    $tarjeta->avanzarTiempo(86000);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 48.2);

  }
  */
  
  public function testTiempoReal() {
    $tarjeta = new MedioBoleto(new Tiempo());
    $this->assertFalse($tarjeta->avanzarTiempo(200));
    $this->assertFalse($tarjeta->avanzarTiempo("200"));
    $this->assertFalse($tarjeta->avanzarTiempo(12));
    $this->assertFalse($tarjeta->avanzarTiempo(-200));
  }

}