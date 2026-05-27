package com.granmalo.veterinaria.model;

import java.io.Serializable;

public record AuthUser(
        String username,
        String displayName,
        Role role
) implements Serializable {

    public boolean canSell() {
        return role.level() >= Role.CAJERO.level();
    }

    public boolean canConsult() {
        return role.level() >= Role.VETERINARIO.level();
    }

    public boolean canOperate() {
        return role.level() >= Role.CIRUJANO.level();
    }
}
