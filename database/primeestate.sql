CREATE DATABASE IF NOT EXISTS primeestate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE primeestate;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(160) NOT NULL UNIQUE,
 phone VARCHAR(30),
 password VARCHAR(255) NOT NULL,
 role ENUM('user','admin') DEFAULT 'user',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE properties (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(180) NOT NULL,
 description TEXT,
 type ENUM('House','Apartment','Villa','Commercial','Plot') NOT NULL,
 listing_type ENUM('Buy','Rent') DEFAULT 'Buy',
 city VARCHAR(100) NOT NULL,
 address VARCHAR(255),
 price DECIMAL(14,2) NOT NULL,
 area INT DEFAULT 0,
 bedrooms INT DEFAULT 0,
 bathrooms INT DEFAULT 0,
 parking INT DEFAULT 0,
 image VARCHAR(255),
 status ENUM('Available','Sold','Rented') DEFAULT 'Available',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE favorites (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 property_id INT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY unique_fav(user_id,property_id),
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE CASCADE
);

CREATE TABLE enquiries (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NULL,
 property_id INT NULL,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(160) NOT NULL,
 phone VARCHAR(30),
 subject VARCHAR(180),
 message TEXT NOT NULL,
 status ENUM('New','Contacted','Closed') DEFAULT 'New',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
 FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE SET NULL
);

CREATE TABLE agents (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 designation VARCHAR(150),
 experience VARCHAR(80),
 phone VARCHAR(30),
 email VARCHAR(160),
 image VARCHAR(255)
);

INSERT INTO users(name,email,phone,password,role) VALUES
('Administrator','admin@dreamnest.com','9876543210',SHA2('admin123',256),'admin');

INSERT INTO properties(title,description,type,listing_type,city,address,price,area,bedrooms,bathrooms,parking,image) VALUES
('Modern Luxury Villa','Premium family residence with modern interiors, garden and secure parking.','Villa','Buy','Ahmedabad','SG Highway, Ahmedabad',18500000,2450,4,3,2,'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=85'),
('Skyline Premium Apartment','Modern apartment with excellent city connectivity.','Apartment','Buy','Ahmedabad','Prahlad Nagar, Ahmedabad',9200000,1720,3,2,1,'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=85'),
('Garden Family Home','Spacious family home in a peaceful residential area.','House','Buy','Gandhinagar','Sector 21, Gandhinagar',12500000,2150,4,3,2,'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=85'),
('Contemporary Villa','Large luxury villa with premium amenities.','Villa','Buy','Vadodara','Alkapuri, Vadodara',21000000,3100,5,4,3,'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1200&q=85'),
('Premium City Flat','Comfortable apartment suitable for families and professionals.','Apartment','Rent','Surat','Vesu, Surat',28000,1250,2,2,1,'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1200&q=85'),
('Urban Studio Apartment','Compact modern studio apartment for urban living.','Apartment','Rent','Ahmedabad','Satellite, Ahmedabad',18000,720,1,1,1,'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=85');

INSERT INTO agents(name,designation,experience,phone,email,image) VALUES
('Ananya Shah','Senior Property Consultant','8+ Years','9876500011','ananya@dreamnest.com','https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=500&q=85'),
('Rahul Mehta','Residential Property Expert','7+ Years','9876500012','rahul@dreamnest.com','https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=500&q=85'),
('Priya Patel','Commercial Advisor','9+ Years','9876500013','priya@dreamnest.com','https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=500&q=85'),
('Arjun Desai','Luxury Property Specialist','10+ Years','9876500014','arjun@dreamnest.com','https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=500&q=85');
