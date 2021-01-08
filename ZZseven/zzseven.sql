/* zzseven.sql */

/* CREATE DATABASE zzseven; */

/* user */
/** type: 0=customer, 1=seller, 2=admin */
CREATE TABLE user (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(64),
    password VARCHAR(64),
    email VARCHAR(64),
    type TINYINT
);

/* type of user */
CREATE TABLE usertype (
    id TINYINT,
    name VARCHAR(16)
);

/* cart - customer */
CREATE TABLE cart (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    idCustomer INT,
    idInventory INT,
    quantity TINYINT
);

/* inventory - seller */
CREATE TABLE inventory (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    idSeller INT,
    name VARCHAR(128),
    type TINYINT,
    price DECIMAL(8,2),
    quantityTotal SMALLINT,
    quantityLeft SMALLINT
);
    
/* Type of inventory */
CREATE TABLE inventorytype (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(64),
    commission DECIMAL(4,2)
);

/* rate - inventory - seller*/
/** rate range = 0-10 */
CREATE TABLE rate (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    idCustomer INT,
    idInventory INT,
    rate DECIMAL(4,2),
    comment VARCHAR(256)
);

/* promo code */
/** conditions = the minimum spent to use this promo code */
CREATE TABLE promo (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    idSeller INT,
    code VARCHAR(8),
    dateStart DATE,
    dateEnd DATE,
    timeStart TIME,
    timeEnd TIME,
    conditions SMALLINT,
    discount SMALLINT,
    quantityTotal SMALLINT,
    quantityLeft SMALLINT
);

/* chat */
CREATE TABLE chat (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    idSender INT,
    idReceiver INT,
    message VARCHAR(256),
    datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/** SAMPLE DATA **/
/* user */
/** password=123456 */
INSERT INTO user VALUES(NULL, 'Gu Ke', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'guke@gmail.com', 0);
INSERT INTO user VALUES(NULL, 'Mai Jia', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'maijia@gmail.com', 1);
INSERT INTO user VALUES(NULL, 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'admin@gmail.com', 2);
INSERT INTO user VALUES(NULL, 'Ao Ke', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'aoke@gmail.com', 0);
INSERT INTO user VALUES(NULL, 'Shang Ren', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'shangren@yahoo.com', 1);

/* usertype */
INSERT INTO usertype VALUES(0, 'customer');
INSERT INTO usertype VALUES(1, 'seller');
INSERT INTO usertype VALUES(2, 'admin');

/* cart */
INSERT INTO cart VALUES(NULL, 1, 1, 1);
INSERT INTO cart VALUES(NULL, 1, 2, 2);

/* inventory */
INSERT INTO inventory VALUES(NULL, 2, 'Lucky Guitar', 1, 10, 30, 29);
INSERT INTO inventory VALUES(NULL, 2, 'Rock Guitar', 2, 20, 30, 28);
INSERT INTO inventory VALUES(NULL, 5, 'Haha Guitar', 2, 60, 50, 50);

/* inventorytype */
INSERT INTO inventorytype VALUES(NULL, 'Acoustic Guitar', 0.2);
INSERT INTO inventorytype VALUES(NULL, 'Classical Guitar', 0.1);

/* rate */
INSERT INTO rate  VALUES(NULL, 1, 1, 10, 'Good');
INSERT INTO rate  VALUES(NULL, 4, 1, 3, 'Too expensive');
INSERT INTO rate  VALUES(NULL, 4, 2, 5, 'Normal color I do not like');

/* promo */
INSERT INTO promo VALUES(NULL, 2, 'QWERTYUI', '2021-01-01', '2021-01-30', '00:00:00', '23:59:59', 15, 5, 10, 10);
INSERT INTO promo VALUES(NULL, 2, 'ASDFGHJK', '2021-01-01', '2021-01-30', '00:00:00', '23:59:59', 10, 5, 10, 10);

/* chat */
INSERT INTO chat (idSender, idReceiver, message) VALUES(4, 2, 'Why so expensive?');
INSERT INTO chat (idSender, idReceiver, message) VALUES(2, 4, 'no money no bb');
INSERT INTO chat (idSender, idReceiver, message) VALUES(4, 2, 'you bad bad');
INSERT INTO chat (idSender, idReceiver, message) VALUES(2, 4, '...');

INSERT INTO chat (idSender, idReceiver, message) VALUES(4, 3, 'Mai Jia scold me TT');
INSERT INTO chat (idSender, idReceiver, message) VALUES(3, 4, 'so?');
INSERT INTO chat (idSender, idReceiver, message) VALUES(4, 3, 'you two one gang punya, I want report you two');
INSERT INTO chat (idSender, idReceiver, message) VALUES(3, 4, 'I am admin, you report or I ban you? ^^');
INSERT INTO chat (idSender, idReceiver, message) VALUES(4, 3, 'sry...');
/*  */