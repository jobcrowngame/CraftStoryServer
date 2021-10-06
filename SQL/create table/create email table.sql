use mydb;
drop table email;
CREATE TABLE email (
    email_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    acc VARCHAR(20) NOT NULL,
    title VARCHAR(30) NOT NULL,
    message TEXT,
    related_data TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_already_read BOOLEAN DEFAULT 0,
    is_already_received BOOLEAN DEFAULT 0,
    isDiscard BOOLEAN DEFAULT 0
);