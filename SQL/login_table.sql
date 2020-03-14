CREATE TABLE `users_type`(
	`type` VARCHAR(20),
	PRIMARY KEY (`type`)
);

INSERT INTO `users_type` (`type`)
VALUES 
	('admin'),
	('driver')
;

CREATE TABLE `company_users` (
	`user_id` INT AUTO_INCREMENT,
	`username` VARCHAR(64) NOT NULL UNIQUE,
	`password` VARCHAR(64) NOT NULL,
	`type` VARCHAR(64) NOT NULL,
	`nome` VARCHAR(30) NOT NULL,
	`cognome` VARCHAR(30) NOT NULL,
	`tel` VARCHAR(20) NOT NULL,
	PRIMARY KEY (`user_id`),
	FOREIGN KEY (`type`) REFERENCES `users_type` (`type`) ON UPDATE CASCADE
);

INSERT INTO `company_users` (`user_id`, `username`, `password`, `type`, `nome`, `cognome`, `tel`)
VALUES 
	(NULL , 'matteo', sha1('prova'), 'admin', 'nome', 'cogn', '0113617261'),
	(NULL, 'driv1', sha1('prova'), 'driver', 'nome', 'cogn', '0113617261')
;


# Città
CREATE TABLE `cities`(
	`city` VARCHAR(64) NOT NULL,
	`prov` VARCHAR(64) NOT NULL,
	`costo_servizio_1` FLOAT NOT NULL,
	`costo_servizio_2` FLOAT NOT NULL,
	PRIMARY KEY (`city`, `prov`) #ogni città è univoca nella sua provincia
);
INSERT INTO `cities` (`city`, `prov`, `costo_servizio_1`, `costo_servizio_2`)
VALUES
	("Almese", 'Torino', 1.5, 3.5),
	("Rubiana", 'Torino', 2, 4.5),
	("Villar Dora", 'Torino', 1.5, 3.5),
	("Rosta", 'Torino', 2, 4.5),
    ("Sant'Ambrogio di Torino", 'Torino', 2, 4.5),
    ("Avigliana", 'Torino', 2, 4.5)
;


# Info clienti
CREATE TABLE `clients`(
	`client_id` INT AUTO_INCREMENT,
	`nome` VARCHAR(30) NOT NULL,
	`cognome` VARCHAR(30) NOT NULL,
	`tel` VARCHAR(20) NOT NULL,
	`mail` VARCHAR(60) UNIQUE, #da usare anche come username!
	`password` VARCHAR(60),
	`via` TEXT NOT NULL,
	`n_civico` INT NOT NULL CHECK(`n_civico` > 0),
	`interno` INT,
	`cap` INT NOT NULL,
	`city` VARCHAR(60) NOT NULL,
	`prov` VARCHAR(30) NOT NULL,
	`frazione` VARCHAR(30),
	`lat_gps` FLOAT,
	`long_gps` FLOAT,
	`altre_info` TEXT,
	PRIMARY KEY (`client_id`),
	FOREIGN KEY (`city`, `prov`) REFERENCES `cities` (`city`, `prov`) ON UPDATE CASCADE,
	CONSTRAINT unique_client UNIQUE (`nome`,`cognome`,`via`,`n_civico`,`city`, `prov`)
);

INSERT INTO `clients` (`client_id`, `nome`, `cognome`, `tel`, `via`, `n_civico`, `interno`, `cap`, `city`, `prov`)
VALUES 
	(NULL , 'matteo', 'bunino', '3393999876', 'maria m.bugnone maetstra', 8, NULL, 10040, 'ALMESE', 'TORINO'),
	(NULL , 'marco', 'rossi', '33453459876', 'roma', 10, NULL, 14980, 'RUBIANA', 'TORINO')
;



# Venditori
CREATE TABLE `retailers`(
	`retailer_id` INT AUTO_INCREMENT,
	`nome` VARCHAR(30) NOT NULL,
	`proprietario` VARCHAR(30) NOT NULL,
	`tel` VARCHAR(20) NOT NULL,
	`mail` VARCHAR(60) UNIQUE, #da usare anche come username!
	`password` VARCHAR(60),
	`via` TEXT NOT NULL,
	`n_civico` INT NOT NULL CHECK(`n_civico` > 0),
	`interno` INT,
	`cap` INT NOT NULL,
	`city` VARCHAR(60) NOT NULL,
	`prov` VARCHAR(30) NOT NULL,
	`frazione` VARCHAR(30),
	`lat_gps` FLOAT,
	`long_gps` FLOAT,
	`altre_info` TEXT,
	PRIMARY KEY (`retailer_id`),
	FOREIGN KEY (`city`, `prov`) REFERENCES `cities` (`city`, `prov`) ON UPDATE CASCADE, #foreign key sulla coppia
	CONSTRAINT unique_retailer UNIQUE (`nome`,`via`,`n_civico`,`city`, `prov`)
);
INSERT INTO `retailers` (`retailer_id`, `nome`, `proprietario`, `tel`, `via`, `n_civico`, `interno`, `cap`, `city`, `prov`)
VALUES 
	(NULL , 'panetteria almese', 'tonio cartonio', '3393999876', 'maria m.bugnone maetstra', 8, NULL, 10040, 'ALMESE', 'TORINO'),
	(NULL , 'macellaio rivera', 'chef rubio', '33453459876', 'roma', 10, NULL, 14980, 'RUBIANA', 'TORINO')
;

# Quando sarà implementato il fornted pubblico, devo avviungere una verifica via software che una cera email sia unica tra le 3 
# diverse tabelle di utenti


# Ordini
CREATE TABLE `orders`(
	`client_id` INT,
	`retailer_id` INT,
	`date` DATE,
	`prod_list` TEXT NOT NULL,
	`tot_price` FLOAT CHECK(`tot_price` >= 0),
	`service_cost` FLOAT CHECK(`service_cost` >= 0),
	`service_id` INT,
	`bought` BOOLEAN,
	`delivered` BOOLEAN,
	PRIMARY KEY (`client_id`, `retailer_id`, `date`),
	FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON UPDATE CASCADE,
	FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`) ON UPDATE CASCADE
);

INSERT INTO `orders` (`client_id`, `retailer_id`, `date`, `prod_list`)
VALUES
	(1,1,'2020-03-10',"latte, pane, asda")
;
