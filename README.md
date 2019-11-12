# TP-Boletos

Principio S aplica:
-Colectivo
-FranquiciaCompleta
-MedioBoleto
-Saldo
-Tiempo
-TiempoFalso

Principio O aplica
-Colectivo
-FranquiciaCompleta
-MedioBoleto
-Saldo
-Tiempo
-TiempoFalso

Principio L aplica:
-Colectivo
-Saldo
-MedioDePago (Pero sus hijos no lo cumplen)
-Tiempo
-TiempoFalso

Principio I aplica:
-Colectivo
-Saldo
-MedioDePago (Pero sus hijos no lo cumplen)
-Tiempo
-TiempoFalso

Principio D aplica: (No estoy 100% seguro de haberlo entendido bien, asi que puede que esto este mal)
-Colectivo
-FranquiciaCompleta
-MedioBoleto
-Saldo
-MedioDePago
-Tiempo
-TiempoFalso

No Cumple S:
-MedioDePago (Se va muy al carajo con las lineas, ver si se puede hacer algo al respecto. Tambien hay funciones que se repiten en otras clases, Por ejemplo recargaValida)

No Cumple O:
-MedioDePago (No la pude chequear por completo, pero veo muy poco probable que una clases asi de grande no presente problemas cuando se quiera cambiar otras cosas del codigo)

No Cumple L:
-FranquiciaCompleta (Modifica la funcion pagarPasaje de su padre)
-MedioBoleto (Modifica la funcion pagarPasaje de su padre)

No Cumple I:
-FranquiciaCompleta (No usa ningunos de los metodos de MedioDePagoInterface)
-MedioBoleto (No usa ningunos de los metodos de MedioDePagoInterface)

No Cumple D: