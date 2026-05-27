package com.granmalo.veterinaria.model;

import java.math.BigDecimal;
import java.util.LinkedHashMap;
import java.util.Map;

public class Ticket {
    private final String tipo;
    private final long folio;
    private final String volver;
    private final BigDecimal total;
    private final Map<String, String> datos = new LinkedHashMap<>();

    public Ticket(String tipo, long folio, String volver, BigDecimal total) {
        this.tipo = tipo;
        this.folio = folio;
        this.volver = volver;
        this.total = total;
    }

    public Ticket dato(String etiqueta, String valor) {
        datos.put(etiqueta, valor);
        return this;
    }

    public String getTipo() {
        return tipo;
    }

    public long getFolio() {
        return folio;
    }

    public String getVolver() {
        return volver;
    }

    public BigDecimal getTotal() {
        return total;
    }

    public Map<String, String> getDatos() {
        return datos;
    }
}
