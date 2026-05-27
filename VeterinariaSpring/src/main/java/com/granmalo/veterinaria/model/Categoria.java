package com.granmalo.veterinaria.model;

import java.io.Serializable;

public record Categoria(
        long id,
        String nombre
) implements Serializable {
}
