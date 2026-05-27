package com.granmalo.veterinaria.model;

import java.io.Serializable;
import java.math.BigDecimal;
import java.util.UUID;

public class CartItem implements Serializable {

    public enum Kind {
        ANIMAL,
        PRODUCTO,
        CONSULTA,
        CIRUGIA
    }

    private final String id = UUID.randomUUID().toString();
    private Kind kind;
    private long animalId;
    private String animalNombre;
    private Long productoId;
    private String productoNombre;
    private Long clienteId;
    private String clienteNombre;
    private long empleadoId;
    private String empleadoNombre;
    private String metodoPago;
    private String tipoOperacion;
    private String motivo;
    private String diagnostico;
    private String tratamiento;
    private String tipoCirugia;
    private String descripcion;
    private int cantidad = 1;
    private BigDecimal precio = BigDecimal.ZERO;

    public String getId() {
        return id;
    }

    public Kind getKind() {
        return kind;
    }

    public void setKind(Kind kind) {
        this.kind = kind;
    }

    public long getAnimalId() {
        return animalId;
    }

    public void setAnimalId(long animalId) {
        this.animalId = animalId;
    }

    public String getAnimalNombre() {
        return animalNombre;
    }

    public void setAnimalNombre(String animalNombre) {
        this.animalNombre = animalNombre;
    }

    public Long getProductoId() {
        return productoId;
    }

    public void setProductoId(Long productoId) {
        this.productoId = productoId;
    }

    public String getProductoNombre() {
        return productoNombre;
    }

    public void setProductoNombre(String productoNombre) {
        this.productoNombre = productoNombre;
    }

    public Long getClienteId() {
        return clienteId;
    }

    public void setClienteId(Long clienteId) {
        this.clienteId = clienteId;
    }

    public String getClienteNombre() {
        return clienteNombre;
    }

    public void setClienteNombre(String clienteNombre) {
        this.clienteNombre = clienteNombre;
    }

    public long getEmpleadoId() {
        return empleadoId;
    }

    public void setEmpleadoId(long empleadoId) {
        this.empleadoId = empleadoId;
    }

    public String getEmpleadoNombre() {
        return empleadoNombre;
    }

    public void setEmpleadoNombre(String empleadoNombre) {
        this.empleadoNombre = empleadoNombre;
    }

    public String getMetodoPago() {
        return metodoPago;
    }

    public void setMetodoPago(String metodoPago) {
        this.metodoPago = metodoPago;
    }

    public String getTipoOperacion() {
        return tipoOperacion;
    }

    public void setTipoOperacion(String tipoOperacion) {
        this.tipoOperacion = tipoOperacion;
    }

    public String getMotivo() {
        return motivo;
    }

    public void setMotivo(String motivo) {
        this.motivo = motivo;
    }

    public String getDiagnostico() {
        return diagnostico;
    }

    public void setDiagnostico(String diagnostico) {
        this.diagnostico = diagnostico;
    }

    public String getTratamiento() {
        return tratamiento;
    }

    public void setTratamiento(String tratamiento) {
        this.tratamiento = tratamiento;
    }

    public String getTipoCirugia() {
        return tipoCirugia;
    }

    public void setTipoCirugia(String tipoCirugia) {
        this.tipoCirugia = tipoCirugia;
    }

    public String getDescripcion() {
        return descripcion;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    public int getCantidad() {
        return cantidad;
    }

    public void setCantidad(int cantidad) {
        this.cantidad = cantidad;
    }

    public BigDecimal getPrecio() {
        return precio;
    }

    public void setPrecio(BigDecimal precio) {
        this.precio = precio;
    }

    public BigDecimal getTotal() {
        return precio == null ? BigDecimal.ZERO : precio.multiply(BigDecimal.valueOf(cantidad));
    }

    public String getResumen() {
        return switch (kind) {
            case ANIMAL -> tipoOperacion + " de mascota: " + animalNombre;
            case PRODUCTO -> "Venta de producto: " + productoNombre + " x" + cantidad;
            case CONSULTA -> "Consulta: " + animalNombre + " - " + motivo;
            case CIRUGIA -> "Cirugía: " + animalNombre + " - " + tipoCirugia;
        };
    }
}
