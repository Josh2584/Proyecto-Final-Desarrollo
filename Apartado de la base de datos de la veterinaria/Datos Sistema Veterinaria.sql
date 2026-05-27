


--------------------------------------------------
--- CATEGORIAS DE ANIMALES
--------------------------------------------------
INSERT INTO CATEGORIA_ANIMALES VALUES (1, 'Caninos');
INSERT INTO CATEGORIA_ANIMALES VALUES (2, 'Felinos');
INSERT INTO CATEGORIA_ANIMALES VALUES (3, 'Aves');
INSERT INTO CATEGORIA_ANIMALES VALUES (4, 'Bovinos');
INSERT INTO CATEGORIA_ANIMALES VALUES (5, 'Equinos');
INSERT INTO CATEGORIA_ANIMALES VALUES (6, 'Peces');
INSERT INTO CATEGORIA_ANIMALES VALUES (7, 'Reptiles');
 
--------------------------------------------------
-- RAZAS
-- (Raza_Id, CategoriaA_Id, Nombre_Raza)
--------------------------------------------------
INSERT INTO RAZA VALUES (1,  1, 'Husky');
INSERT INTO RAZA VALUES (2,  5, 'Mustang');
INSERT INTO RAZA VALUES (3,  1, 'Corgi');
INSERT INTO RAZA VALUES (4,  5, 'Gypsy Vanner');
INSERT INTO RAZA VALUES (5,  1, 'Cazador Holandes');
INSERT INTO RAZA VALUES (6,  2, 'Siames');
INSERT INTO RAZA VALUES (7,  2, 'Persa');
INSERT INTO RAZA VALUES (8,  3, 'Ninfas');
INSERT INTO RAZA VALUES (9,  3, 'Gallina Polaca');
INSERT INTO RAZA VALUES (10, 3, 'Braham');
INSERT INTO RAZA VALUES (11, 4, 'Angus');
INSERT INTO RAZA VALUES (12, 4, 'Azul Belga');
INSERT INTO RAZA VALUES (13, 1, 'Pastor Aleman');
INSERT INTO RAZA VALUES (14, 1, 'Bulldog Frances');
INSERT INTO RAZA VALUES (15, 7, 'Piton Bola');
INSERT INTO RAZA VALUES (16, 7, 'Serpiente de Maiz');
INSERT INTO RAZA VALUES (17, 7, 'Serpiente Rey');
INSERT INTO RAZA VALUES (18, 6, 'Pez Guppy');
INSERT INTO RAZA VALUES (19, 6, 'Pez Tetra Neon');
INSERT INTO RAZA VALUES (20, 6, 'Pez Platy');
INSERT INTO RAZA VALUES (21, 6, 'Pez Betta');
INSERT INTO RAZA VALUES (22, 6, 'Pez Payaso');
INSERT INTO RAZA VALUES (23, 6, 'Pez Angel');
INSERT INTO RAZA VALUES (24, 7, 'Gecko Leopardo');
INSERT INTO RAZA VALUES (25, 7, 'Dragon Barbudo');
 
--------------------------------------------------
-- PROVEEDORES
--------------------------------------------------
INSERT INTO PROVEEDOR VALUES (1,  'Alcodeza',             'Jose Martinez',    '6141112233', 'Calle Juana 1',      'Chihuahua', 'Activo', '33110');
INSERT INTO PROVEEDOR VALUES (2,  'Delicias Chihuahua',   'Ana Ramos',        '6142223344', 'Av. Tecnologico 250',      'Chihuahua', 'Activo', '33120');
INSERT INTO PROVEEDOR VALUES (3,  'Provedosa',             'Luis Herrera',     '6143334455', 'Blvd. Ortiz Mena 88',     'Chihuahua', 'Activo', '33130');
INSERT INTO PROVEEDOR VALUES (4,  'Mascotas Norte',        'Sofia Delgado',    '6144445566', 'Calle Independencia 340', 'Chihuahua', 'Activo', '33140');
INSERT INTO PROVEEDOR VALUES (5,  'AgroVet Chihuahua',    'Carlos Ibarra',    '6145556677', 'Av. Universidad 512',     'Chihuahua', 'Activo', '33150');
INSERT INTO PROVEEDOR VALUES (6,  'Equinos del Norte',    'Monica Valles',    '6146667788', 'Calle Aljama 77',         'Chihuahua', 'Activo', '33160');
INSERT INTO PROVEEDOR VALUES (7,  'ReptiHouse Chihuahua', 'Roberto Solis',    '6147778899', 'Calle Oprecion 190',      'Chihuahua', 'Activo', '33170');
INSERT INTO PROVEEDOR VALUES (8,  'Acuarios Capital',     'Diana Fuentes',    '6148889900', 'Blvd. Diaz Ordaz 430',    'Chihuahua', 'Activo', '33180');
INSERT INTO PROVEEDOR VALUES (9,  'Avicola Imperial',     'Fernando Aguilar', '6149990011', 'Av. Heroico Colegio 65',  'Chihuahua', 'Activo', '33190');
INSERT INTO PROVEEDOR VALUES (10, 'Local',                'Pedro Sandoval',   '0000000000', 'Calle Juan Estudia 1',           'Chihuahua', 'Activo', '33200');
 
--------------------------------------------------
-- CLIENTES
--------------------------------------------------
INSERT INTO CLIENTE VALUES (1,  'Estefinya',     'Colonia Centro',    'Chihuahua', 'Chihuahua', '31000', '6141000001', 'estefinya@gmail.com', SYSDATE);
INSERT INTO CLIENTE VALUES (2,  'Ivan Señor',    'Colonia Revolucion','Chihuahua', 'Chihuahua', '31100', '6141000002', 'ivan@gmail.com',      SYSDATE);
INSERT INTO CLIENTE VALUES (3,  'Leonardo',      'Colonia Dale',      'Chihuahua', 'Chihuahua', '31200', '6141000003', 'leo@gmail.com',       SYSDATE);
INSERT INTO CLIENTE VALUES (4,  'Maria Lopez',   'Campesina',         'Chihuahua', 'Chihuahua', '31300', '6141000004', 'maria@gmail.com',     SYSDATE);
INSERT INTO CLIENTE VALUES (5,  'Carlos Perez',  'Panamericana',      'Chihuahua', 'Chihuahua', '31400', '6141000005', 'carlos@gmail.com',    SYSDATE);
INSERT INTO CLIENTE VALUES (6,  'Fernanda Ruiz', 'San Felipe',        'Chihuahua', 'Chihuahua', '31500', '6141000006', 'fer@gmail.com',       SYSDATE);
INSERT INTO CLIENTE VALUES (7,  'Miguel Torres', 'Las Granjas',       'Chihuahua', 'Chihuahua', '31600', '6141000007', 'miguel@gmail.com',    SYSDATE);
INSERT INTO CLIENTE VALUES (8,  'Andrea Silva',  'Nombre de Dios',    'Chihuahua', 'Chihuahua', '31700', '6141000008', 'andrea@gmail.com',    SYSDATE);
INSERT INTO CLIENTE VALUES (9,  'Jose Ramirez',  'Arquitectos',       'Chihuahua', 'Chihuahua', '31800', '6141000009', 'jose@gmail.com',      SYSDATE);
INSERT INTO CLIENTE VALUES (10, 'Patricia Gomez','Los Pinos',         'Chihuahua', 'Chihuahua', '31900', '6141000010', 'paty@gmail.com',      SYSDATE);
 
--------------------------------------------------
-- EMPLEADOS

--------------------------------------------------
INSERT INTO EMPLEADO VALUES (1,  'Joshua Alejandro',       '6391112340', TO_DATE('2018-03-01','YYYY-MM-DD'), 'Veterinario',  'Cirugia',          '25000', TO_DATE('2005-11-27','YYYY-MM-DD'), 'LEAR850612H10');
INSERT INTO EMPLEADO VALUES (2,  'Jorge Antonio',       '6361303044', TO_DATE('2019-07-15','YYYY-MM-DD'), 'Veterinario',  'Medicina General', '24000', TO_DATE('2005-11-27','YYYY-MM-DD'), 'POL900228M11');
INSERT INTO EMPLEADO VALUES (3,  'Abdiel Ivan',         '6391657480', TO_DATE('2020-01-10','YYYY-MM-DD'), 'Asistente',    'Atencion Cliente', '12000', TO_DATE('2005-10-01','YYYY-MM-DD'), 'MENI981105H12');
INSERT INTO EMPLEADO VALUES (4,  'Gabriela Torres',      '6141110004', TO_DATE('2021-04-20','YYYY-MM-DD'), 'Recepcionista','Administracion',   '11000', TO_DATE('1995-08-17','YYYY-MM-DD'), 'TOGM950817M13');
INSERT INTO EMPLEADO VALUES (5,  'Hugo Salinas',     '6141110005', TO_DATE('2017-09-05','YYYY-MM-DD'), 'Veterinario',  'Animales Grandes', '27000', TO_DATE('1980-04-30','YYYY-MM-DD'), 'SAHH800430H14');
 
--------------------------------------------------
-- ANIMALES REGISTRADOS
--------------------------------------------------
INSERT INTO ANIMAL VALUES (1,  'Jabon',   ADD_MONTHS(SYSDATE,-24), 'Macho',  'Blanco',         NULL,  7,  1,  'Con Dueño', 10);
INSERT INTO ANIMAL VALUES (2,  'Felipe',  ADD_MONTHS(SYSDATE,-36), 'Macho',  'Blanco Gris',    NULL,  6,  2,  'Con Dueño', 10);
INSERT INTO ANIMAL VALUES (3,  'Erick',   ADD_MONTHS(SYSDATE,-6),  'Macho',  'Amarillo Blanco', 10,  22,  3,  'Con Dueño',  8);
INSERT INTO ANIMAL VALUES (4,  'Spirit',  ADD_MONTHS(SYSDATE,-60), 'Macho',  'Cafe',           35000, 2,  4,  'Con Dueño',  6);
INSERT INTO ANIMAL VALUES (5,  'Remi',    ADD_MONTHS(SYSDATE,-12), 'Macho',  'Blanco',         NULL,  7,  5,  'Con Dueño', 10);
INSERT INTO ANIMAL VALUES (6,  'Miguel',  ADD_MONTHS(SYSDATE,-10), 'Macho',  'Negro',          NULL,  7,  6,  'Con Dueño', 10);
INSERT INTO ANIMAL VALUES (7,  'Julio',   ADD_MONTHS(SYSDATE,-8),  'Macho',  'Cafe',           NULL,  7,  NULL,  'En Adopcion', 10);
INSERT INTO ANIMAL VALUES (8,  'Max',     ADD_MONTHS(SYSDATE,-20), 'Macho',  'Negro',          NULL,  13, 8,  'Con Dueño', 10);
INSERT INTO ANIMAL VALUES (9,  'Luna',    ADD_MONTHS(SYSDATE,-14), 'Hembra', 'Blanco',         NULL,  7,  9,  'Con Dueño', 10);
INSERT INTO ANIMAL VALUES (10, 'Rocky',   ADD_MONTHS(SYSDATE,-30), 'Macho',  'Cafe',           NULL,  14, 10, 'Con Dueño', 10);
INSERT INTO ANIMAL VALUES (11, 'Milo',    ADD_MONTHS(SYSDATE,-16), 'Macho',  'Gris',           NULL,  6,  1,  'Adoptado', 10);
INSERT INTO ANIMAL VALUES (12, 'Nina',    ADD_MONTHS(SYSDATE,-12), 'Hembra', 'Blanco',         NULL,  7,  NULL,  'En Adopcion', 10);
INSERT INTO ANIMAL VALUES (13, 'Thor',    ADD_MONTHS(SYSDATE,-48), 'Macho',  'Negro Cafe',     NULL,  13, 3,  'Con Dueño', 10);
INSERT INTO ANIMAL VALUES (14, 'Pelusa',  ADD_MONTHS(SYSDATE,-18), 'Hembra', 'Blanco',         NULL,  7,  4,  'Adoptado', 10);
 
-- GALLINAS A LA VENTA
INSERT INTO ANIMAL VALUES (15, 'Polaca1', ADD_MONTHS(SYSDATE,-5), 'Hembra', 'Blanco',       350, 9,  NULL, 'Disponible', 9);
INSERT INTO ANIMAL VALUES (16, 'Polaca2', ADD_MONTHS(SYSDATE,-5), 'Hembra', 'Negro',        350, 9,  NULL, 'Disponible', 9);
INSERT INTO ANIMAL VALUES (17, 'Polaca3', ADD_MONTHS(SYSDATE,-5), 'Hembra', 'Cafe',         350, 9,  NULL, 'Disponible', 9);
INSERT INTO ANIMAL VALUES (18, 'Polaca4', ADD_MONTHS(SYSDATE,-5), 'Hembra', 'Blanco Negro', 350, 9,  NULL, 'Disponible', 9);
INSERT INTO ANIMAL VALUES (19, 'Braham1', ADD_MONTHS(SYSDATE,-6), 'Macho',  'Cafe',         500, 10, NULL, 'Disponible', 9);
INSERT INTO ANIMAL VALUES (20, 'Braham2', ADD_MONTHS(SYSDATE,-6), 'Macho',  'Negro',        500, 10, NULL, 'Disponible', 9);
INSERT INTO ANIMAL VALUES (21, 'Braham3', ADD_MONTHS(SYSDATE,-6), 'Macho',  'Blanco',       500, 10, NULL, 'Disponible', 9);
INSERT INTO ANIMAL VALUES (22, 'Gallina1',ADD_MONTHS(SYSDATE,-4), 'Hembra', 'Rojo',         280, 9,  NULL, 'Disponible', 9);
INSERT INTO ANIMAL VALUES (23, 'Gallina2',ADD_MONTHS(SYSDATE,-4), 'Hembra', 'Cafe',         280, 9,  NULL, 'Disponible', 9);
INSERT INTO ANIMAL VALUES (24, 'Gallina3',ADD_MONTHS(SYSDATE,-4), 'Hembra', 'Negro',        280, 9,  NULL, 'Disponible', 9);
 
-- PECES A LA VENTA
INSERT INTO ANIMAL VALUES (25, 'Nemo',  ADD_MONTHS(SYSDATE,-3), 'Macho',  'Naranja',    120, 22, NULL, 'Disponible', 8);
INSERT INTO ANIMAL VALUES (26, 'Blue',  ADD_MONTHS(SYSDATE,-2), 'Hembra', 'Azul',        90, 19, NULL, 'Disponible', 8);
INSERT INTO ANIMAL VALUES (27, 'Flash', ADD_MONTHS(SYSDATE,-4), 'Macho',  'Rojo',        70, 20, NULL, 'Disponible', 8);
INSERT INTO ANIMAL VALUES (28, 'King',  ADD_MONTHS(SYSDATE,-5), 'Macho',  'Azul',       200, 21, NULL, 'Disponible', 8);
INSERT INTO ANIMAL VALUES (29, 'Angel', ADD_MONTHS(SYSDATE,-7), 'Hembra', 'Blanco',     180, 23, NULL, 'Disponible', 8);
INSERT INTO ANIMAL VALUES (30, 'Goldy', ADD_MONTHS(SYSDATE,-3), 'Hembra', 'Dorado',      50, 18, NULL, 'Disponible', 8);
INSERT INTO ANIMAL VALUES (31, 'Ray',   ADD_MONTHS(SYSDATE,-3), 'Macho',  'Negro',      110, 19, NULL, 'Disponible', 8);
INSERT INTO ANIMAL VALUES (32, 'Ocean', ADD_MONTHS(SYSDATE,-2), 'Macho',  'Azul Negro', 140, 23, NULL, 'Disponible', 8);
 
-- SERPIENTES A LA VENTA
INSERT INTO ANIMAL VALUES (33, 'Sombra', ADD_MONTHS(SYSDATE,-12), 'Macho',  'Negro',        2500, 15, NULL, 'Disponible', 7);
INSERT INTO ANIMAL VALUES (34, 'Venom',  ADD_MONTHS(SYSDATE,-10), 'Macho',  'Cafe',         2200, 16, NULL, 'Disponible', 7);
INSERT INTO ANIMAL VALUES (35, 'Kira',   ADD_MONTHS(SYSDATE,-9),  'Hembra', 'Amarillo',     2700, 17, NULL, 'Disponible', 7);
INSERT INTO ANIMAL VALUES (36, 'Rex',    ADD_MONTHS(SYSDATE,-8),  'Macho',  'Negro Blanco',  2600, 15, NULL, 'Disponible', 7);
INSERT INTO ANIMAL VALUES (37, 'Nova',   ADD_MONTHS(SYSDATE,-7),  'Hembra', 'Rojo Negro',   3000, 16, NULL, 'Disponible', 7);
 

--------------------------------------------------
-- CATEGORIAS DE PRODUCTOS
--------------------------------------------------
INSERT INTO CATEGORIA_PRODUCTO VALUES (1, 'Medicamentos',    'Medicamentos veterinarios');
INSERT INTO CATEGORIA_PRODUCTO VALUES (2, 'Comida Perros',   'Alimento para perros');
INSERT INTO CATEGORIA_PRODUCTO VALUES (3, 'Comida Gatos',    'Alimento para gatos');
INSERT INTO CATEGORIA_PRODUCTO VALUES (4, 'Comida Aves',     'Alimento para aves');
INSERT INTO CATEGORIA_PRODUCTO VALUES (5, 'Comida Reptiles', 'Alimento para reptiles');
INSERT INTO CATEGORIA_PRODUCTO VALUES (6, 'Comida Caballos', 'Alimento para caballos');
INSERT INTO CATEGORIA_PRODUCTO VALUES (7, 'Comida Vacas',    'Alimento para vacas');
INSERT INTO CATEGORIA_PRODUCTO VALUES (8, 'Accesorios',      'Accesorios para animales');
 
--------------------------------------------------
-- PRODUCTOS (100)
--------------------------------------------------
 
INSERT INTO PRODUCTOS VALUES (1,  'Antibiotico Canino',          250, 50, 'S', 1);
INSERT INTO PRODUCTOS VALUES (2,  'Desparasitante Felino',        180, 40, 'S', 1);
INSERT INTO PRODUCTOS VALUES (3,  'Vitamina Aves',                120, 35, 'N', 1);
INSERT INTO PRODUCTOS VALUES (4,  'Suero Veterinario',             90, 60, 'S', 1);
INSERT INTO PRODUCTOS VALUES (5,  'Vacuna Rabia',                 300, 25, 'S', 1);
INSERT INTO PRODUCTOS VALUES (6,  'Antiinflamatorio Equino',      450, 15, 'S', 1);
INSERT INTO PRODUCTOS VALUES (7,  'Jarabe Respiratorio',          210, 30, 'S', 1);
INSERT INTO PRODUCTOS VALUES (8,  'Gotas Oculares',               130, 45, 'N', 1);
INSERT INTO PRODUCTOS VALUES (9,  'Pomada Cicatrizante',           95, 40, 'N', 1);
INSERT INTO PRODUCTOS VALUES (10, 'Pastillas Vitaminadas',         160, 50, 'N', 1);
INSERT INTO PRODUCTOS VALUES (11, 'Antibiotico Bovino',           500, 20, 'S', 1);
INSERT INTO PRODUCTOS VALUES (12, 'Vacuna Triple Felina',         320, 18, 'S', 1);
INSERT INTO PRODUCTOS VALUES (13, 'Vacuna Moquillo',              350, 18, 'S', 1);
INSERT INTO PRODUCTOS VALUES (14, 'Antipulgas Spray',             220, 27, 'N', 1);
INSERT INTO PRODUCTOS VALUES (15, 'Calmante Reptiles',            410, 10, 'S', 1);
INSERT INTO PRODUCTOS VALUES (16, 'Vitaminas Peces',               90, 55, 'N', 1);
INSERT INTO PRODUCTOS VALUES (17, 'Antiseptico Animal',            85, 60, 'N', 1);
INSERT INTO PRODUCTOS VALUES (18, 'Gel Cicatrizante',             150, 25, 'N', 1);
INSERT INTO PRODUCTOS VALUES (19, 'Shampoo Medicado',             190, 35, 'N', 1);
INSERT INTO PRODUCTOS VALUES (20, 'Desinfectante Veterinario',    300, 20, 'N', 1);
 
-- COMIDA PARA PERROS (categoria 2) — alimentos no requieren receta
INSERT INTO PRODUCTOS VALUES (21, 'Croquetas Premium',            850, 30, 'N', 2);
INSERT INTO PRODUCTOS VALUES (22, 'Croquetas Cachorro',           780, 25, 'N', 2);
INSERT INTO PRODUCTOS VALUES (23, 'Alimento Pastor Aleman',       920, 18, 'N', 2);
INSERT INTO PRODUCTOS VALUES (24, 'Alimento Bulldog',             880, 15, 'N', 2);
INSERT INTO PRODUCTOS VALUES (25, 'Snacks Caninos',               120, 60, 'N', 2);
INSERT INTO PRODUCTOS VALUES (26, 'Huesos de Carnaza',             95, 70, 'N', 2);
INSERT INTO PRODUCTOS VALUES (27, 'Lata Carne Res',                60,100, 'N', 2);
INSERT INTO PRODUCTOS VALUES (28, 'Lata Pollo',                    58, 90, 'N', 2);
INSERT INTO PRODUCTOS VALUES (29, 'Croquetas Adulto',             810, 35, 'N', 2);
INSERT INTO PRODUCTOS VALUES (30, 'Croquetas Senior',             830, 22, 'N', 2);
INSERT INTO PRODUCTOS VALUES (31, 'Premios Caninos',              140, 45, 'N', 2);
INSERT INTO PRODUCTOS VALUES (32, 'Alimento Husky',               950, 12, 'N', 2);
INSERT INTO PRODUCTOS VALUES (33, 'Croquetas Mini',               700, 20, 'N', 2);
INSERT INTO PRODUCTOS VALUES (34, 'Croquetas Razas Grandes',      980, 14, 'N', 2);
INSERT INTO PRODUCTOS VALUES (35, 'Alimento Balanceado',          760, 33, 'N', 2);
 
-- COMIDA PARA GATOS (categoria 3)
INSERT INTO PRODUCTOS VALUES (36, 'Croquetas Siames',             650, 25, 'N', 3);
INSERT INTO PRODUCTOS VALUES (37, 'Croquetas Persa',              690, 18, 'N', 3);
INSERT INTO PRODUCTOS VALUES (38, 'Lata Atun',                     45, 90, 'N', 3);
INSERT INTO PRODUCTOS VALUES (39, 'Lata Salmon',                   50, 80, 'N', 3);
INSERT INTO PRODUCTOS VALUES (40, 'Premios Felinos',              110, 50, 'N', 3);
INSERT INTO PRODUCTOS VALUES (41, 'Arena para Gato',              210, 40, 'N', 3);
INSERT INTO PRODUCTOS VALUES (42, 'Croquetas Gatito',             620, 20, 'N', 3);
INSERT INTO PRODUCTOS VALUES (43, 'Croquetas Adulto Gato',        700, 18, 'N', 3);
INSERT INTO PRODUCTOS VALUES (44, 'Leche para Gatitos',           130, 35, 'N', 3);
INSERT INTO PRODUCTOS VALUES (45, 'Snacks de Pescado',             95, 50, 'N', 3);
INSERT INTO PRODUCTOS VALUES (46, 'Comida Humeda',                 55, 75, 'N', 3);
INSERT INTO PRODUCTOS VALUES (47, 'Croquetas Premium Gato',       820, 16, 'N', 3);
INSERT INTO PRODUCTOS VALUES (48, 'Alimento Bajo en Grasas',      760, 15, 'N', 3);
INSERT INTO PRODUCTOS VALUES (49, 'Alimento Control Bolas Pelo',  790, 14, 'N', 3);
INSERT INTO PRODUCTOS VALUES (50, 'Premios de Pollo',             120, 40, 'N', 3);
 
-- COMIDA PARA AVES (categoria 4)
INSERT INTO PRODUCTOS VALUES (51, 'Semillas Premium',             180, 35, 'N', 4);
INSERT INTO PRODUCTOS VALUES (52, 'Alimento Ninfas',              220, 20, 'N', 4);
INSERT INTO PRODUCTOS VALUES (53, 'Maiz Molido',                  160, 40, 'N', 4);
INSERT INTO PRODUCTOS VALUES (54, 'Vitaminas Aves',               130, 30, 'N', 4);
INSERT INTO PRODUCTOS VALUES (55, 'Alimento Pollitos',            150, 45, 'N', 4);
INSERT INTO PRODUCTOS VALUES (56, 'Alpiste',                       90, 50, 'N', 4);
INSERT INTO PRODUCTOS VALUES (57, 'Pellets para Aves',            210, 25, 'N', 4);
INSERT INTO PRODUCTOS VALUES (58, 'Mezcla Tropical',              240, 18, 'N', 4);
INSERT INTO PRODUCTOS VALUES (59, 'Suplemento Calcio',            175, 20, 'N', 4);
INSERT INTO PRODUCTOS VALUES (60, 'Semillas Naturales',           120, 38, 'N', 4);
 
-- COMIDA REPTILES (categoria 5)
INSERT INTO PRODUCTOS VALUES (61, 'Alimento Gecko',               260, 15, 'N', 5);
INSERT INTO PRODUCTOS VALUES (62, 'Alimento Dragon Barbudo',      320, 12, 'N', 5);
INSERT INTO PRODUCTOS VALUES (63, 'Insectos Deshidratados',       150, 25, 'N', 5);
INSERT INTO PRODUCTOS VALUES (64, 'Suplemento Reptil',            190, 20, 'N', 5);
INSERT INTO PRODUCTOS VALUES (65, 'Vitaminas Reptiles',           220, 14, 'N', 5);
INSERT INTO PRODUCTOS VALUES (66, 'Comida Tortuga',               140, 18, 'N', 5);
INSERT INTO PRODUCTOS VALUES (67, 'Ratones Congelados',           350, 10, 'N', 5);
INSERT INTO PRODUCTOS VALUES (68, 'Calcio Reptiles',              200, 16, 'N', 5);
INSERT INTO PRODUCTOS VALUES (69, 'Alimento Serpientes',          330, 11, 'N', 5);
INSERT INTO PRODUCTOS VALUES (70, 'Snack Reptiles',               125, 22, 'N', 5);
 
-- COMIDA CABALLOS (categoria 6)
INSERT INTO PRODUCTOS VALUES (71, 'Alfalfa Premium',              450, 20, 'N', 6);
INSERT INTO PRODUCTOS VALUES (72, 'Avena Equina',                 390, 18, 'N', 6);
INSERT INTO PRODUCTOS VALUES (73, 'Suplemento Caballo',           520, 12, 'N', 6);
INSERT INTO PRODUCTOS VALUES (74, 'Minerales Equinos',            480, 10, 'N', 6);
INSERT INTO PRODUCTOS VALUES (75, 'Pellets Caballo',              430, 16, 'N', 6);
INSERT INTO PRODUCTOS VALUES (76, 'Heno Natural',                 370, 25, 'N', 6);
INSERT INTO PRODUCTOS VALUES (77, 'Vitaminas Equinas',            510, 10, 'N', 6);
INSERT INTO PRODUCTOS VALUES (78, 'Mezcla Nutricional',           560,  8, 'N', 6);
INSERT INTO PRODUCTOS VALUES (79, 'Alimento Mustang',             600,  7, 'N', 6);
INSERT INTO PRODUCTOS VALUES (80, 'Suplemento Energia',           450,  9, 'N', 6);
 
-- COMIDA VACAS (categoria 7)
INSERT INTO PRODUCTOS VALUES (81, 'Alimento Angus',               700, 20, 'N', 7);
INSERT INTO PRODUCTOS VALUES (82, 'Pasto Seco',                   500, 25, 'N', 7);
INSERT INTO PRODUCTOS VALUES (83, 'Suplemento Bovino',            650, 15, 'N', 7);
INSERT INTO PRODUCTOS VALUES (84, 'Sales Minerales',              300, 30, 'N', 7);
INSERT INTO PRODUCTOS VALUES (85, 'Alimento Azul Belga',          780, 10, 'N', 7);
INSERT INTO PRODUCTOS VALUES (86, 'Forraje Premium',              550, 20, 'N', 7);
INSERT INTO PRODUCTOS VALUES (87, 'Nutricion Bovina',             620, 12, 'N', 7);
INSERT INTO PRODUCTOS VALUES (88, 'Vitaminas Vacas',              430, 18, 'N', 7);
INSERT INTO PRODUCTOS VALUES (89, 'Paca de Alfalfa',              470, 15, 'N', 7);
INSERT INTO PRODUCTOS VALUES (90, 'Mezcla Bovinos',               710,  9, 'N', 7);
 
-- ACCESORIOS (categoria 8)
INSERT INTO PRODUCTOS VALUES (91,  'Correa Grande',               250, 20, 'N', 8);
INSERT INTO PRODUCTOS VALUES (92,  'Correa Mediana',              220, 25, 'N', 8);
INSERT INTO PRODUCTOS VALUES (93,  'Correa Chica',                180, 30, 'N', 8);
INSERT INTO PRODUCTOS VALUES (94,  'Chaleco Canino',              320, 18, 'N', 8);
INSERT INTO PRODUCTOS VALUES (95,  'Espuelas Caballo',            900,  8, 'N', 8);
INSERT INTO PRODUCTOS VALUES (96,  'Pecera Pequena',              450, 10, 'N', 8);
INSERT INTO PRODUCTOS VALUES (97,  'Terrario Reptiles',          1800,  5, 'N', 8);
INSERT INTO PRODUCTOS VALUES (98,  'Rascador Gatos',              750,  7, 'N', 8);
INSERT INTO PRODUCTOS VALUES (99,  'Jaula Aves',                 1200,  6, 'N', 8);
INSERT INTO PRODUCTOS VALUES (100, 'Cama Mascota',                680, 12, 'N', 8);

--------------------------------------------------
-- VENTAS
-- (Venta_Id, Fecha, Cliente_Id, Empleado_Id, Metodo_Pago, Tipo_Ventas)
--------------------------------------------------
INSERT INTO VENTAS VALUES (1, TO_DATE('2026-05-01','YYYY-MM-DD'), 5, 3, 'Efectivo',  'Venta Animal y Producto');
INSERT INTO VENTAS VALUES (2, TO_DATE('2026-05-05','YYYY-MM-DD'), 4, 3, 'Tarjeta',   'Venta Producto');
INSERT INTO VENTAS VALUES (3, TO_DATE('2026-05-10','YYYY-MM-DD'), 2, 3, 'Efectivo',  'Venta Animal y Producto');
INSERT INTO VENTAS VALUES (4, TO_DATE('2026-05-12','YYYY-MM-DD'), 1, 1, 'Tarjeta',   'Consulta y Producto');
INSERT INTO VENTAS VALUES (5, TO_DATE('2026-05-15','YYYY-MM-DD'), 7, 1, 'Mixto',     'Venta Animal y Producto');
 
--------------------------------------------------
-- VENTAS_ANIMAL
-- (Animal_Id, Venta_Id, Precio_Venta)
--------------------------------------------------
INSERT INTO VENTAS_ANIMAL VALUES (15, 1, 350.00);
INSERT INTO VENTAS_ANIMAL VALUES (28, 3, 200.00);
INSERT INTO VENTAS_ANIMAL VALUES (33, 5, 2500.00);
 
--------------------------------------------------
-- VENTAS_PRODUCTO
-- (Venta_Id, Producto_Id, Precio_Venta, Cantidad)
--------------------------------------------------
INSERT INTO VENTAS_PRODUCTO VALUES (1, 51, 180.00, 2);
INSERT INTO VENTAS_PRODUCTO VALUES (2, 21, 850.00, 1);
INSERT INTO VENTAS_PRODUCTO VALUES (2, 25, 120.00, 2);
INSERT INTO VENTAS_PRODUCTO VALUES (3, 96, 450.00, 1);
INSERT INTO VENTAS_PRODUCTO VALUES (4, 12, 320.00, 1);
INSERT INTO VENTAS_PRODUCTO VALUES (5, 97, 1800.00, 1);
 
--------------------------------------------------
-- CONSULTA
-- (Consulta_Id, Fecha, Motivo, Diagnostico, Tratamiento, Empleado_Id, Animal_Id, Venta_Id; Costo_Consulta)
--------------------------------------------------
INSERT INTO CONSULTA VALUES (1, TO_DATE('2026-05-12','YYYY-MM-DD'),
    'Vacunacion anual',
    'Animal sano, apto para vacuna',
    'Aplicacion Vacuna Triple Felina',
    1, 1, 4, 300);
INSERT INTO CONSULTA VALUES (2, TO_DATE('2026-05-08','YYYY-MM-DD'),
    'Tos persistente',
    'Bronquitis leve',
    'Jarabe Respiratorio por 7 dias',
    2, 8, NULL, 250);
INSERT INTO CONSULTA VALUES (3, TO_DATE('2026-05-03','YYYY-MM-DD'),
    'Revision general',
    'Estado de salud optimo',
    'Ninguno',
    1, 10, NULL, 250);
INSERT INTO CONSULTA VALUES (4, TO_DATE('2026-05-14','YYYY-MM-DD'),
    'Revision post parto',
    'Recuperacion normal',
    'Vitaminas y reposo',
    2, 9, NULL, 400);-- Consulta 5: Thor el Pastor Aleman desparasitacion
INSERT INTO CONSULTA VALUES (5, TO_DATE('2026-05-17','YYYY-MM-DD'),
    'Desparasitacion semestral',
    'Sin parasitos detectados',
    'Desparasitante preventivo aplicado',
    1, 13, NULL, 300);
 
--------------------------------------------------
-- CIRUGIA
-- (Cirugia_Id, Fecha, Tipo_Cirugia, Descripcion, Animal_Id, Empleado_Id, Venta_Id, Costo_Cirugia)
--------------------------------------------------

INSERT INTO CIRUGIA VALUES (1, TO_DATE('2026-04-20','YYYY-MM-DD'),
    'Esterilizacion',
    'Ovariohisterectomia exitosa sin complicaciones',
    9, 1, NULL, 1080);
INSERT INTO CIRUGIA VALUES (2, TO_DATE('2026-05-02','YYYY-MM-DD'),
    'Extraccion de tumor',
    'Tumor benigno en pata delantera, extirpado con exito',
    10, 1, NULL, 2500);
INSERT INTO CIRUGIA VALUES (3, TO_DATE('2026-03-15','YYYY-MM-DD'),
    'Ortopedia',
    'Reparacion de fractura en tibia delantera derecha',
    13, 5, NULL, 1400);
INSERT INTO CIRUGIA VALUES (4, TO_DATE('2026-04-10','YYYY-MM-DD'),
    'Castracion',
    'Orquiectomia bilateral sin complicaciones',
    8, 1, NULL, 1180);
INSERT INTO CIRUGIA VALUES (5, TO_DATE('2026-05-18','YYYY-MM-DD'),
    'Colico equino',
    'Intervencion por colico espasmódico, evolucion favorable',
    4, 5, NULL, 900);
 
--------------------------------------------------
-- HISTORIAL_MEDICO
-- (Historial_Id, Fecha, Notas, Tipo, Animal_Id, Empleado_Id, Descripcion, Peso, Temperatura)
--------------------------------------------------
INSERT INTO HISTORIAL_MEDICO VALUES (1, TO_DATE('2026-05-12','YYYY-MM-DD'),
    'Primera vacunacion del año',
    'Vacunacion',
    1, 1, 'Vacuna Triple Felina aplicada sin reacciones adversas', 4.20, 38.50);
INSERT INTO HISTORIAL_MEDICO VALUES (2, TO_DATE('2026-05-08','YYYY-MM-DD'),
    'Tratamiento por bronquitis leve',
    'Enfermedad',
    8, 2, 'Se receta Jarabe Respiratorio cada 8 horas por 7 dias', 32.10, 39.20);
INSERT INTO HISTORIAL_MEDICO VALUES (3, TO_DATE('2026-04-20','YYYY-MM-DD'),
    'Post operatorio esterilizacion',
    'Cirugia',
    9, 1, 'Recuperacion satisfactoria, sutura limpia y sin infeccion', 3.80, 38.30);
INSERT INTO HISTORIAL_MEDICO VALUES (4, TO_DATE('2026-05-03','YYYY-MM-DD'),
    'Revision anual de rutina',
    'Revision',
    10, 1, 'Bulldog en excelente estado, dientes y articulaciones normales', 25.00, 38.70);
INSERT INTO HISTORIAL_MEDICO VALUES (5, TO_DATE('2026-05-17','YYYY-MM-DD'),
    'Desparasitacion semestral',
    'Prevencion',
    13, 1, 'Sin parasitos internos ni externos detectados, peso estable', 38.50, 38.60);
 
--------------------------------------------------
-- ADOPCION
-- (Adopcion_Id, Fecha_Adopcion, Condiciones, Estado_Adopcion, Animal_Id, Cliente_Id, Empleado_Id)
--------------------------------------------------
INSERT INTO ADOPCION VALUES (1, TO_DATE('2026-04-15','YYYY-MM-DD'),
    'Esterilizado, vacunas al dia, patio no requerido',
    'Finalizada', 11, 4, 3);
INSERT INTO ADOPCION VALUES (2, TO_DATE('2026-05-10','YYYY-MM-DD'),
    'Requiere espacio interior, compatible con niños',
    'Pendiente', 12, NULL, 3);
INSERT INTO ADOPCION VALUES (3, TO_DATE('2026-04-28','YYYY-MM-DD'),
    'Gata tranquila, ideal para departamento',
    'Finalizada', 14, 4, 4);
INSERT INTO ADOPCION VALUES (4, TO_DATE('2026-05-16','YYYY-MM-DD'),
    'Gato joven activo, requiere juguetes e interaccion diaria',
    'Pendiente', 7, NULL, 4);
 
--------------------------------------------------
-- PEDIDO_ANIMAL
-- (PedidoA_Id, Fecha_Orden, Fecha_Recepcion, Costo_Envio, Empleado_Id, Proveedor_Id, Tipo, Estado_Pedido_Animal)
--------------------------------------------------
INSERT INTO PEDIDO_ANIMAL VALUES (1,
    TO_DATE('2026-04-01','YYYY-MM-DD'),
    TO_DATE('2026-04-08','YYYY-MM-DD'),
    250.00, 3, 9, 'Aves de corral', 'Entregado');
INSERT INTO PEDIDO_ANIMAL VALUES (2,
    TO_DATE('2026-04-10','YYYY-MM-DD'),
    TO_DATE('2026-04-15','YYYY-MM-DD'),
    180.00, 3, 8, 'Peces ornamentales', 'Entregado');
INSERT INTO PEDIDO_ANIMAL VALUES (3,
    TO_DATE('2026-05-05','YYYY-MM-DD'),
    TO_DATE('2026-05-12','YYYY-MM-DD'),
    400.00, 3, 7, 'Reptiles exoticos', 'Entregado');
INSERT INTO PEDIDO_ANIMAL VALUES (4,
    TO_DATE('2026-03-01','YYYY-MM-DD'),
    TO_DATE('2026-03-10','YYYY-MM-DD'),
    800.00, 5, 6, 'Equino de exhibicion', 'Entregado');
INSERT INTO PEDIDO_ANIMAL VALUES (5,
    TO_DATE('2026-05-20','YYYY-MM-DD'),
    NULL,
    300.00, 3, 9, 'Aves de corral', 'Solicitado');
 
--------------------------------------------------
-- DETALLE_PEDIDO_ANIMAL
-- (Animal_Id, PedidoA_Id, Costo)
-- Costo = precio que le cobro el proveedor por ese animal
--------------------------------------------------
-- Pedido 1: 4 Gallinas Polacas
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (15, 1, 280.00); -- Polaca1 Blanca
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (16, 1, 280.00); -- Polaca2 Negra
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (17, 1, 280.00); -- Polaca3 Cafe
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (18, 1, 280.00); -- Polaca4 Blanco Negro
-- Pedido 2: Peces ornamentales
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (25, 2,  90.00); -- Nemo Pez Payaso
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (26, 2,  65.00); -- Blue Pez Tetra Neon
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (27, 2,  50.00); -- Flash Pez Platy
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (28, 2, 150.00); -- King Pez Betta
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (29, 2, 130.00); -- Angel Pez Angel
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (30, 2,  35.00); -- Goldy Pez Guppy
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (31, 2,  65.00); -- Ray Pez Tetra Neon
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (32, 2, 100.00); -- Ocean Pez Angel
-- Pedido 3: Serpientes exoticas
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (33, 3, 2000.00); -- Sombra Piton Bola
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (34, 3, 1700.00); -- Venom Serpiente de Maiz
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (35, 3, 2100.00); -- Kira Serpiente Rey
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (36, 3, 2000.00); -- Rex Piton Bola
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (37, 3, 2400.00); -- Nova Serpiente de Maiz
-- Pedido 4: Caballo Spirit
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (4,  4, 30000.00); -- Spirit Mustang
-- Pedido 5: Brahams nuevos (aun solicitados, sin costo confirmado aun)
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (19, 5, 400.00); -- Braham1
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (20, 5, 400.00); -- Braham2
INSERT INTO DETALLE_PEDIDO_ANIMAL VALUES (21, 5, 400.00); -- Braham3
 
--------------------------------------------------
-- PEDIDO_PRODUCTO
-- (PedidoP_Id, Fecha_Orden, Fecha_Recepcion, Costo_Envio, Empleado_Id, Proveedor_Id, Tipo, Estado_Pedido_Producto)
--------------------------------------------------
INSERT INTO PEDIDO_PRODUCTO VALUES (1,
    TO_DATE('2026-04-05','YYYY-MM-DD'),
    TO_DATE('2026-04-10','YYYY-MM-DD'),
    150.00, 4, 1, 'Medicamentos veterinarios', 'Entregado');
INSERT INTO PEDIDO_PRODUCTO VALUES (2,
    TO_DATE('2026-04-15','YYYY-MM-DD'),
    TO_DATE('2026-04-20','YYYY-MM-DD'),
    200.00, 4, 2, 'Alimento mascotas', 'Entregado');
INSERT INTO PEDIDO_PRODUCTO VALUES (3,
    TO_DATE('2026-05-01','YYYY-MM-DD'),
    TO_DATE('2026-05-06','YYYY-MM-DD'),
    120.00, 4, 9, 'Alimento aves', 'Entregado');
INSERT INTO PEDIDO_PRODUCTO VALUES (4,
    TO_DATE('2026-05-08','YYYY-MM-DD'),
    TO_DATE('2026-05-13','YYYY-MM-DD'),
    180.00, 4, 4, 'Accesorios para mascotas', 'Entregado');
INSERT INTO PEDIDO_PRODUCTO VALUES (5,
    TO_DATE('2026-05-18','YYYY-MM-DD'),
    NULL,
    100.00, 4, 7, 'Alimento reptiles', 'Reparto');
 
--------------------------------------------------
-- DETALLE_PEDIDO_PRODUCTO
-- (PedidoP_Id, Producto_Id, Cantidad, Costo)
-- Costo = precio unitario que cobra el proveedor
--------------------------------------------------
-- Pedido 1: Medicamentos
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (1,  1, 20, 180.00); -- Antibiotico Canino
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (1,  5, 10, 220.00); -- Vacuna Rabia
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (1, 12, 10, 240.00); -- Vacuna Triple Felina
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (1, 13, 10, 260.00); -- Vacuna Moquillo
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (1,  9, 15,  65.00); -- Pomada Cicatrizante
-- Pedido 2: Comida perros y gatos
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (2, 21, 10, 620.00); -- Croquetas Premium
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (2, 22, 10, 570.00); -- Croquetas Cachorro
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (2, 36,  8, 480.00); -- Croquetas Siames
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (2, 37,  8, 510.00); -- Croquetas Persa
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (2, 25, 20,  85.00); -- Snacks Caninos
-- Pedido 3: Alimento aves
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (3, 51, 15, 130.00); -- Semillas Premium
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (3, 52, 10, 160.00); -- Alimento Ninfas
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (3, 56, 20,  65.00); -- Alpiste
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (3, 55, 15, 110.00); -- Alimento Pollitos
-- Pedido 4: Accesorios
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (4, 91,  8, 180.00); -- Correa Grande
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (4, 96,  4, 330.00); -- Pecera Pequena
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (4, 97,  2,1300.00); -- Terrario Reptiles
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (4, 98,  3, 550.00); -- Rascador Gatos
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (4,100,  5, 500.00); -- Cama Mascota
-- Pedido 5: Alimento reptiles
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (5, 61,  6, 190.00); -- Alimento Gecko
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (5, 63, 10, 110.00); -- Insectos Deshidratados
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (5, 67,  5, 260.00); -- Ratones Congelados
INSERT INTO DETALLE_PEDIDO_PRODUCTO VALUES (5, 69,  5, 240.00); -- Alimento Serpientes

COMMIT;
