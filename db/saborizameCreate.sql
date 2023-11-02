#Creo la base de datos SaborizameBD.
CREATE DATABASE if NOT EXISTS saborizamebd DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

USE saborizamebd;

CREATE TABLE if NOT EXISTS usuario (
    email VARCHAR(256) NOT NULL PRIMARY KEY,
    contrasena VARCHAR(128) NOT NULL,
    alias VARCHAR(32) NOT NULL,
    rol ENUM('tur', 'rest', 'admin') NOT NULL,
    fotoperfilologo VARCHAR(256),
    estadologico ENUM('activo', 'inactivo') NOT NULL
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS barrio (
    ciudad VARCHAR(256) NOT NULL,
    nombrebarrio VARCHAR(256) NOT NULL,
    PRIMARY KEY (ciudad, nombrebarrio)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS restaurante (
    emailrest VARCHAR(256) NOT NULL PRIMARY KEY,
    visibilidadperfil ENUM('visible', 'invisible') NOT NULL,
    dirrestaurante VARCHAR(256) NOT NULL,
    nombrerestaurante VARCHAR(256) NOT NULL,
    psiri INT(2) NOT NULL,
    emichelin ENUM('0', '1', '2', '3') NOT NULL,
    tdoro INT(2) NOT NULL,
    ciudad VARCHAR(256) NOT NULL,
    nombrebarrio VARCHAR(256) NOT NULL,
    iniciomembresia DATE,
    finmembresia DATE,
    tipomembresia ENUM('anual', 'mensual', 'bianual'),
    estadomembresia ENUM('vigente', 'rechazada', 'pendiente', 'vencida'),
    rutapdf VARCHAR(256) NOT NULL,
    tipocomida VARCHAR(256) NOT NULL,
    latitudrestaurante VARCHAR(256),
    longitudrestaurante VARCHAR(256),
    descrrestaurante VARCHAR(256),
    promedio_resenas INT, 
    comprobante_pago VARCHAR(256),
    iniciorenovacion DATE,
    finrenovacion DATE,
    tiporenovacion ENUM('anual', 'mensual', 'bianual'),
    estadorenovacion ENUM('valida', 'rechazada', 'pendiente'),
    mensajemembresia VARCHAR(256),
    comprobante_pago_renovacion VARCHAR(256),
    telefono1 VARCHAR(9),
    telefono2 VARCHAR(9),
    trestaurante ENUM(
        'rbuffet',
        'rcrapida',
        'rcrcasual',
        'rautor',
        'rgourmet',
        'rtematico',
        'rpllevar'
    ) NOT NULL, 
    
    CONSTRAINT fk_usuario_restaurante FOREIGN KEY (emailrest) REFERENCES usuario(email),
    CONSTRAINT fk_barrio_restaurante FOREIGN KEY (ciudad, nombrebarrio) REFERENCES barrio(ciudad, nombrebarrio)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS nacionalidadrestaurante (
    idnacion int(11) NOT NULL,
    nacionrestaurante VARCHAR(250) NOT NULL,
    PRIMARY KEY (idnacion)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS es (
    emailrest VARCHAR(256) NOT NULL,
    idnacion int(11) NOT NULL,
    PRIMARY KEY (emailrest),
    CONSTRAINT fk_restaurante_es FOREIGN KEY (emailrest) REFERENCES restaurante(emailrest),
    CONSTRAINT fk_restaurante_nacion FOREIGN KEY (idnacion) REFERENCES nacionalidadrestaurante(idnacion)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS horario (
    dia ENUM(
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo'
    ) NOT NULL,
    emailrest VARCHAR(256) NOT NULL,
    hapertura TIME,
    hcierre TIME,
    abiertocerrado ENUM('abierto', 'cerrado') NOT NULL,
    PRIMARY KEY (dia, emailrest),
    CONSTRAINT fk_restaurante_horario FOREIGN KEY (emailrest) REFERENCES restaurante(emailrest)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS turista (
    emailtur VARCHAR(256) NOT NULL PRIMARY KEY,
    CONSTRAINT fk_usuario_turista FOREIGN KEY (emailtur) REFERENCES usuario(email)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS administrador(
    emailadmin VARCHAR(256) NOT NULL PRIMARY KEY,
    CONSTRAINT fk_usuario_administrador FOREIGN KEY (emailadmin) REFERENCES usuario(email)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS hotel (
    nombrehotel VARCHAR(256) NOT NULL,
    dirhotel VARCHAR(256) NOT NULL,
    PRIMARY KEY (dirhotel)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS hospeda (
    emailtur VARCHAR(256) NOT NULL,
    finiciohospedaje DATETIME NOT NULL,
    ffinhospedaje DATETIME NOT NULL,
    dirhotel VARCHAR(256) NOT NULL,
    ciudad VARCHAR(256) NOT NULL,
    nombrebarrio VARCHAR(256) NOT NULL,
    PRIMARY KEY (
        emailtur,
        finiciohospedaje, 
        dirhotel
    ),
    CONSTRAINT fk_hotel_hospeda FOREIGN KEY (dirhotel) REFERENCES hotel(dirhotel),
    CONSTRAINT fk_turista_hospeda FOREIGN KEY (emailtur) REFERENCES turista(emailtur),
    CONSTRAINT fk_barrio_hospeda FOREIGN KEY (ciudad, nombrebarrio) REFERENCES barrio(ciudad, nombrebarrio)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS asistencia (
    emailtur VARCHAR(256) NOT NULL,
    emailrest VARCHAR(256) NOT NULL,
    validaresena ENUM('si', 'no') NOT NULL,
    finiciohospedaje DATETIME NOT NULL,
    PRIMARY KEY (emailrest, emailtur, finiciohospedaje),
    CONSTRAINT fk_restaurante_asistencia FOREIGN KEY (emailrest) REFERENCES restaurante(emailrest),
    CONSTRAINT fk_hospeda_asistencia FOREIGN KEY (emailtur, finiciohospedaje) REFERENCES hospeda(emailtur, finiciohospedaje)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS promocion (
    idpromocion VARCHAR(9) NOT NULL,
    emailrest VARCHAR(256) NOT NULL,
    nombrepromocion VARCHAR(256) NOT NULL,
    descrpromocion VARCHAR(256) NOT NULL,
    fyhfinpromo DATETIME NOT NULL,
    fyhiniciopromo DATETIME NOT NULL,
    imagenpromocion VARCHAR(256),
    PRIMARY KEY (idpromocion, emailrest),
    CONSTRAINT fk_restaurante_promocion FOREIGN KEY (emailrest) REFERENCES restaurante(emailrest)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS resena(
    emailtur VARCHAR(256) NOT NULL,
    emailrest VARCHAR(256) NOT NULL,
    restorangral ENUM('1', '2', '3', '4', '5') NOT NULL,
    instalaciones ENUM('1', '2', '3', '4', '5') NOT NULL,
    menugastronomico ENUM('1', '2', '3', '4', '5') NOT NULL,
    atencion ENUM('1', '2', '3', '4', '5') NOT NULL,
    fyhpuntaje DATETIME,
    PRIMARY KEY (emailtur, emailrest),
    CONSTRAINT fk_hospeda_resena FOREIGN KEY (emailtur) REFERENCES hospeda(emailtur),
    CONSTRAINT fk_restaurante_resena FOREIGN KEY (emailrest) REFERENCES restaurante(emailrest)
)ENGINE=Aria DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE USER 'usuarioproducto'@'localhost'
IDENTIFIED BY 'Proyectoits2023';
GRANT INSERT, UPDATE, SELECT ON saborizamebd.* TO 'usuarioproducto'@'localhost';
FLUSH PRIVILEGES;