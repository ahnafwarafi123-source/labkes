-- Minimal security table untuk track active sessions
-- Mencegah session hijacking dengan validasi IP & User-Agent
CREATE TABLE IF NOT EXISTS `active_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `session_id` VARCHAR(128) UNIQUE NOT NULL,
  `user_id` INT NOT NULL,
  `username` VARCHAR(100), -- Denormalized for logging/auditing convenience
  `ip_address` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `expires_at` DATETIME NOT NULL,
  `is_valid` BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id),
  INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
