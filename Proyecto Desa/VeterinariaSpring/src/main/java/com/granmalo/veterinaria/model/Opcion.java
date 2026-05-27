package com.granmalo.veterinaria.model;

import java.math.BigDecimal;

public record Opcion(
        long id,
        String nombre,
        String detalle,
        BigDecimal precio,
        Integer existencia
) {
}
