package com.granmalo.veterinaria.config;

import org.apache.camel.builder.RouteBuilder;
import org.springframework.stereotype.Component;

@Component
public class VeterinariaCamelRoutes extends RouteBuilder {

    public static final String OPERACION_AUDITORIA = "direct:operacion-auditoria";

    @Override
    public void configure() {
        from(OPERACION_AUDITORIA)
                .routeId("operacion-auditoria")
                .log("Evento veterinario recibido para auditoria: ${body}")
                .to("seda:operacion-historial");

        from("seda:operacion-historial")
                .routeId("operacion-historial")
                .log("Evento veterinario procesado por historial interno: ${body}");
    }
}
