CREATE TABLE `users_type`(
	`type` VARCHAR(255),
	PRIMARY KEY (`type`)
);

INSERT INTO `users_type` (`type`)
VALUES 
	('admin'),
	('driver')
;

CREATE TABLE `company_users` (
	`user_id` INT AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL UNIQUE,
	`password` VARCHAR(255) NOT NULL,
	`type` VARCHAR(255) NOT NULL,
	`developer` BOOLEAN NOT NULL DEFAULT FALSE,
	`name` VARCHAR(255) NOT NULL,
	`surname` VARCHAR(255) NOT NULL,
	`tel` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`user_id`),
	FOREIGN KEY (`type`) REFERENCES `users_type` (`type`) ON UPDATE CASCADE
);

INSERT INTO `company_users` (`user_id`, `username`, `password`, `type`, `developer`, `name`, `surname`, `tel`)
VALUES 
	(NULL , 'matteo', sha1('prova'), 'admin', 1, 'nome', 'cogn', '0113617261'),
	(NULL, 'driv1', sha1('prova'), 'driver', 0, 'nome', 'cogn', '0113617261')
;


# Città
CREATE TABLE `cities`(
	`city` VARCHAR(255) NOT NULL,
	`prov` VARCHAR(255) NOT NULL,
	`service_cost_1` FLOAT NOT NULL,
	`service_cost_2` FLOAT NOT NULL,
	PRIMARY KEY (`city`, `prov`) #ogni città è univoca nella sua provincia
);
INSERT INTO `cities` (`city`, `prov`, `service_cost_1`, `service_cost_2`)
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
	`cl_name` VARCHAR(255) NOT NULL,
	`cl_surname` VARCHAR(255) NOT NULL,
	`cl_tel` VARCHAR(255) NOT NULL,
	`cl_mail` VARCHAR(255) UNIQUE, #da usare anche come username!
	`cl_password` VARCHAR(255),
	`cl_street` TEXT NOT NULL,
	`cl_street_n` INT NOT NULL CHECK(`cl_street_n` > 0),
	`cl_zip` INT NOT NULL,
	`cl_city` VARCHAR(255) NOT NULL,
	`cl_prov` VARCHAR(255) NOT NULL,
	`cl_lat_gps` FLOAT,
	`cl_long_gps` FLOAT,
	`cl_other_info` TEXT,
	PRIMARY KEY (`client_id`),
	FOREIGN KEY (`cl_city`, `cl_prov`) REFERENCES `cities` (`city`, `prov`) ON UPDATE CASCADE,
	CONSTRAINT unique_client UNIQUE (`cl_name`,`cl_surname`,`cl_street`,`cl_street_n`,`cl_city`, `cl_prov`)
);

INSERT INTO `clients` (`client_id`, `cl_name`, `cl_surname`, `cl_tel`, `cl_street`, `cl_street_n`, `cl_zip`, `cl_city`, `cl_prov`)
VALUES 
	(NULL , 'matteo', 'bunino', '3393999876', 'maria m.bugnone maetstra', 8, 10040, 'Almese', 'Torino'),
	(NULL , 'marco', 'rossi', '33453459876', 'roma', 10, 14980, 'Rubiana', 'Torino')
;



# Venditori
CREATE TABLE `retailers`(
	`retailer_id` INT AUTO_INCREMENT,
	`ret_name` VARCHAR(255) NOT NULL,
	`ret_owner` VARCHAR(255) NOT NULL,
	`ret_tel` VARCHAR(255) NOT NULL,
	`ret_mail` VARCHAR(255) UNIQUE, #da usare anche come username!
	`ret_password` VARCHAR(255),
	`ret_street` TEXT NOT NULL,
	`ret_street_n` INT NOT NULL CHECK(`ret_street_n` > 0),
	`ret_zip` INT NOT NULL,
	`ret_city` VARCHAR(255) NOT NULL,
	`ret_prov` VARCHAR(255) NOT NULL,
	`ret_lat_gps` FLOAT,
	`ret_long_gps` FLOAT,
	`ret_other_info` TEXT,
	PRIMARY KEY (`retailer_id`),
	FOREIGN KEY (`ret_city`, `ret_prov`) REFERENCES `cities` (`city`, `prov`) ON UPDATE CASCADE, #foreign key sulla coppia
	CONSTRAINT unique_retailer UNIQUE (`ret_name`,`ret_street`,`ret_street_n`,`ret_city`, `ret_prov`)
);
INSERT INTO `retailers` (`retailer_id`, `ret_name`, `ret_owner`, `ret_tel`, `ret_street`, `ret_street_n`, `ret_zip`, `ret_city`, `ret_prov`)
VALUES 
	(NULL , 'panetteria almese', 'tonio cartonio', '3393999876', 'maria m.bugnone maetstra', 8, 10040, 'Almese', 'Torino'),
	(NULL , 'macellaio rivera', 'chef rubio', '33453459876', 'roma', 10, 14980, 'Rubiana', 'Torino')
;

# Quando sarà implementato il fornted pubblico, devo avviungere una verifica via software che una cera email sia unica tra le 3 
# diverse tabelle di utenti


# Ordini
CREATE TABLE `orders`(
	`client_id` INT,
	`retailer_id` INT,
	`ord_date` DATE,
	`prod_list` TEXT NOT NULL,
	`ord_tot_price` FLOAT CHECK(`ord_tot_price` >= 0),
	`ord_service_cost` FLOAT CHECK(`ord_service_cost` >= 0),
	`service_id` INT,
	`bought` BOOLEAN NOT NULL DEFAULT FALSE,
	`delivered` BOOLEAN NOT NULL DEFAULT FALSE,
	PRIMARY KEY (`client_id`, `retailer_id`, `ord_date`),
	FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON UPDATE CASCADE,
	FOREIGN KEY (`retailer_id`) REFERENCES `retailers` (`retailer_id`) ON UPDATE CASCADE
);

INSERT INTO `orders` (`client_id`, `retailer_id`, `ord_date`, `prod_list`)
VALUES
	(1,1,'2020-03-10',"latte, pane, giornale")
;
