package com.granmalo.veterinaria.config;

import org.springframework.amqp.core.Binding;
import org.springframework.amqp.core.BindingBuilder;
import org.springframework.amqp.core.DirectExchange;
import org.springframework.amqp.core.Queue;
import org.springframework.amqp.rabbit.connection.ConnectionFactory;
import org.springframework.amqp.rabbit.core.RabbitAdmin;
import org.springframework.boot.ApplicationRunner;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

@Configuration
public class RabbitConfig {

    private static final Logger log = LoggerFactory.getLogger(RabbitConfig.class);

    @Bean
    DirectExchange veterinariaExchange(@Value("${app.rabbit.exchange}") String exchange) {
        return new DirectExchange(exchange, true, false);
    }

    @Bean
    Queue veterinariaQueue(@Value("${app.rabbit.queue}") String queue) {
        return new Queue(queue, true);
    }

    @Bean
    Binding veterinariaBinding(Queue veterinariaQueue,
                               DirectExchange veterinariaExchange,
                               @Value("${app.rabbit.routing-key}") String routingKey) {
        return BindingBuilder.bind(veterinariaQueue).to(veterinariaExchange).with(routingKey);
    }

    @Bean
    RabbitAdmin rabbitAdmin(ConnectionFactory connectionFactory) {
        RabbitAdmin rabbitAdmin = new RabbitAdmin(connectionFactory);
        rabbitAdmin.setAutoStartup(true);
        return rabbitAdmin;
    }

    @Bean
    ApplicationRunner initializeRabbit(RabbitAdmin rabbitAdmin) {
        return args -> {
            try {
                rabbitAdmin.initialize();
            } catch (RuntimeException ex) {
                log.warn("RabbitMQ no esta disponible al iniciar: {}", ex.getMessage());
            }
        };
    }
}
