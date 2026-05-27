package com.granmalo.veterinaria.controller;

import com.granmalo.veterinaria.model.Cart;
import com.granmalo.veterinaria.model.CartItem;
import com.granmalo.veterinaria.model.Producto;
import com.granmalo.veterinaria.model.AuthUser;
import com.granmalo.veterinaria.model.Role;
import com.granmalo.veterinaria.model.Ticket;
import com.granmalo.veterinaria.config.AuthInterceptor;
import com.granmalo.veterinaria.service.AuthService;
import com.granmalo.veterinaria.service.VeterinariaService;
import jakarta.servlet.http.HttpSession;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

@Controller
public class WebController {

    private final VeterinariaService service;
    private final AuthService authService;

    public WebController(VeterinariaService service, AuthService authService) {
        this.service = service;
        this.authService = authService;
    }

    @GetMapping("/login")
    public String login(HttpSession session, Model model) {
        if (usuario(session) != null) {
            return "redirect:/";
        }
        return "login";
    }

    @PostMapping("/login")
    public String autenticar(@RequestParam String username,
                             @RequestParam String password,
                             HttpSession session,
                             Model model) {
        Optional<AuthUser> user = authService.login(username, password);
        if (user.isPresent()) {
            session.setAttribute(AuthInterceptor.SESSION_USER, user.get());
            return "redirect:/";
        }
        model.addAttribute("error", "Usuario o contraseña inválidos.");
        model.addAttribute("username", username);
        return "login";
    }

    @PostMapping("/logout")
    public String logout(HttpSession session) {
        session.invalidate();
        return "redirect:/login";
    }

    @GetMapping("/")
    public String inicio(Model model) {
        model.addAttribute("pagina", "index");
        model.addAttribute("stats", service.dashboard());
        return "index";
    }

    @GetMapping("/mascotas")
    public String mascotas(@RequestParam(required = false) String nombre,
                           @RequestParam(required = false) String genero,
                           @RequestParam(required = false) Long categoria,
                           Model model) {
        model.addAttribute("pagina", "mascotas");
        model.addAttribute("nombre", nombre);
        model.addAttribute("genero", genero);
        model.addAttribute("categoriaSeleccionada", categoria);
        model.addAttribute("categorias", service.categoriasAnimales());
        model.addAttribute("animales", service.animalesDisponibles(nombre, genero, categoria));
        return "mascotas";
    }

    @GetMapping("/productos")
    public String productos(@RequestParam(required = false) String buscar,
                            @RequestParam(required = false) Long categoria,
                            @RequestParam(required = false) String receta,
                            Model model) {
        List<Producto> productos = service.productos(buscar, categoria, receta);
        model.addAttribute("pagina", "productos");
        model.addAttribute("buscar", buscar);
        model.addAttribute("categoriaSeleccionada", categoria);
        model.addAttribute("receta", receta);
        model.addAttribute("categorias", service.categoriasProductos());
        model.addAttribute("productos", productos);
        model.addAttribute("sinStock", productos.stream().filter(p -> p.existencia() == 0).count());
        model.addAttribute("requierenReceta", productos.stream().filter(p -> "S".equals(p.requiereReceta())).count());
        return "productos";
    }

    @GetMapping("/consultas")
    public String consultas(@RequestParam(required = false) String buscar, Model model) {
        model.addAttribute("pagina", "consultas");
        model.addAttribute("buscar", buscar);
        model.addAttribute("consultas", service.consultas(buscar));
        return "consultas";
    }

    @GetMapping("/operaciones")
    public String operaciones(@RequestParam(required = false) String buscar, Model model) {
        model.addAttribute("pagina", "operaciones");
        model.addAttribute("buscar", buscar);
        model.addAttribute("cirugias", service.cirugias(buscar));
        return "operaciones";
    }

    @GetMapping("/consultas/nueva")
    public String nuevaConsulta(HttpSession session, Model model) {
        Long clienteId = clienteSesion(session);
        model.addAttribute("pagina", "consultas");
        model.addAttribute("animales", service.animalesConPropietario(clienteId));
        model.addAttribute("clientes", service.clientes());
        model.addAttribute("empleados", empleadosParaConsulta(session));
        agregarDatosFijadosAlModelo(session, model);
        return "nueva-consulta";
    }

    @PostMapping("/consultas/nueva")
    public String guardarConsulta(@RequestParam long animalId,
                                  @RequestParam long clienteId,
                                  @RequestParam long empleadoId,
                                  @RequestParam String metodoPago,
                                  @RequestParam String motivo,
                                  @RequestParam(required = false) String diagnostico,
                                  @RequestParam(required = false) String tratamiento,
                                  @RequestParam BigDecimal costo,
                                  HttpSession session,
                                  RedirectAttributes redirectAttributes) {
        CartItem item = new CartItem();
        service.validarAnimalPerteneceCliente(animalId, clienteId);
        item.setKind(CartItem.Kind.CONSULTA);
        item.setAnimalId(animalId);
        item.setAnimalNombre(service.animalNombre(animalId));
        item.setClienteId(clienteId);
        item.setClienteNombre(service.clienteNombre(clienteId));
        item.setEmpleadoId(empleadoId);
        item.setEmpleadoNombre(service.empleadoNombre(empleadoId));
        item.setMetodoPago(metodoPago);
        item.setMotivo(motivo);
        item.setDiagnostico(diagnostico);
        item.setTratamiento(tratamiento);
        item.setPrecio(costo);
        fijarSesionSiNuevo(session, clienteId, metodoPago);
        cart(session).add(item);
        redirectAttributes.addFlashAttribute("mensaje", "Consulta agregada al carrito.");
        return "redirect:/carrito";
    }

    @GetMapping("/operaciones/nueva")
    public String nuevaCirugia(HttpSession session, Model model) {
        Long clienteId = clienteSesion(session);
        model.addAttribute("pagina", "operaciones");
        model.addAttribute("animales", service.animalesConPropietario(clienteId));
        model.addAttribute("clientes", service.clientes());
        model.addAttribute("empleados", empleadosParaCirugia(session));
        agregarDatosFijadosAlModelo(session, model);
        return "nueva-cirugia";
    }

    @PostMapping("/operaciones/nueva")
    public String guardarCirugia(@RequestParam long animalId,
                                 @RequestParam long clienteId,
                                 @RequestParam long empleadoId,
                                 @RequestParam String metodoPago,
                                 @RequestParam String tipoCirugia,
                                 @RequestParam(required = false) String descripcion,
                                 @RequestParam BigDecimal costo,
                                 HttpSession session,
                                 RedirectAttributes redirectAttributes) {
        CartItem item = new CartItem();
        service.validarAnimalPerteneceCliente(animalId, clienteId);
        item.setKind(CartItem.Kind.CIRUGIA);
        item.setAnimalId(animalId);
        item.setAnimalNombre(service.animalNombre(animalId));
        item.setClienteId(clienteId);
        item.setClienteNombre(service.clienteNombre(clienteId));
        item.setEmpleadoId(empleadoId);
        item.setEmpleadoNombre(service.empleadoNombre(empleadoId));
        item.setMetodoPago(metodoPago);
        item.setTipoCirugia(tipoCirugia);
        item.setDescripcion(descripcion);
        item.setPrecio(costo);
        fijarSesionSiNuevo(session, clienteId, metodoPago);
        cart(session).add(item);
        redirectAttributes.addFlashAttribute("mensaje", "Cirugía agregada al carrito.");
        return "redirect:/carrito";
    }

    @GetMapping("/mascotas/venta")
    public String ventaMascota(HttpSession session, Model model) {
        model.addAttribute("pagina", "mascotas");
        model.addAttribute("animales", service.animalesParaFormulario());
        model.addAttribute("clientes", service.clientes());
        model.addAttribute("empleados", empleadosParaVenta(session));
        agregarDatosFijadosAlModelo(session, model);
        return "venta-mascota";
    }

    @PostMapping("/mascotas/venta")
    public String guardarVentaMascota(@RequestParam long animalId,
                                      @RequestParam long clienteId,
                                      @RequestParam long empleadoId,
                                      @RequestParam String metodoPago,
                                      @RequestParam String tipo,
                                      @RequestParam BigDecimal precioVenta,
                                      HttpSession session,
                                      RedirectAttributes redirectAttributes) {
        CartItem item = new CartItem();
        item.setKind(CartItem.Kind.ANIMAL);
        item.setAnimalId(animalId);
        item.setAnimalNombre(service.animalNombre(animalId));
        item.setClienteId(clienteId);
        item.setClienteNombre(service.clienteNombre(clienteId));
        item.setEmpleadoId(empleadoId);
        item.setEmpleadoNombre(service.empleadoNombre(empleadoId));
        item.setMetodoPago(metodoPago);
        item.setTipoOperacion(tipo);
        item.setPrecio(precioVenta);
        fijarSesionSiNuevo(session, clienteId, metodoPago);
        cart(session).add(item);
        redirectAttributes.addFlashAttribute("mensaje", "Mascota agregada al carrito.");
        return "redirect:/carrito";
    }

    @GetMapping("/productos/venta")
    public String ventaProducto(HttpSession session, Model model) {
        model.addAttribute("pagina", "productos");
        model.addAttribute("productos", service.productosConStock());
        model.addAttribute("clientes", service.clientes());
        model.addAttribute("empleados", empleadosParaVenta(session));
        agregarDatosFijadosAlModelo(session, model);
        return "venta-producto";
    }

    @PostMapping("/productos/venta")
    public String guardarVentaProducto(@RequestParam long productoId,
                                       @RequestParam long clienteId,
                                       @RequestParam long empleadoId,
                                       @RequestParam String metodoPago,
                                       @RequestParam int cantidad,
                                       @RequestParam BigDecimal precioVenta,
                                       HttpSession session,
                                       RedirectAttributes redirectAttributes) {
        Producto producto = service.producto(productoId);
        CartItem item = new CartItem();
        item.setKind(CartItem.Kind.PRODUCTO);
        item.setProductoId(productoId);
        item.setProductoNombre(producto.descripcion());
        item.setClienteId(clienteId);
        item.setClienteNombre(service.clienteNombre(clienteId));
        item.setEmpleadoId(empleadoId);
        item.setEmpleadoNombre(service.empleadoNombre(empleadoId));
        item.setMetodoPago(metodoPago);
        item.setCantidad(cantidad);
        item.setPrecio(precioVenta);
        fijarSesionSiNuevo(session, clienteId, metodoPago);
        cart(session).add(item);
        redirectAttributes.addFlashAttribute("mensaje", "Producto agregado al carrito.");
        return "redirect:/carrito";
    }

    @GetMapping("/carrito")
    public String carrito(HttpSession session, Model model) {
        model.addAttribute("pagina", "carrito");
        model.addAttribute("cart", cart(session));
        return "carrito";
    }

    @PostMapping("/carrito/remover")
    public String removerCarrito(@RequestParam String itemId, HttpSession session, RedirectAttributes redirectAttributes) {
        cart(session).remove(itemId);
        redirectAttributes.addFlashAttribute("mensaje", "Elemento eliminado del carrito.");
        return "redirect:/carrito";
    }

    @PostMapping("/carrito/finalizar")
    public String finalizarCarrito(HttpSession session, RedirectAttributes redirectAttributes) {
        Cart cart = cart(session);
        try {
            Ticket ticket = service.finalizarCarrito(cart);
            cart.clear();
            limpiarSesion(session);
            redirectAttributes.addFlashAttribute("ticket", ticket);
            return "redirect:/ticket";
        } catch (IllegalArgumentException ex) {
            redirectAttributes.addFlashAttribute("error", ex.getMessage());
            return "redirect:/carrito";
        }
    }

    @GetMapping("/ticket")
    public String ticket(Model model) {
        if (!model.containsAttribute("ticket")) {
            return "redirect:/";
        }
        model.addAttribute("pagina", "ticket");
        return "ticket";
    }

    private Cart cart(HttpSession session) {
        Cart cart = (Cart) session.getAttribute("cart");
        if (cart == null) {
            cart = new Cart();
            session.setAttribute("cart", cart);
        }
        return cart;
    }

    private void fijarSesionSiNuevo(HttpSession session, long clienteId, String metodoPago) {
        if (clienteSesion(session) == null) {
            session.setAttribute("clienteIdSesion", clienteId);
        }
        if (metodoPagoSesion(session) == null) {
            session.setAttribute("metodoPagoSesion", metodoPago);
        }
    }

    private Long clienteSesion(HttpSession session) {
        Object value = session.getAttribute("clienteIdSesion");
        return value instanceof Long id ? id : null;
    }

    private String metodoPagoSesion(HttpSession session) {
        Object value = session.getAttribute("metodoPagoSesion");
        return value instanceof String metodo ? metodo : null;
    }

    private void limpiarSesion(HttpSession session) {
        session.removeAttribute("clienteIdSesion");
        session.removeAttribute("metodoPagoSesion");
    }

    private void agregarDatosFijadosAlModelo(HttpSession session, Model model) {
        Long clienteId = clienteSesion(session);
        String metodoPago = metodoPagoSesion(session);
        model.addAttribute("clienteFijadoId", clienteId);
        model.addAttribute("clienteFijadoNombre", clienteId == null ? null : service.clienteNombre(clienteId));
        model.addAttribute("metodoPagoFijado", metodoPago);
    }

    private AuthUser usuario(HttpSession session) {
        return session == null ? null : (AuthUser) session.getAttribute(AuthInterceptor.SESSION_USER);
    }

    private List<com.granmalo.veterinaria.model.Opcion> empleadosParaVenta(HttpSession session) {
        AuthUser user = usuario(session);
        if (user == null || user.role() == Role.ADMIN) {
            return service.empleados();
        }
        return service.empleadosPorNombres(nombresPorRol(user.role()));
    }

    private List<com.granmalo.veterinaria.model.Opcion> empleadosParaConsulta(HttpSession session) {
        AuthUser user = usuario(session);
        if (user == null || user.role() == Role.ADMIN) {
            return service.empleados();
        }
        List<String> nombres = new ArrayList<>();
        if (user.role().level() >= Role.VETERINARIO.level()) {
            nombres.add("Jorge Antonio");
        }
        if (user.role().level() >= Role.CIRUJANO.level()) {
            nombres.addAll(nombresPorRol(Role.CIRUJANO));
        }
        return service.empleadosPorNombres(nombres);
    }

    private List<com.granmalo.veterinaria.model.Opcion> empleadosParaCirugia(HttpSession session) {
        AuthUser user = usuario(session);
        if (user == null || user.role() == Role.ADMIN) {
            return service.empleados();
        }
        return service.empleadosPorNombres(user.role().level() >= Role.CIRUJANO.level()
                ? nombresPorRol(Role.CIRUJANO)
                : List.of());
    }

    private List<String> nombresPorRol(Role role) {
        return switch (role) {
            case CAJERO -> List.of("Abdiel Ivan", "Gabriela Torres");
            case VETERINARIO -> List.of("Jorge Antonio");
            case CIRUJANO -> List.of("Joshua Alejandro", "Hugo Salinas");
            case ADMIN -> List.of();
        };
    }
}
