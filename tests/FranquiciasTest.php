<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class FranquiciasTest extends TestCase {

  public function testMedioBoleto() {
    $tarjeta = new MedioBoleto(new TiempoFalso(900));
    $tarjeta->recargar(100);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 92.6);
    $tarjeta->avanzarTiempo(5500);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 85.2);
    $tarjeta->avanzarTiempo(5500);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 77.8);
    $tarjeta->avanzarTiempo(5500);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 70.4);

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
    $this->assertEquals($tarjeta->obtenerSaldo(), 92.6);
    $tarjeta->avanzarTiempo(200);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 77.8);
    $tarjeta->avanzarTiempo(200);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 70.4);
    $tarjeta->avanzarTiempo(100);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 55.6);
    $tarjeta->avanzarTiempo(20);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 40.8);
    $tarjeta->avanzarTiempo(90);
    $tarjeta->pagarPasaje();
    $this->assertEquals($tarjeta->obtenerSaldo(), 26);

  }

  public function testViajePlus() {
    $tarjeta = new MedioBoleto(new TiempoFalso(900));
    $tarjeta->noContarTrasbordos();
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), -14.8);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), -29.6);
    $tarjeta->recargar(100);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 63);
  }

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
    $this->assertEquals($tarjeta->obtenerSaldo(), 55.6);
    $tarjeta->avanzarTiempo(86000);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 48.2);

  }

  public function testTiempoReal() {
    $tarjeta = new MedioBoleto(new Tiempo());
    $this->assertFalse($tarjeta->avanzarTiempo(200));
    $this->assertFalse($tarjeta->avanzarTiempo("200"));
    $this->assertFalse($tarjeta->avanzarTiempo(12));
    $this->assertFalse($tarjeta->avanzarTiempo(-200));
  }

  public function testTipoTarjeta() {
    $secundario = new MedioBoleto(new Tiempo());
    $universitario = new MedioBoleto(new Tiempo(), 0);
    $this->assertEquals($secundario->tipoTarjeta(), "Medio Boleto Secundario");
    $this->assertEquals($universitario->tipoTarjeta(), "Medio Boleto Universitario");
    $this->assertNotEquals($secundario->tipoTarjeta(), "Medio Boleto Universitario");
    $this->assertNotEquals($universitario->tipoTarjeta(), "Medio Boleto Secundario");
  }
}