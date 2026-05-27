package com.granmalo.veterinaria.service;

import com.granmalo.veterinaria.model.AuthUser;
import com.granmalo.veterinaria.model.Role;
import org.springframework.stereotype.Service;

import java.util.Locale;
import java.util.Map;
import java.util.Optional;

@Service
public class AuthService {

    private record LoginData(String password, String displayName, Role role) {
    }

    private static final Map<String, LoginData> USERS = Map.of(
            "cajero", new LoginData("2569d", "Cajero", Role.CAJERO),
            "veterinario", new LoginData("4723c", "Veterinario", Role.VETERINARIO),
            "cirujano", new LoginData("5937b", "Cirujano", Role.CIRUJANO),
            "admin", new LoginData("1234a", "Admin", Role.ADMIN)
    );

    public Optional<AuthUser> login(String username, String password) {
        if (username == null || password == null) {
            return Optional.empty();
        }
        String key = username.trim().toLowerCase(Locale.ROOT);
        LoginData data = USERS.get(key);
        if (data == null || !data.password().equals(password)) {
            return Optional.empty();
        }
        return Optional.of(new AuthUser(key, data.displayName(), data.role()));
    }
}
