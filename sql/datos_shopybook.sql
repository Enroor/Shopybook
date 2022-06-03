-- Insertamos el usuario de la tienda admin 
INSERT INTO `shopybook`.`usuarios` (usuario, contrasena) VALUES('admin','admin');

-- Creamos las claves foráneas
ALTER TABLE `shopybook`.`libro`
ADD CONSTRAINT `categoriaID`
FOREIGN KEY (`categoriaID`) REFERENCES `categoria` (`id`)
ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE `shopybook`.`compra`
ADD CONSTRAINT `libroID`
FOREIGN KEY (`libroID`) REFERENCES `libro` (`id`)
ON UPDATE CASCADE ON DELETE CASCADE,
ADD CONSTRAINT `pedidoID`
FOREIGN KEY (`pedidoID`) REFERENCES `pedido` (`id`)
ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `shopybook`.`pedido`
ADD CONSTRAINT `usuarioID`
FOREIGN KEY (`usuarioID`) REFERENCES `usuarios` (`id`)
ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `shopybook`.`libro` ADD FULLTEXT(titulo, autor);

-- Creamos el usuario de la base de datos
CREATE USER 'adminShopybook'@'%' IDENTIFIED BY 'adminShopybook';
CREATE USER 'adminShopybook'@'localhost' IDENTIFIED BY 'adminShopybook';

-- Asignamos permisos al usuario
GRANT ALL ON `shopybook`.*
TO `admin`;

-- Insertamos datos de ejemplo en la tienda
USE `shopybook`;

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(1, 'Sin categoría'),
(2, 'Drama'),
(3, 'Terror'),
(4, 'Aventura'),
(5, 'Comedia');

INSERT INTO `libro` (`id`, `isbn`,`titulo`, `autor`,`imagen`, `descripcion`,`valoracion`, `stock`,`precio`,`categoriaID`) VALUES
(1, 1111111111111, 'ATREVETE A RETARME (LAS GUERRERAS MAXWELL, 7)','MEGAN MAXWELL', 'atrevete-a-retarme.jpg','Descubre, con esta nueva entrega de «Las guerreras Maxwell», cómo los retos acaban dando paso a nuevas oportunidades. 
Carolina Campbell es la pequeña de la familia. A diferencia de sus hermanas y hermanos, que cumplen la voluntad de sus padres, ella es más inquieta. Su carácter independiente y retador espanta a todos los hombres que se le acercan.
Peter McGregor, un guapo y joven highlander con un excelente sentido del humor, se dedica a la cría de caballos junto con sus amigos Aidan y Harald.
Los Campbell y los McGregor se odian desde hace años por algo que ocurrió entre sus antepasados y que llevó a los McGregor a entregarles unas tierras que Peter está dispuesto a recuperar a toda costa.
Y la oportunidad le llega de sopetón cuandoCarolina, intentando salir airosa de un problema y sin apenas conocer a Peter, le ofrece las tierras que desea a cambio de que se case con ella.
En un principio Peter se niega. ¿Acaso aquella Campbell se ha vuelto loca?
Al final, viendo que de este modo recuperará las propiedades que su padre tanto ansía, termina aceptando el enlace para un año y un día con Carolina. Pasado ese tiempo no renovará los votos matrimoniales: volverá a ser un hombre libre y con las tierras en su poder.
Pero ¿qué pasará si durante ese año se enamoran?
Eso solo lo sabrás si leesAtrévete a retarme,la séptima entrega de la famosa saga «Las guerreras Maxwell», que sin duda te llegará al corazón.', 3.9, 5, 17.10,2),
(2, 2111111111112, 'MALAVENTURA','FERNANDO NAVARRO', 'de-sangre-y-cenizas.jpg','Los héroes y los villanos se desdibujan en "Malaventura" una suerte de neorromancero ambientado en una Andalucía desesperada y remota, a la vez que irreal y auténtica, mítica y salvaje, llena de personajes
 extremos: quinquis, hechiceras, cazadores, demonios que se desplazan por las ondas de la radio, mercenarios de buen corazón o niños que maldicen a todo un pueblo. Una mujer barbera atrapada en una reyerta. El cruel linchamiento de un legendario bandolero. 
Una misteriosa matanza en una fonda en la que el único testigo es un burrico. El amor imposible entre una vidente y un forajido. Una inundación que sigue su curso llevándose por delante todo lo que encuentra a su paso o la inesperada visita de los fantasmas del pasado
 que buscan ajustar cuentas con un violento guardia civil.', 4.2, 1, 19.00,2),
(3, 3111111111113, 'LA COMPAÑERA','AGUSTINA GUERRERO', 'la-companera.jpg','Tras El viaje, aclamado por la crítica y los lectores, acompaña a La Volátil en una exploración inolvidable al origen de sus luces y sus sombras para aprender a ser feliz
"Una apuesta valiente, arriesgada, que merece la pena." El Ojo Crítico (RN1)
"Pocas autoras saben darle la naturalidad que tiene en sus lápices, con la misma facilidad que una página cargada de poesía." Cómic para todos
"Esta eres tú, y aún no me conoces, pero ya estoy ahí, contigo". Así comienza este emocionante libro en el que "la compañera" guiará a La Volátil a traves de desiertos, oceanos y cuevas para asomarse a distintos episodios de su vida que dejaron una huella importante a la hora de convertirse en quien es. Un recorrido por la memoria y los recuerdos sembrado de amor, humor y ternura, pero tambien de dolor, culpa y miedo, cuya última parada bien podría ser la felicidad.
La crítica ha dicho sobre la autora:
"[Por] su humor, la energía e intensidad de sus dibujos, sus ocurrentes viñetas y, sobre todo, el desnudarse en cada ilustración [...] se ha convertido en una de mis ilustradoras de referencia."
Ana Rivera Magaña, Diari de Tarragona
"Una pequeña heroína', 3.5, 26, 19.85,1),
(4, 4111111111114, 'DE SANGRE Y CENIZAS','JENNIFER L. ARMENTROUT', 'malaventura.jpg','Apasionante y con una acción trepidante, De sangre y ceniza es una fantasía sexy, adictiva e inesperada, perfecta para los seguidores de Sarah J. Maas y Laura Thalassa. Una Doncella… 
Elegida desde su nacimiento para dar comienzo a una nueva era, la vida de Poppy nunca le ha pertenecido. La vida de la Doncella es solitaria. Jamás la tocarán. Jamás la mirarán. Jamás le hablarán. Jamás sentirá placer. Mientras espera al día de su Ascensión, preferiría estar 
con los guardias luchando contra el mal que se llevó a su familia que preparándose para que los dioses la encuentren lo bastante digna. Pero la elección nunca ha sido suya. Un deber… El futuro del reino entero recae sobre los hombros de Poppy, algo que ni siquiera está demasiado
 segura de querer para ella. Porque una Doncella tiene corazón. Y alma. Y deseo. Y cuando Hawke, un guardia de ojos dorados que ha jurado asegurar su Ascensión, entra en su vida, el destino y el deber se entremezclan con el deseo y la necesidad. Él incita su ira, hace que se 
cuestione todo aquello en lo que cree y la tienta con lo prohibido. Un reino… Abandonado por los dioses y temido por los mortales, un reino caído está surgiendo de nuevo, decidido a recuperar lo que cree que es suyo mediante la violencia y la venganza. Y a medida que la sombra de 
los malditos se acerca, la línea entre lo prohibido y lo correcto se difumina. Poppy no solo está a punto de perder el corazón y ser considerada indigna por los dioses, sino que también está a punto de perder la vida cuando los ensangrentados hilos que mantienen unido su mundo
 empiezan a deshilacharse.', 4.7, 150, 18.95,3),
(5, 9788478884452, 'HARRY POTTER Y LA PIEDRA FILOSOFAL', 'J.K. Rowling', 'harry-potter-y-la-piedra-filosofal.jpg', '"Con las manos temblorosas, Harry le dio la vuelta al sobre y vio un sello de lacre púrpura con un escudo de armas: un león, un águila, un tejón y una serpiente, que rodeaban una gran letra H."
Harry Potter nunca ha oído hablar de Hogwarts hasta que empiezan a caer cartas en el felpudo del número 4 de Privet Drive. Llevan la dirección escrita con tinta verde en un sobre de pergamino amarillento con un sello de lacre púrpura, y sus horripilantes tíos se apresuran a confiscarlas. Más tarde, el día que 
Harry cumple once años, Rubeus Hagrid, un hombre gigantesco cuyos ojos brillan como escarabajos negros, irrumpe con una noticia extraordinaria: Harry Potter es un mago, y le han concedido una plaza en el Colegio Hogwarts de Magia y Hechicería. ¡Está a punto de comenzar una aventura increíble!',5, 10, 14.25, 3),
(6, 9788446051251,'LOS ROTOS','ANTONIO MAESTRE', 'los-rotos.jpg','La vida cotidiana atravesada por la clase está en constante remiendo. Los rotos son las personas de clase obrera, pero también los constantes destrozos de una existencia popular; las fracturas de una vida hostil, rota, como una kelly al final del turno. Roto como el ánimo de 
quien pierde dos horas cada día en el transporte público o en la sala de espera de un ambulatorio, sin esperanza de mejora; sin futuro. Rotos de dolor al enterrar a un compañero muerto en el tajo que se partió la cabeza al caérsele una lámina de hierro de 500 kilos o sufriendo el insomnio que provoca la incertidumbre
 por la proximidad de un ERE o la falta de carga de trabajo en una fábrica que no es tuya, pero te da de comer. Esta obra es una visión personal, íntima y subjetiva de cómo el origen social influye en la vida de la clase trabajadora.', 5, 16, 19.47, 1),
(7, 9788466671781, 'ROMA SOY YO', 'SANTIAGO POSTEGUILLO', 'roma-soy-yo.jpg', 'DESPUÉS DE JULIO CÉSAR, EL MUNDO NUNCA VOLVIÓ A SER EL MISMO.
Si alguna vez hubo un hombre nacido para cambiar el curso de la Historia, ese fue Julio César. Su leyenda, veinte siglos después, sigue más viva que nunca.
Roma, año 77 a.C. El cruel senador Dolabela va a ser juzgado por corrupción, pero ha contratado a los mejores abogados, ha comprado al jurado y, además, es conocido por usar la violencia contra todos los que se enfrentan a él. Nadie se atreve a ser el fiscal, hasta que de pronto, contra todo pronóstico, un joven patricio de tan solo veintitrés años acepta llevar la acusación, defender al pueblo de Roma y desafiar el poder de las élites. El nombre del desconocido abogado es Cayo Julio César.
Combinando con maestría un exhaustivo rigor histórico y una capacidad narrativa extraordinaria, Santiago Posteguillo logra sumergir al lector en el fragor de las batallas, hacerle caminar por las calles más peligrosas mientras los sicarios de los senadores acechan en cualquier esquina, vivir la gran historia de amor de Julio César con Cornelia, su primera esposa, y comprender, en definitiva, cómo fueron los orígenes del hombre tras el mito.
Hay personajes que cambian la historia del mundo, pero también hay momentos que cambian la vida de esos personajes.Roma soy yo es el relato de los extraordinarios sucesos que marcaron el destino de César.', 4, 8, 22.70, 4),
(8, 9788408252856, 'EL LIBRO NEGRO DE LAS HORAS', 'EVA GARCIA SAENZ DE URTURI', 'el-libro-negro-de-las-horas.jpg', 'Alguien que lleva muerto cuarenta años no puede ser secuestrado y, desde luego, no puede sangrar.
Vitoria, 2022. El exinspector Unai López de Ayala —alias Kraken— recibe una llamada anónima que cambiará lo que cree saber de su pasado familiar: tiene una semana para encontrar el legendario Libro Negro de las Horas, una joya bibliográfica exclusiva, si no, su madre, quien descansa en el cementerio desde hace décadas, morirá.
¿Cómo es esto posible?
Una carrera contrarreloj entre Vitoria y el Madrid de los bibliófilos para trazar el perfil criminal más importante de su vida, uno capaz de modificar el pasado, para siempre.
Me llamo Unai. Me llaman Kraken.
Aquí termina tu caza, aquí comienza la mía.
¿Y si tu madre fuera la mejor falsificadora de libros antiguos de la historia?', 4.5, 7, 19.85, 1),
(9, 9788401027062, 'LA VIOLINISTA ROJA', 'REYES MONFORTE', 'la-violinista-roja.jpg', 'Reyes Monforte regresa con su novela más ambiciosa: la apasionante historia de la española que se convirtió en la espía sovietica más importante del siglo xx.
La legendaria historia de una valerosa mujer que luchó por sus ideales más allá de la familia, el amor, la amistad y el orden mundial.
"Pero ¿quien demonios es esa mujer?" era la pregunta más escuchada en los despachos de la CIA. ¿Quien movía los hilos del espionaje mundial, frustraba operaciones de inteligencia, retorcía voluntades, mudaba de piel, encabezaba misiones imposibles, descubría secretos de Estado y dibujaba en el tablero de la Guerra Fría la amenaza de una Tercera Guerra Mundial? Esa misteriosa mujer era la española Çfrica de las Heras, quien se convirtió en la espía sovietica más importante del siglo xx.
Captada por los servicios secretos de Stalin en Barcelona durante la guerra civil española, formó parte del operativo para asesinar a Trotski en Mexico, luchó contra los nazis ejerciendo de radioperadora #violinista# en Ucrania, protagonizó la trampa de miel más fructífera del KGB al casarse con el escritor anticomunista Felisberto Hernández y crear la mayor red de agentes sovieticos en Sudamerica, dejó su impronta en el espionaje nuclear,', 3, 2, 20.80, 3),
(10, 9788448028831, 'La cuenta atrás para el verano', 'la vecina rubia','la-cuenta-atras-para-el-verano.jpg','La primera novela de la Vecina Rubia
LA VIDA SON RECUERDOS Y LOS MÍOS TIENEN NOMBRES DE PERSONA
¿Sabrías decir cuántas personas han formado parte de tu vida y cuántas han sido capaces de cambiarla? Las últimas son las que realmente importan.
Lauri, la primera y más responsable amiga de la infancia y Nacho, mi primer amor de la adolescencia. La malhumorada y siempre sincera Lucía, la calmada Sara y el sarcástico Pol. También Álex, el que siempre vuelve, y la única mujer capaz de susurrar gritando, Laura. Y por supuesto, MI PADRE, en mayúsculas.
La cuenta atrás para el verano entrelaza en el tiempo, la vida de una rubia, que soy yo, y la de las personas que han supuesto el aprendizaje más útil que atesoro, porque en el fondo, conocer a las personas más importantes de tu vida es conocerte a ti misma.
Nombres propios que me ayudaron a dar el salto desde la adolescencia a la madurez, despeinándome en el camino el pelazo, pero construyendo un cerebro debajo.
Esta novela está basada en ilusiones reales que me he inventado algunas veces. Reconocer cuáles es algo que estará dentro de cada una de nosotras.',null,3,17.99,5);



INSERT INTO `usuarios` (`id`, `usuario`,`contrasena`, `dni`,`nombre`, `apellido_1`,`apellido_2`,`direccion`,`imagen_perfil`, `telefono`) VALUES
(2, 'Nat13', 'Nat13', '11111111H','Natalia','Parra','Figueroa', 'Avd. Andalucia 3 5ºC', 'url1', 666666666),
(3, 'TamGon10', 'TamGon10', '11112111H', 'Tamara','Martin',NULL,'C/ San Juan 5 ', 'url2', 666666660),
(4, 'Ivi21', 'Ivi21', '11111131H','Iván','Zurdo', 'Perez','Camino olivar 7', 'url3', 666666661),
(5, 'Rober18', 'Rober18', '11111511H','Roberto','Parra', NULL,'Calle Velazquez 5 p2 4ªc', 'url4', 666666667);

INSERT INTO `pedido` (`id`, `fecha`,`total`, `nombre`,`apellidos`, `direccion`,`num_tarjeta`, `fecha_cad`,`cvs`,`estado`,`usuarioID`) VALUES
(1, '2022-03-05', 51.30,'Natalia', 'Parra','Avd. Andalucia 3 5ºC', 1515000019190000, 2023-12-01, 000, 'PENDIENTE DE ENVIO',2),
(2, '2022-02-20', 38.00,'Tamara', 'Martin','C/ San Juan 5 ', 1666000019190000, 2027-10-01, 000, 'PENDIENTE DE ENVIO',3),
(3, '2022-05-05', 39.70,'Iván', 'Zurdo','Camino olivar 7', 2222000019190000, 2024-09-01, 000, 'ENTREGADO',4),
(4, '2022-07-15', 56.85,'Roberto', 'Parra','Calle Velazquez 5 p2 4ªc', 5555000019190000, 2025-01-06, 000, 'CONFIRMADO',5);

INSERT INTO `compra` (`id`, `titulo`, `imagen`, `cantidad`, `precio`,`libroID`,`pedidoID`) VALUES
(1, 'ATREVETE A RETARME (LAS GUERRERAS MAXWELL, 7)','url1', 3, 51.30,1,1),
(2, 'MALAVENTURA','url2', 2, 38.00,2,2),
(3, 'COMPAÑERA','url3', 2, 39.70,3,3),
(4, 'DE SANGRE Y CENIZAS','url4', 3, 56.85,4,4);



