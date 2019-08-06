<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class BoletoTest extends TestCase {

  public function testTipo() {
    $tarjeta = new Tarjeta(new Tiempo());
    $colectivo = new Colectivo(null, "143 Rojo", null);
    $boleto1 = new Boleto($colectivo, $tarjeta, new Tiempo());
    $this->assertEquals($boleto1->obtenerColectivo(), $colectivo);
    $this->assertEquals($boleto1->obtenerTipo(), "Normal");
    $this->assertEquals($boleto1->obtenerLinea(), "143 Rojo");
    $this->assertEquals($boleto1->obtenerTotalAbonado(), 14.8);
    $this->assertEquals($boleto1->obtenerValor(), 14.8);
    $this->assertEquals($boleto1->obtenerSaldo(), 0);
    $this->assertEquals($boleto1->obtenerID(), 1);
  }

  public function testFecha() {
    $tarjeta = new Tarjeta(new Tiempo());
    $colectivo = new Colectivo(null, "", null);
    $tiempo = new TiempoFalso();
    $boleto = new Boleto($colectivo, $tarjeta, $tiempo);
    $this->assertEquals($boleto->obtenerFecha(), date("d/m/Y H:i:s", 0));
    $tiempo->avanzar(95);
    $boleto = new Boleto($colectivo, $tarjeta, $tiempo);
    $this->assertEquals($boleto->obtenerFecha(), date("d/m/Y H:i:s", 95));

  }

  public function testPrecio() {
    $tarjeta = new Tarjeta(new TiempoFalso());
    $colectivo = new Colectivo(null, "", null);
    $tiempo = new TiempoFalso();
    $tarjeta->pagarPasaje();
    $tarjeta->pagarPasaje();
    $tarjeta->recargar(50);
    $tarjeta->recargar(10);
    $boleto = new Boleto($colectivo, $tarjeta, $tiempo);
    $this->assertEquals($boleto->obtenerTotalAbonado(), 14.8 * 3);
    $tarjeta->avanzarTiempo(5500);
    $tarjeta->pagarPasaje();
    $boleto = new Boleto($colectivo, $tarjeta, $tiempo);
    $this->assertEquals($boleto->obtenerTotalAbonado(), 14.8);
    $tarjeta->avanzarTiempo(5500);
    $tarjeta->pagarPasaje();
    $tarjeta->avanzarTiempo(5500);
    $tarjeta->pagarPasaje();
    $tarjeta->recargar(50);
    $boleto = new Boleto($colectivo, $tarjeta, $tiempo);
    $this->assertEquals($boleto->obtenerTotalAbonado(), 29.6);

    $tarjeta = new MedioBoleto(new Tiempo(), 0);
    $boleto = new Boleto($colectivo, $tarjeta, $tiempo);
    $this->assertEquals($boleto->obtenerTotalAbonado(), 7.4);
    $tarjeta->avanzarTiempo(5500);
    $tarjeta->pagarPasaje();
    $boleto = new Boleto($colectivo, $tarjeta, $tiempo);
    $this->assertEquals($boleto->obtenerTotalAbonado(), 7.4);
    $tarjeta->recargar(50);
    $boleto = new Boleto($colectivo, $tarjeta, $tiempo);
    $this->assertEquals($boleto->obtenerTotalAbonado(), 14.8);
  }

  public function testDescripcion() {
    $tarjeta = new FranquiciaCompleta(new Tiempo());
    $colectivo = new Colectivo(null, "", null);
    $boleto = new Boleto($colectivo, $tarjeta, new Tiempo());
    $this->assertEquals($boleto->obtenerDescripcion(), "$0");
    $this->assertEquals($boleto->obtenerTipo(), "Franquicia completa");

    $tarjeta = new Tarjeta(new Tiempo());
    $tarjeta->pagarPasaje();
    $tarjeta->pagarPasaje();
    $boleto = new Boleto($colectivo, $tarjeta, new Tiempo());

    $this->assertEquals($boleto->obtenerDescripcion(), "$0 Ultimo Plus");
  }

  public function testTipoTrasbordo() {
    $tarjeta = new FranquiciaCompleta(new Tiempo());
    $colectivo = new Colectivo(null, "", null);
    $boleto = new Boleto($colectivo, $tarjeta, new Tiempo());
    $this->assertEquals($boleto->tipoTrasbordo(11), "");
    $this->assertEquals($boleto->tipoTrasbordo(132), "");
    $this->assertEquals($boleto->tipoTrasbordo(14.8 / 3), "Trasbordo");
    $this->assertEquals($boleto->tipoTrasbordo(7.4 / 3), "Medio Trasbordo");
    $this->assertEquals($boleto->tipoTrasbordo(10.4), "");
    $this->assertEquals($boleto->tipoTrasbordo(11.8), "");
  }
}
