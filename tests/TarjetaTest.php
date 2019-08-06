<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {

  /**
   * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
   */
  public function testCargaSaldo() {
    $tarjeta = new Tarjeta(new Tiempo());
    $this->assertTrue($tarjeta->recargar(10));
    $this->assertEquals($tarjeta->obtenerSaldo(), 10);

    $this->assertTrue($tarjeta->recargar(20));
    $this->assertEquals($tarjeta->obtenerSaldo(), 30);

    $this->assertTrue($tarjeta->recargar(30));
    $this->assertEquals($tarjeta->obtenerSaldo(), 60);

    $this->assertTrue($tarjeta->recargar(50));
    $this->assertEquals($tarjeta->obtenerSaldo(), 110);

    $this->assertTrue($tarjeta->recargar(100));
    $this->assertEquals($tarjeta->obtenerSaldo(), 210);

    $this->assertTrue($tarjeta->recargar(510.15));
    $this->assertEquals($tarjeta->obtenerSaldo(), 802.08);

    $this->assertTrue($tarjeta->recargar(962.59));
    $this->assertEquals($tarjeta->obtenerSaldo(), 1986.25);
  }

  /**
   * Comprueba que la tarjeta no puede cargar saldos invalidos.
   */
  public function testCargaSaldoInvalido() {
    $tarjeta = new Tarjeta(new Tiempo());
    $this->assertFalse($tarjeta->recargar(15));
    $this->assertEquals($tarjeta->obtenerSaldo(), 0);
    $this->assertFalse($tarjeta->recargar(35));
    $this->assertEquals($tarjeta->obtenerSaldo(), 0);
    $this->assertFalse($tarjeta->recargar(115));
    $this->assertEquals($tarjeta->obtenerSaldo(), 0);
    $this->assertFalse($tarjeta->recargar(155.15));
    $this->assertEquals($tarjeta->obtenerSaldo(), 0);
    $this->assertFalse($tarjeta->recargar(157.15));
    $this->assertEquals($tarjeta->obtenerSaldo(), 0);
  }

  public function testPagoConSaldo() {
    $tarjeta = new Tarjeta(new Tiempo());
    $tarjeta->recargar(100);

    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), 100 - 14.80);

  }

  public function testViajePlus() {
    $tarjeta = new Tarjeta(new Tiempo());

    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), -14.80);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertEquals($tarjeta->obtenerSaldo(), -29.60);

    $this->assertFalse($tarjeta->pagarPasaje());
    $tarjeta->recargar(10);
    $this->assertEquals($tarjeta->obtenerSaldo(), -19.60);
    $this->assertFalse($tarjeta->pagarPasaje());
    $tarjeta->recargar(10);
    $this->assertTrue($tarjeta->pagarPasaje());
    $this->assertFalse($tarjeta->pagarPasaje());

  }

  public function testRecargaPlus() {
    $tarjeta = new Tarjeta(new TiempoFalso());
    $tarjeta->pagarPasaje();
    $tarjeta->recargar(20);
    $this->assertEquals($tarjeta->obtenerSaldo(), 5.2);
  }

  public function testTrasbordo() {
    $tarjeta = new Tarjeta(new TiempoFalso()); //Se crea el 1 de enero de 1970: Jueves 00:00hs
    $tarjeta->recargar(100);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 85.2);
    $tarjeta->avanzarTiempo(5000);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 80.27);
    $tarjeta->avanzarTiempo(21200);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 65.47);
    $tarjeta->avanzarTiempo(4000);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 50.67);
    $tarjeta->avanzarTiempo(3000);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 45.74);
  }

  public function testLimitacionSabado() {
    $tarjeta = new Tarjeta(new TiempoFalso(55 * 3600));
    $tarjeta->recargar(100);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $tarjeta->avanzarTiempo(3660);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 70.4);
    $tarjeta->avanzarTiempo(3000);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 65.47);
    $tarjeta->avanzarTiempo(3600 * 7);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 50.67);
    $tarjeta->avanzarTiempo(60 * 80);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 45.74);
  }

  public function testLinea() {
    $tarjeta = new MedioBoleto(new TiempoFalso());
    $tarjeta->recargar(100);
    $colectivo = new Colectivo(143, "143 Rojo", "Semtur");
    $colectivo2 = new Colectivo(133, "133 Negro", "Otra");
    $colectivo->pagarCon($tarjeta);
    $tarjeta->avanzarTiempo(800);
    $colectivo->pagarCon($tarjeta);
    $this->assertEquals($tarjeta->obtenerSaldo(), 85.2);
    $tarjeta->avanzarTiempo(800);
    $boleto = $colectivo2->pagarCon($tarjeta);
    $this->assertEquals($tarjeta->obtenerSaldo(), 82.73);
    $tarjeta = new Tarjeta(new TiempoFalso());
    $tarjeta->recargar(100);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 100 - 14.8);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 80.27);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 65.47);
  }

  public function testTrasbordoConPlus() {
    $tarjeta = new Tarjeta(new TiempoFalso());
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), -14.8);
    $tarjeta->recargar(20);
    $tarjeta->recargar(10);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 0.4);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 0.4 - 14.8);
    $tarjeta->recargar(100);
    $tarjeta->pagarPasaje();
    $tarjeta->reestablecerPrecio();
    $this->assertEquals($tarjeta->obtenerSaldo(), 100.4 - 29.6);
  }
}
