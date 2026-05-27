package com.granmalo.veterinaria.model;

public enum Role {
    CAJERO(1, "Cajero"),
    VETERINARIO(2, "Veterinario"),
    CIRUJANO(3, "Cirujano"),
    ADMIN(3, "Admin");

    private final int level;
    private final String label;

    Role(int level, String label) {
        this.level = level;
        this.label = label;
    }

    public int level() {
        return level;
    }

    public String label() {
        return label;
    }
}
