drop table smartphone;

create table smathphone (
    id INTEGER NOT NULL auto_increment PRIMARY KEY,
    name VARCHAR(100),
    price FLOAT(10,2),
    screen_id INT(11),
    battery_id INT(11)
);

DELETE FROM smathphone;
insert into smathphone (name, price, screen_id, battery_id) values ('iphone', 12000, 63, 59),
                                                              ('samsung', 11000, 64, 60),
                                                              ('xiaomi', 9000, 65, 61),
                                                              ('honor', 8000, 66, 62),
                                                              ('oppo', 7000, 65, 60);