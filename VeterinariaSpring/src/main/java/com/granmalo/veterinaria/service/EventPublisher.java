package com.granmalo.veterinaria.service;

import com.granmalo.veterinaria.model.OperacionEvent;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.amqp.rabbit.core.RabbitTemplate;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;

@Service
public class EventPublisher {

    private static final Logger log = LoggerFactory.getLogger(EventPublisher.class);

    private final RabbitTemplate rabbitTemplate;
    private final String exchange;
    private final String routingKey;

    public EventPublisher(RabbitTemplate rabbitTemplate,
                          @Value("${app.rabbit.exchange}") String exchange,
                          @Value("${app.rabbit.routing-key}") String routingKey) {
        this.rabbitTemplate = rabbitTemplate;
        this.exchange = exchange;
        this.routingKey = routingKey;
    }

    public void publish(OperacionEvent event) {
        try {
            rabbitTemplate.convertAndSend(exchange, routingKey, event);
        } catch (RuntimeException ex) {
            log.warn("No se pudo publicar evento en RabbitMQ: {}", ex.getMessage());
        }
    }
}
