CREATE TABLE `user` (
    `username` VARCHAR(100) PRIMARY KEY,
    `pseudo` VARCHAR(100) NOT NULL,
    `inscriptionDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `activationDate` DATETIME
);