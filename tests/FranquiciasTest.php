<?php

namespace TrabajoPagos;

use PHPUnit\Framework\TestCase;

class FranquiciasTest extends TestCase {

  public function testMedioBoleto() {
    $mediodepago = new MedioBoleto(new TiempoFalso(900));
    $mediodepago->recargar(100);
    $mediodepago->pagarPasaje();
    $this->assertEquals($mediodepago->obtenerSaldo(), 83.75);
    $mediodepago->avanzarTiempo(8000);
    $mediodepago->pagarPasaje();
    $this->assertEquals($mediodepago->obtenerSaldo(), 67.5);
    $mediodepago->avanzarTiempo(8000);
    $mediodepago->pagarPasaje();
    $this->assertEquals($mediodepago->obtenerSaldo(), 51.25);
    $mediodepago->avanzarTiempo(8000);
    $mediodepago->pagarPasaje();
    $this->assertEquals($mediodepago->obtenerSaldo(), 35);

  }

  public function testFranquiciaCompleta() {

    $mediodepago = new FranquiciaCompleta(new Tiempo());
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertTrue($mediodepago->pagarPasaje());
  }

  public function testLimitacion5mins() {
    $mediodepago = new MedioBoleto(new TiempoFalso(700));
    $mediodepago->noContarTrasbordos();
    $mediodepago->recargar(100);
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), 83.75);
    $mediodepago->avanzarTiempo(200);
    $mediodepago->pagarPasaje();
    $this->assertEquals($mediodepago->obtenerSaldo(), 51.25);
    $mediodepago->avanzarTiempo(200);
    $mediodepago->pagarPasaje();
    $this->assertEquals($mediodepago->obtenerSaldo(), 35);
  }

  public function testViajePlus() {
    $mediodepago = new MedioBoleto(new TiempoFalso(900));
    $mediodepago->noContarTrasbordos();
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), -32.5);
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), -65);
    $mediodepago->recargar(100);
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), 18.75);
  }

  /*
  public function testLimitacionUniversitario() {
    $mediodepago = new MedioBoleto(new TiempoFalso(900), 0);
    $mediodepago->noContarTrasbordos();
    $mediodepago->recargar(100);
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), 92.6);
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), 77.8);
    $mediodepago->avanzarTiempo(900);
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), 70.4);
    $mediodepago->avanzarTiempo(900);
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), 63);
    $mediodepago->avanzarTiempo(86000);
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), 48.2);

  }
  */
  
  public function testTiempoReal() {
    $mediodepago = new MedioBoleto(new Tiempo());
    $this->assertFalse($mediodepago->avanzarTiempo(200));
    $this->assertFalse($mediodepago->avanzarTiempo("200"));
    $this->assertFalse($mediodepago->avanzarTiempo(12));
    $this->assertFalse($mediodepago->avanzarTiempo(-200));
  }

}