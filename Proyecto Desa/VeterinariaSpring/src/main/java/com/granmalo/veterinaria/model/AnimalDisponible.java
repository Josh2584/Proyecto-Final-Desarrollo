package com.granmalo.veterinaria.model;

import java.math.BigDecimal;
import java.time.LocalDate;

public record AnimalDisponible(
        long id,
        String nombre,
        String genero,
        String color,
        BigDecimal precioLista,
        LocalDate fechaNacimiento,
        String raza,
        String categoria
) {
}
