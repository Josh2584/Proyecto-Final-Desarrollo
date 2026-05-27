package com.granmalo.veterinaria.model;

import java.io.Serializable;

public record DashboardStats(
        int totalAnimales,
        int animalesDisponibles,
        int totalProductos,
        int totalClientes,
        int totalConsultas,
        int totalCirugias
) implements Serializable {
}
