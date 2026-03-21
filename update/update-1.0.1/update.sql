-- Database update for Userman-ROS7 v1.0.1
-- Run this on your production database

-- Create Settings table if it doesn't exist
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP
);

-- Insert essential app settings if they don't exist
INSERT IGNORE INTO settings (setting_key, setting_value) VALUES 
('app_name', 'UserMan ROS7'),
('app_logo', 'assets/img/mangoteklogo.png'),
('ref_code', 'WELCOME-2026'),
('portal_title', 'User Portal'),
('portal_description', 'MikroTik User Manager Portal');
