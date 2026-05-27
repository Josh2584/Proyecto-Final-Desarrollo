package com.granmalo.veterinaria.service;

import com.granmalo.veterinaria.model.*;
import org.springframework.cache.annotation.CacheEvict;
import org.springframework.cache.annotation.Cacheable;
import org.springframework.jdbc.core.namedparam.MapSqlParameterSource;
import org.springframework.jdbc.core.namedparam.NamedParameterJdbcTemplate;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.sql.Date;
import java.time.Instant;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

@Service
public class VeterinariaService {

    private static final DateTimeFormatter DATE = DateTimeFormatter.ofPattern("dd/MM/yyyy");

    private final NamedParameterJdbcTemplate jdbc;
    private final EventPublisher eventPublisher;

    public VeterinariaService(NamedParameterJdbcTemplate jdbc, EventPublisher eventPublisher) {
        this.jdbc = jdbc;
        this.eventPublisher = eventPublisher;
    }

    @Cacheable("dashboard")
    public DashboardStats dashboard() {
        return new DashboardStats(
                count("ANIMAL"),
                count("ANIMAL WHERE Cliente_Id IS NULL"),
                count("PRODUCTOS"),
                count("CLIENTE"),
                count("CONSULTA"),
                count("CIRUGIA")
        );
    }

    @Cacheable("categoriasAnimales")
    public List<Categoria> categoriasAnimales() {
        return jdbc.query("""
                SELECT CategoriaA_Id, Nombre_Categoria_Animal
                FROM CATEGORIA_ANIMALES
                ORDER BY Nombre_Categoria_Animal
                """, Map.of(), (rs, row) -> new Categoria(
                rs.getLong("CategoriaA_Id"),
                rs.getString("Nombre_Categoria_Animal")
        ));
    }

    @Cacheable("categoriasProductos")
    public List<Categoria> categoriasProductos() {
        return jdbc.query("""
                SELECT CategoriaP_Id, Nombre_Categoria_Producto
                FROM CATEGORIA_PRODUCTO
                ORDER BY Nombre_Categoria_Producto
                """, Map.of(), (rs, row) -> new Categoria(
                rs.getLong("CategoriaP_Id"),
                rs.getString("Nombre_Categoria_Producto")
        ));
    }

    public List<AnimalDisponible> animalesDisponibles(String nombre, String genero, Long categoria) {
        StringBuilder sql = new StringBuilder("""
                SELECT A.Animal_Id, A.Nombre_Animal, A.Genero, A.Color, A.Precio_Lista,
                       A.Fecha_Nacimiento, R.Nombre_Raza, CA.Nombre_Categoria_Animal
                FROM ANIMAL A
                LEFT JOIN RAZA R ON A.Raza_Id = R.Raza_Id
                LEFT JOIN CATEGORIA_ANIMALES CA ON R.CategoriaA_Id = CA.CategoriaA_Id
                WHERE A.Cliente_Id IS NULL
                """);
        MapSqlParameterSource params = new MapSqlParameterSource();
        if (hasText(nombre)) {
            sql.append(" AND UPPER(A.Nombre_Animal) LIKE UPPER(:nombre)");
            params.addValue("nombre", "%" + nombre.trim() + "%");
        }
        if (hasText(genero)) {
            sql.append(" AND A.Genero = :genero");
            params.addValue("genero", genero);
        }
        if (categoria != null) {
            sql.append(" AND CA.CategoriaA_Id = :categoria");
            params.addValue("categoria", categoria);
        }
        sql.append(" ORDER BY A.Nombre_Animal");
        return jdbc.query(sql.toString(), params, (rs, row) -> new AnimalDisponible(
                rs.getLong("Animal_Id"),
                rs.getString("Nombre_Animal"),
                rs.getString("Genero"),
                rs.getString("Color"),
                rs.getBigDecimal("Precio_Lista"),
                localDate(rs.getDate("Fecha_Nacimiento")),
                rs.getString("Nombre_Raza"),
                rs.getString("Nombre_Categoria_Animal")
        ));
    }

    public List<Producto> productos(String buscar, Long categoria, String receta) {
        StringBuilder sql = new StringBuilder("""
                SELECT P.Producto_Id, P.Descripcion_Producto, P.Precio_Lista, P.Existencia,
                       P.Requiere_Receta, CP.Nombre_Categoria_Producto
                FROM PRODUCTOS P
                LEFT JOIN CATEGORIA_PRODUCTO CP ON P.CategoriaP_Id = CP.CategoriaP_Id
                WHERE 1 = 1
                """);
        MapSqlParameterSource params = new MapSqlParameterSource();
        if (hasText(buscar)) {
            sql.append(" AND UPPER(P.Descripcion_Producto) LIKE UPPER(:buscar)");
            params.addValue("buscar", "%" + buscar.trim() + "%");
        }
        if (categoria != null) {
            sql.append(" AND P.CategoriaP_Id = :categoria");
            params.addValue("categoria", categoria);
        }
        if (hasText(receta)) {
            sql.append(" AND P.Requiere_Receta = :receta");
            params.addValue("receta", receta);
        }
        sql.append(" ORDER BY P.Descripcion_Producto");
        return jdbc.query(sql.toString(), params, (rs, row) -> new Producto(
                rs.getLong("Producto_Id"),
                rs.getString("Descripcion_Producto"),
                rs.getBigDecimal("Precio_Lista"),
                rs.getInt("Existencia"),
                rs.getString("Requiere_Receta"),
                rs.getString("Nombre_Categoria_Producto")
        ));
    }

    public List<Consulta> consultas(String buscar) {
        String sql = """
                SELECT C.Consulta_Id, C.Fecha, C.Motivo, C.Diagnostico, C.Tratamiento,
                       A.Nombre_Animal, E.Nombre_Empleado, C.Costo_Consulta
                FROM CONSULTA C
                LEFT JOIN ANIMAL A ON C.Animal_Id = A.Animal_Id
                LEFT JOIN EMPLEADO E ON C.Empleado_Id = E.Empleado_Id
                """;
        MapSqlParameterSource params = new MapSqlParameterSource();
        if (hasText(buscar)) {
            sql += """
                    WHERE UPPER(C.Motivo) LIKE UPPER(:buscarMotivo)
                       OR UPPER(C.Diagnostico) LIKE UPPER(:buscarDiagnostico)
                       OR UPPER(A.Nombre_Animal) LIKE UPPER(:buscarAnimal)
                    """;
            String like = "%" + buscar.trim() + "%";
            params.addValue("buscarMotivo", like);
            params.addValue("buscarDiagnostico", like);
            params.addValue("buscarAnimal", like);
        }
        sql += """
                ORDER BY C.Fecha DESC
                """;
        return jdbc.query(sql, params, (rs, row) -> new Consulta(
                rs.getLong("Consulta_Id"),
                localDate(rs.getDate("Fecha")),
                rs.getString("Motivo"),
                rs.getString("Diagnostico"),
                rs.getString("Tratamiento"),
                rs.getString("Nombre_Animal"),
                rs.getString("Nombre_Empleado"),
                rs.getBigDecimal("Costo_Consulta")
        ));
    }

    public List<Cirugia> cirugias(String buscar) {
        String sql = """
                SELECT CG.Cirugia_Id, CG.Fecha, CG.Tipo_Cirugia, CG.Descripcion,
                       A.Nombre_Animal, E.Nombre_Empleado, CG.Costo_Cirugia
                FROM CIRUGIA CG
                LEFT JOIN ANIMAL A ON CG.Animal_Id = A.Animal_Id
                LEFT JOIN EMPLEADO E ON CG.Empleado_Id = E.Empleado_Id
                """;
        MapSqlParameterSource params = new MapSqlParameterSource();
        if (hasText(buscar)) {
            sql += """
                    WHERE UPPER(CG.Tipo_Cirugia) LIKE UPPER(:buscarTipo)
                       OR UPPER(CG.Descripcion) LIKE UPPER(:buscarDescripcion)
                       OR UPPER(A.Nombre_Animal) LIKE UPPER(:buscarAnimal)
                    """;
            String like = "%" + buscar.trim() + "%";
            params.addValue("buscarTipo", like);
            params.addValue("buscarDescripcion", like);
            params.addValue("buscarAnimal", like);
        }
        sql += """
                ORDER BY CG.Fecha DESC
                """;
        return jdbc.query(sql, params, (rs, row) -> new Cirugia(
                rs.getLong("Cirugia_Id"),
                localDate(rs.getDate("Fecha")),
                rs.getString("Tipo_Cirugia"),
                rs.getString("Descripcion"),
                rs.getString("Nombre_Animal"),
                rs.getString("Nombre_Empleado"),
                rs.getBigDecimal("Costo_Cirugia")
        ));
    }

    public List<Opcion> animalesParaFormulario() {
        return jdbc.query("""
                SELECT A.Animal_Id, A.Nombre_Animal, A.Precio_Lista, R.Nombre_Raza
                FROM ANIMAL A LEFT JOIN RAZA R ON A.Raza_Id = R.Raza_Id
                WHERE A.Cliente_Id IS NULL
                ORDER BY A.Nombre_Animal
                """, Map.of(), (rs, row) -> new Opcion(
                rs.getLong("Animal_Id"),
                rs.getString("Nombre_Animal"),
                rs.getString("Nombre_Raza"),
                rs.getBigDecimal("Precio_Lista"),
                null
        ));
    }

    public List<Opcion> todosAnimales() {
        return opciones("SELECT Animal_Id, Nombre_Animal FROM ANIMAL ORDER BY Nombre_Animal", "Animal_Id", "Nombre_Animal");
    }

    public List<AnimalClienteOpcion> animalesConPropietario(Long clienteId) {
        String sql = """
                SELECT Animal_Id, Nombre_Animal, Cliente_Id
                FROM ANIMAL
                WHERE Cliente_Id IS NOT NULL
                """;
        MapSqlParameterSource params = new MapSqlParameterSource();
        if (clienteId != null) {
            sql += " AND Cliente_Id = :clienteId";
            params.addValue("clienteId", clienteId);
        }
        sql += " ORDER BY Nombre_Animal";
        return jdbc.query(sql, params, (rs, row) -> new AnimalClienteOpcion(
                rs.getLong("Animal_Id"),
                rs.getString("Nombre_Animal"),
                rs.getLong("Cliente_Id")
        ));
    }

    public List<Opcion> clientes() {
        return opciones("SELECT Cliente_Id, Nombre_Cliente FROM CLIENTE ORDER BY Nombre_Cliente", "Cliente_Id", "Nombre_Cliente");
    }

    public List<Opcion> empleados() {
        return jdbc.query("""
                SELECT Empleado_Id, Nombre_Empleado, Especialidad
                FROM EMPLEADO
                ORDER BY Nombre_Empleado
                """, Map.of(), (rs, row) -> new Opcion(
                rs.getLong("Empleado_Id"),
                rs.getString("Nombre_Empleado"),
                rs.getString("Especialidad"),
                null,
                null
        ));
    }

    public List<Opcion> empleadosPorNombres(List<String> nombres) {
        if (nombres == null || nombres.isEmpty()) {
            return empleados();
        }
        return jdbc.query("""
                SELECT Empleado_Id, Nombre_Empleado, Especialidad
                FROM EMPLEADO
                WHERE Nombre_Empleado IN (:nombres)
                ORDER BY Nombre_Empleado
                """, Map.of("nombres", nombres), (rs, row) -> new Opcion(
                rs.getLong("Empleado_Id"),
                rs.getString("Nombre_Empleado"),
                rs.getString("Especialidad"),
                null,
                null
        ));
    }

    public List<Opcion> productosConStock() {
        return jdbc.query("""
                SELECT Producto_Id, Descripcion_Producto, Precio_Lista, Existencia
                FROM PRODUCTOS
                WHERE Existencia > 0
                ORDER BY Descripcion_Producto
                """, Map.of(), (rs, row) -> new Opcion(
                rs.getLong("Producto_Id"),
                rs.getString("Descripcion_Producto"),
                null,
                rs.getBigDecimal("Precio_Lista"),
                rs.getInt("Existencia")
        ));
    }

    public String animalNombre(long animalId) {
        return nombre("ANIMAL", "Animal_Id", "Nombre_Animal", animalId);
    }

    public String clienteNombre(long clienteId) {
        return nombre("CLIENTE", "Cliente_Id", "Nombre_Cliente", clienteId);
    }

    public String empleadoNombre(long empleadoId) {
        return nombre("EMPLEADO", "Empleado_Id", "Nombre_Empleado", empleadoId);
    }

    public Producto producto(long productoId) {
        return productoPorId(productoId);
    }

    @Transactional
    @CacheEvict(value = {"dashboard"}, allEntries = true)
    public Ticket finalizarCarrito(Cart cart) {
        if (cart == null || cart.isEmpty()) {
            throw new IllegalArgumentException("El carrito está vacío.");
        }

        validarCarrito(cart);

        Ticket ticket = new Ticket("Ticket de carrito", 0, "/", cart.getTotal());
        int index = 1;
        for (CartItem item : cart.getItems()) {
            Ticket itemTicket = switch (item.getKind()) {
                case ANIMAL -> registrarVentaMascota(
                        item.getAnimalId(),
                        item.getClienteId(),
                        item.getEmpleadoId(),
                        item.getMetodoPago(),
                        item.getTipoOperacion(),
                        item.getPrecio()
                );
                case PRODUCTO -> registrarVentaProducto(
                        item.getProductoId(),
                        item.getClienteId(),
                        item.getEmpleadoId(),
                        item.getMetodoPago(),
                        item.getCantidad(),
                        item.getPrecio()
                );
                case CONSULTA -> registrarConsulta(
                        item.getAnimalId(),
                        item.getEmpleadoId(),
                        item.getMotivo(),
                        item.getDiagnostico(),
                        item.getTratamiento(),
                        item.getPrecio()
                );
                case CIRUGIA -> registrarCirugia(
                        item.getAnimalId(),
                        item.getEmpleadoId(),
                        item.getTipoCirugia(),
                        item.getDescripcion(),
                        item.getPrecio()
                );
            };
            ticket.dato("Operación " + index, item.getResumen() + " | Folio #" + itemTicket.getFolio() + " | " + money(item.getTotal()));
            index++;
        }
        return ticket;
    }

    private void validarCarrito(Cart cart) {
        Map<Long, Integer> productosSolicitados = new HashMap<>();
        Map<Long, Integer> animalesEnVenta = new HashMap<>();
        Map<Long, Integer> consultasPorAnimal = new HashMap<>();
        Map<Long, Integer> cirugiasPorAnimal = new HashMap<>();

        for (CartItem item : cart.getItems()) {
            switch (item.getKind()) {
                case ANIMAL -> {
                    validarAnimalDisponible(item.getAnimalId());
                    animalesEnVenta.merge(item.getAnimalId(), 1, Integer::sum);
                }
                case PRODUCTO -> productosSolicitados.merge(item.getProductoId(), item.getCantidad(), Integer::sum);
                case CONSULTA -> {
                    validarAnimalPerteneceCliente(item.getAnimalId(), item.getClienteId());
                    consultasPorAnimal.merge(item.getAnimalId(), 1, Integer::sum);
                }
                case CIRUGIA -> {
                    validarAnimalPerteneceCliente(item.getAnimalId(), item.getClienteId());
                    cirugiasPorAnimal.merge(item.getAnimalId(), 1, Integer::sum);
                }
            }
        }

        for (Map.Entry<Long, Integer> entry : animalesEnVenta.entrySet()) {
            if (entry.getValue() > 1) {
                throw new IllegalArgumentException("El animal " + animalNombre(entry.getKey())
                        + " está repetido en el carrito.");
            }
        }

        for (Map.Entry<Long, Integer> entry : productosSolicitados.entrySet()) {
            Producto producto = productoPorId(entry.getKey());
            if (producto.existencia() < entry.getValue()) {
                throw new IllegalArgumentException("No hay suficiente existencia de " + producto.descripcion()
                        + ". Disponible: " + producto.existencia() + ", solicitado: " + entry.getValue());
            }
        }

        for (Map.Entry<Long, Integer> entry : cirugiasPorAnimal.entrySet()) {
            if (entry.getValue() > 1 || cirugiasHoy(entry.getKey()) > 0) {
                throw new IllegalArgumentException("El animal " + animalNombre(entry.getKey())
                        + " ya tiene una cirugía registrada o programada para hoy.");
            }
        }

        for (Map.Entry<Long, Integer> entry : consultasPorAnimal.entrySet()) {
            int totalHoy = consultasHoy(entry.getKey()) + entry.getValue();
            if (totalHoy > 2) {
                throw new IllegalArgumentException("El animal " + animalNombre(entry.getKey())
                        + " no puede tener más de 2 consultas en el mismo día.");
            }
        }
    }

    private void validarAnimalDisponible(long animalId) {
        Integer disponible = jdbc.queryForObject("""
                SELECT COUNT(*)
                FROM ANIMAL
                WHERE Animal_Id = :animalId
                  AND Cliente_Id IS NULL
                """, Map.of("animalId", animalId), Integer.class);
        if (disponible == null || disponible == 0) {
            throw new IllegalArgumentException("El animal " + animalNombre(animalId) + " ya no está disponible.");
        }
    }

    public void validarAnimalPerteneceCliente(long animalId, Long clienteId) {
        if (clienteId == null) {
            throw new IllegalArgumentException("Selecciona un cliente para el animal " + animalNombre(animalId) + ".");
        }
        Integer pertenece = jdbc.queryForObject("""
                SELECT COUNT(*)
                FROM ANIMAL
                WHERE Animal_Id = :animalId
                  AND Cliente_Id = :clienteId
                """, Map.of("animalId", animalId, "clienteId", clienteId), Integer.class);
        if (pertenece == null || pertenece == 0) {
            throw new IllegalArgumentException("El animal " + animalNombre(animalId)
                    + " no pertenece al cliente " + clienteNombre(clienteId) + ".");
        }
    }

    private int cirugiasHoy(long animalId) {
        Integer count = jdbc.queryForObject("""
                SELECT COUNT(*)
                FROM CIRUGIA
                WHERE Animal_Id = :animalId
                  AND TRUNC(Fecha) = TRUNC(SYSDATE)
                """, Map.of("animalId", animalId), Integer.class);
        return count == null ? 0 : count;
    }

    private int consultasHoy(long animalId) {
        Integer count = jdbc.queryForObject("""
                SELECT COUNT(*)
                FROM CONSULTA
                WHERE Animal_Id = :animalId
                  AND TRUNC(Fecha) = TRUNC(SYSDATE)
                """, Map.of("animalId", animalId), Integer.class);
        return count == null ? 0 : count;
    }

    @Transactional
    @CacheEvict(value = {"dashboard"}, allEntries = true)
    public Ticket registrarConsulta(long animalId, long empleadoId, String motivo, String diagnostico, String tratamiento, BigDecimal costo) {
        long id = nextId("CONSULTA", "Consulta_Id");
        jdbc.update("""
                INSERT INTO CONSULTA (Consulta_Id, Fecha, Motivo, Diagnostico, Tratamiento, Costo_Consulta, Empleado_Id, Animal_Id, Venta_Id)
                VALUES (:id, SYSDATE, :motivo, :diagnostico, :tratamiento, :costo, :empleadoId, :animalId, NULL)
                """, new MapSqlParameterSource()
                .addValue("id", id)
                .addValue("motivo", motivo)
                .addValue("diagnostico", diagnostico)
                .addValue("tratamiento", tratamiento)
                .addValue("costo", costo)
                .addValue("empleadoId", empleadoId)
                .addValue("animalId", animalId));
        eventPublisher.publish(new OperacionEvent("Consulta veterinaria", id, costo, Instant.now()));
        return new Ticket("Consulta veterinaria", id, "/consultas", null)
                .dato("Animal", nombre("ANIMAL", "Animal_Id", "Nombre_Animal", animalId))
                .dato("Veterinario", nombre("EMPLEADO", "Empleado_Id", "Nombre_Empleado", empleadoId))
                .dato("Motivo", motivo)
                .dato("Diagnostico", diagnostico)
                .dato("Tratamiento", tratamiento)
                .dato("Costo", money(costo))
                .dato("Fecha", LocalDate.now().format(DATE));
    }

    @Transactional
    @CacheEvict(value = {"dashboard"}, allEntries = true)
    public Ticket registrarCirugia(long animalId, long empleadoId, String tipoCirugia, String descripcion, BigDecimal costo) {
        long id = nextId("CIRUGIA", "Cirugia_Id");
        jdbc.update("""
                INSERT INTO CIRUGIA (Cirugia_Id, Fecha, Tipo_Cirugia, Descripcion, Costo_Cirugia, Animal_Id, Empleado_Id, Venta_Id)
                VALUES (:id, SYSDATE, :tipo, :descripcion, :costo, :animalId, :empleadoId, NULL)
                """, new MapSqlParameterSource()
                .addValue("id", id)
                .addValue("tipo", tipoCirugia)
                .addValue("descripcion", descripcion)
                .addValue("costo", costo)
                .addValue("animalId", animalId)
                .addValue("empleadoId", empleadoId));
        eventPublisher.publish(new OperacionEvent("Cirugia registrada", id, costo, Instant.now()));
        return new Ticket("Cirugia registrada", id, "/operaciones", null)
                .dato("Animal", nombre("ANIMAL", "Animal_Id", "Nombre_Animal", animalId))
                .dato("Cirujano", nombre("EMPLEADO", "Empleado_Id", "Nombre_Empleado", empleadoId))
                .dato("Tipo de cirugia", tipoCirugia)
                .dato("Descripcion", descripcion)
                .dato("Costo", money(costo))
                .dato("Fecha", LocalDate.now().format(DATE));
    }

    @Transactional
    @CacheEvict(value = {"dashboard"}, allEntries = true)
    public Ticket registrarVentaMascota(long animalId, long clienteId, long empleadoId, String metodoPago, String tipo, BigDecimal precio) {
        long ventaId = nextId("VENTAS", "Venta_Id");
        jdbc.update("INSERT INTO VENTAS VALUES (:id, SYSDATE, :clienteId, :empleadoId, :metodo, :tipo)",
                new MapSqlParameterSource()
                        .addValue("id", ventaId)
                        .addValue("clienteId", clienteId)
                        .addValue("empleadoId", empleadoId)
                        .addValue("metodo", metodoPago)
                        .addValue("tipo", tipo));
        jdbc.update("INSERT INTO VENTAS_ANIMAL VALUES (:animalId, :ventaId, :precio)",
                new MapSqlParameterSource().addValue("animalId", animalId).addValue("ventaId", ventaId).addValue("precio", precio));
        jdbc.update("UPDATE ANIMAL SET Cliente_Id = :clienteId WHERE Animal_Id = :animalId",
                new MapSqlParameterSource().addValue("clienteId", clienteId).addValue("animalId", animalId));
        eventPublisher.publish(new OperacionEvent(tipo, ventaId, precio, Instant.now()));
        return new Ticket("Adopcion".equals(tipo) ? "Adopcion de mascota" : "Venta de mascota", ventaId, "/mascotas", precio)
                .dato("Animal", nombre("ANIMAL", "Animal_Id", "Nombre_Animal", animalId))
                .dato("Cliente", nombre("CLIENTE", "Cliente_Id", "Nombre_Cliente", clienteId))
                .dato("Atendido por", nombre("EMPLEADO", "Empleado_Id", "Nombre_Empleado", empleadoId))
                .dato("Tipo", tipo)
                .dato("Metodo de pago", metodoPago)
                .dato("Precio", money(precio));
    }

    @Transactional
    @CacheEvict(value = {"dashboard"}, allEntries = true)
    public Ticket registrarVentaProducto(long productoId, long clienteId, long empleadoId, String metodoPago, int cantidad, BigDecimal precio) {
        Producto producto = productoPorId(productoId);
        if (producto.existencia() < cantidad) {
            throw new IllegalArgumentException("No hay suficiente existencia. Disponible: " + producto.existencia());
        }
        long ventaId = nextId("VENTAS", "Venta_Id");
        BigDecimal total = precio.multiply(BigDecimal.valueOf(cantidad));
        jdbc.update("INSERT INTO VENTAS VALUES (:id, SYSDATE, :clienteId, :empleadoId, :metodo, 'Venta producto')",
                new MapSqlParameterSource()
                        .addValue("id", ventaId)
                        .addValue("clienteId", clienteId)
                        .addValue("empleadoId", empleadoId)
                        .addValue("metodo", metodoPago));
        jdbc.update("INSERT INTO VENTAS_PRODUCTO VALUES (:ventaId, :productoId, :precio, :cantidad)",
                new MapSqlParameterSource()
                        .addValue("ventaId", ventaId)
                        .addValue("productoId", productoId)
                        .addValue("precio", precio)
                        .addValue("cantidad", cantidad));
        jdbc.update("UPDATE PRODUCTOS SET Existencia = Existencia - :cantidad WHERE Producto_Id = :productoId",
                new MapSqlParameterSource().addValue("cantidad", cantidad).addValue("productoId", productoId));
        eventPublisher.publish(new OperacionEvent("Venta de producto", ventaId, total, Instant.now()));
        return new Ticket("Venta de producto", ventaId, "/productos", total)
                .dato("Producto", producto.descripcion())
                .dato("Cantidad", cantidad + " unidad(es)")
                .dato("Precio unitario", money(precio))
                .dato("Cliente", nombre("CLIENTE", "Cliente_Id", "Nombre_Cliente", clienteId))
                .dato("Atendido por", nombre("EMPLEADO", "Empleado_Id", "Nombre_Empleado", empleadoId))
                .dato("Metodo de pago", metodoPago)
                .dato("Total", money(total));
    }

    private Producto productoPorId(long productoId) {
        return jdbc.queryForObject("""
                SELECT P.Producto_Id, P.Descripcion_Producto, P.Precio_Lista, P.Existencia,
                       P.Requiere_Receta, CP.Nombre_Categoria_Producto
                FROM PRODUCTOS P LEFT JOIN CATEGORIA_PRODUCTO CP ON P.CategoriaP_Id = CP.CategoriaP_Id
                WHERE P.Producto_Id = :id
                """, Map.of("id", productoId), (rs, row) -> new Producto(
                rs.getLong("Producto_Id"),
                rs.getString("Descripcion_Producto"),
                rs.getBigDecimal("Precio_Lista"),
                rs.getInt("Existencia"),
                rs.getString("Requiere_Receta"),
                rs.getString("Nombre_Categoria_Producto")
        ));
    }

    private int count(String tableAndWhere) {
        Integer value = jdbc.queryForObject("SELECT COUNT(*) FROM " + tableAndWhere, Map.of(), Integer.class);
        return value == null ? 0 : value;
    }

    private long nextId(String table, String column) {
        Long value = jdbc.queryForObject("SELECT NVL(MAX(" + column + "),0)+1 FROM " + table, Map.of(), Long.class);
        return value == null ? 1 : value;
    }

    private String nombre(String table, String idColumn, String nameColumn, long id) {
        String sql = "SELECT " + nameColumn + " FROM " + table + " WHERE " + idColumn + " = :id";
        List<String> names = jdbc.query(sql, Map.of("id", id), (rs, row) -> rs.getString(nameColumn));
        return names.isEmpty() ? "-" : names.get(0);
    }

    private List<Opcion> opciones(String sql, String idColumn, String nameColumn) {
        return jdbc.query(sql, Map.of(), (rs, row) -> new Opcion(
                rs.getLong(idColumn),
                rs.getString(nameColumn),
                null,
                null,
                null
        ));
    }

    private static LocalDate localDate(Date date) {
        return date == null ? null : date.toLocalDate();
    }

    private static boolean hasText(String value) {
        return value != null && !value.trim().isEmpty();
    }

    private static String money(BigDecimal value) {
        return value == null ? "-" : "$" + value.setScale(2).toPlainString();
    }
}
