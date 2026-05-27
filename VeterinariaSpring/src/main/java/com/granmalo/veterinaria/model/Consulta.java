package com.granmalo.veterinaria.model;

import java.math.BigDecimal;
import java.time.LocalDate;

public record Consulta(
        long id,
        LocalDate fecha,
        String motivo,
        String diagnostico,
        String tratamiento,
        String animal,
        String empleado,
        BigDecimal costo
) {
}
