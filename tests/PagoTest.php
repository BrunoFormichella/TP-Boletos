<?php

namespace TrabajoPagos;

use PHPUnit\Framework\TestCase;

class MedioDePagoTest extends TestCase {

  /**
   * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
   */
  public function testCargaSaldo() {
    $mediodepago = new MedioDePago(new Tiempo());
    $this->assertTrue($mediodepago->recargar(10));
    $this->assertEquals($mediodepago->obtenerSaldo(), 10);

    $this->assertTrue($mediodepago->recargar(20));
    $this->assertEquals($mediodepago->obtenerSaldo(), 30);

    $this->assertTrue($mediodepago->recargar(30));
    $this->assertEquals($mediodepago->obtenerSaldo(), 60);

    $this->assertTrue($mediodepago->recargar(50));
    $this->assertEquals($mediodepago->obtenerSaldo(), 110);

    $this->assertTrue($mediodepago->recargar(100));
    $this->assertEquals($mediodepago->obtenerSaldo(), 210);

    $this->assertTrue($mediodepago->recargar(1119.90));
    $this->assertEquals($mediodepago->obtenerSaldo(), 1510);

    $this->assertTrue($mediodepago->recargar(2114.11));
    $this->assertEquals($mediodepago->obtenerSaldo(), 4110);
  }

  /**
   * Comprueba que la tarjeta no puede cargar saldos invalidos.
   */
  public function testCargaSaldoInvalido() {

    $mediodepago = new MedioDePago(new Tiempo());
    $this->assertFalse($mediodepago->recargar(15));
    $this->assertEquals($mediodepago->obtenerSaldo(), 0);

    $this->assertFalse($mediodepago->recargar(35));
    $this->assertEquals($mediodepago->obtenerSaldo(), 0);

    $this->assertFalse($mediodepago->recargar(115));
    $this->assertEquals($mediodepago->obtenerSaldo(), 0);

    $this->assertFalse($mediodepago->recargar(155.15));
    $this->assertEquals($mediodepago->obtenerSaldo(), 0);

    $this->assertFalse($mediodepago->recargar(157.15));
    $this->assertEquals($mediodepago->obtenerSaldo(), 0);
  }

  public function testPagoConSaldo() {
    $mediodepago = new MedioDePago(new Tiempo());
    $mediodepago->recargar(100);

    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), 100 - 32.5);

  }

  public function testViajePlus() {
    $mediodepago = new MedioDePago(new Tiempo());

    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), -32.5);

    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertEquals($mediodepago->obtenerSaldo(), -65);

    $this->assertFalse($mediodepago->pagarPasaje());
    $mediodepago->recargar(10);
    $this->assertEquals($mediodepago->obtenerSaldo(), -55);
    
    $this->assertFalse($mediodepago->pagarPasaje());
    $mediodepago->recargar(50);
    $this->assertTrue($mediodepago->pagarPasaje());
    $this->assertFalse($mediodepago->pagarPasaje());

  }

  public function testRecargaPlus() {
    $mediodepago = new MedioDePago(new TiempoFalso());
    $mediodepago->pagarPasaje();
    $mediodepago->recargar(50);
    $this->assertEquals($mediodepago->obtenerSaldo(), 17.5);
  }

  public function testTrasbordo() {
    $mediodepago = new MedioDePago(new TiempoFalso()); //Se crea el 1 de enero de 1970: Jueves 00:00hs
    $mediodepago->recargar(100);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 67.5);

    $mediodepago->avanzarTiempo(500);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 67.5);

    $mediodepago->avanzarTiempo(21200);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 35);

    $mediodepago->avanzarTiempo(4000);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 2.5);
  }

  /*
  public function testLimitacionSabado() {
    $mediodepago = new MedioDePago(new TiempoFalso(55 * 3600));
    $mediodepago->recargar(100);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $mediodepago->avanzarTiempo(3660);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 70.4);

    $mediodepago->avanzarTiempo(3000);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 65.47);

    $mediodepago->avanzarTiempo(3600 * 7);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 50.67);
    
    $mediodepago->avanzarTiempo(60 * 80);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 45.74);
  }
  */

  public function testLinea() {

    $mediodepago = new MedioBoleto(new TiempoFalso());
    $mediodepago->recargar(100);
    $colectivo = new Colectivo(143, "143 Rojo", "Semtur");
    $colectivo2 = new Colectivo(133, "133 Negro", "Otra");
    $colectivo->pagarCon($mediodepago);
    $mediodepago->avanzarTiempo(800);
    $colectivo->pagarCon($mediodepago);
    $this->assertEquals($mediodepago->obtenerSaldo(), 67.5);

    $mediodepago->avanzarTiempo(800);
    $boleto = $colectivo2->pagarCon($mediodepago);
    $this->assertEquals($mediodepago->obtenerSaldo(), 67.5);

    $mediodepago = new MedioDePago(new TiempoFalso());
    $mediodepago->recargar(100);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 100 - 32.5);

    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 67.5);

    $mediodepago->avanzarTiempo(2000);
    
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 35);
  }

  public function testTrasbordoConPlus() {

    $mediodepago = new MedioDePago(new TiempoFalso());
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), -32.5);

    $mediodepago->recargar(20);
    $mediodepago->recargar(20);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), -25);

    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), -57.5);

    $mediodepago->recargar(100);
    $mediodepago->pagarPasaje();
    $mediodepago->reestablecerPrecio();
    $this->assertEquals($mediodepago->obtenerSaldo(), 10);
  }
}
