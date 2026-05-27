package com.granmalo.veterinaria.model;

import java.math.BigDecimal;
import java.time.LocalDate;

public record Cirugia(
        long id,
        LocalDate fecha,
        String tipo,
        String descripcion,
        String animal,
        String empleado,
        BigDecimal costo
) {
}
