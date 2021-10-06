use mydb;
drop table exchange_points;
CREATE TABLE exchange_points (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) NOT NULL,
    email VARCHAR(50) NOT NULL,
    point INT NOT NULL,
    money INT NOT NULL,
	end BOOL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
