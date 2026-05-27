package com.granmalo.veterinaria.model;

import java.io.Serializable;
import java.math.BigDecimal;
import java.time.Instant;

public record OperacionEvent(
        String tipo,
        long folio,
        BigDecimal total,
        Instant fecha
) implements Serializable {
}
