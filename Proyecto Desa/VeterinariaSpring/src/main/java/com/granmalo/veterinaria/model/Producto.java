package com.granmalo.veterinaria.model;

import java.math.BigDecimal;

public record Producto(
        long id,
        String descripcion,
        BigDecimal precioLista,
        int existencia,
        String requiereReceta,
        String categoria
) {
}
