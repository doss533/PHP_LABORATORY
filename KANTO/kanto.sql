CREATE DATABASE IF NOT EXISTS kanto;
USE kanto;

CREATE TABLE IF NOT EXISTS places (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    rating DECIMAL(2,1) NOT NULL,
    image VARCHAR(500) NOT NULL,
    address VARCHAR(255) NOT NULL,
    contact VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    place_id INT NOT NULL,
    reviewer_name VARCHAR(255) NOT NULL,
    comment TEXT NOT NULL,
    rating INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE
);

INSERT INTO places (name, category, location, description, rating, image, address, contact) VALUES
('Kape sa Kanto', 'Café', 'Nasugbu', 'A perfect place to sip local coffee while watching the sunset. Friendly vibes and great pastries.', 4.8, 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', '123 Sunset Blvd, Nasugbu, Batangas', '0917-123-4567'),
('Sunset Tambayan', 'Beach', 'Nasugbu', 'Relax on the sand with a cold drink. The best local beach spot for a weekend getaway with the barkada.', 4.7, 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'Beachfront, Nasugbu, Batangas', '0918-987-6543'),
('Barkada Food Hub', 'Food', 'Lipa', 'Affordable and delicious street food and local delicacies. Open until midnight for your late-night cravings.', 4.5, 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'Main St, Lipa, Batangas', '0922-333-4444'),
('Study Nook Café', 'Study', 'Tagaytay', 'Quiet atmosphere, fast WiFi, and unlimited coffee. The ideal environment for students and freelancers.', 4.9, 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'Aguinaldo Hwy, Tagaytay City', '0999-888-7777'),
('Kanto Grill', 'Food', 'Batangas City', 'Best ihaw-ihaw in town. Try our famous pork barbecue and isaw.', 4.6, 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'Rizal Ave, Batangas City', '0919-555-6666');

INSERT INTO reviews (place_id, reviewer_name, comment, rating) VALUES
(1, 'Sample User', 'Perfect place to chill with friends after class.', 5),
(1, 'Another User', 'Great coffee and relaxing atmosphere.', 4),
(4, 'Student 101', 'Lifesaver during midterms! Fast internet.', 5);
