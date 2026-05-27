package com.granmalo.veterinaria.config;

import com.granmalo.veterinaria.model.AuthUser;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import org.springframework.stereotype.Component;
import org.springframework.web.servlet.HandlerInterceptor;
import org.springframework.web.servlet.ModelAndView;

@Component
public class AuthInterceptor implements HandlerInterceptor {

    public static final String SESSION_USER = "authUser";

    @Override
    public boolean preHandle(HttpServletRequest request, HttpServletResponse response, Object handler) throws Exception {
        String path = request.getRequestURI();
        if (path.equals("/login") || path.equals("/error") || path.equals("/favicon.ico")
                || path.startsWith("/css/") || path.startsWith("/js/") || path.startsWith("/images/")) {
            return true;
        }
        AuthUser user = currentUser(request.getSession(false));
        if (user == null) {
            response.sendRedirect("/login");
            return false;
        }
        if (!allowed(user, path)) {
            response.sendRedirect("/");
            return false;
        }
        return true;
    }

    @Override
    public void postHandle(HttpServletRequest request, HttpServletResponse response, Object handler, ModelAndView modelAndView) {
        if (modelAndView == null) {
            return;
        }
        AuthUser user = currentUser(request.getSession(false));
        if (user != null) {
            modelAndView.addObject("usuarioActual", user);
            modelAndView.addObject("puedeVender", user.canSell());
            modelAndView.addObject("puedeConsultar", user.canConsult());
            modelAndView.addObject("puedeOperar", user.canOperate());
        }
    }

    private boolean allowed(AuthUser user, String path) {
        if (path.startsWith("/consultas/nueva")) {
            return user.canConsult();
        }
        if (path.startsWith("/operaciones/nueva")) {
            return user.canOperate();
        }
        if (path.startsWith("/consultas")) {
            return user.canConsult();
        }
        if (path.startsWith("/operaciones")) {
            return user.canOperate();
        }
        if (path.startsWith("/mascotas/venta") || path.startsWith("/productos/venta")) {
            return user.canSell();
        }
        return true;
    }

    private AuthUser currentUser(HttpSession session) {
        return session == null ? null : (AuthUser) session.getAttribute(SESSION_USER);
    }
}
