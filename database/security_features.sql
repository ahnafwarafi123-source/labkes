-- Security Features Tables
-- Created for: data_pending, activity_logs, login_logs

-- ========================================
-- Table: login_logs
-- Purpose: Track all user login attempts (success and failures)
-- ========================================
CREATE TABLE IF NOT EXISTS `login_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `status` enum('success','failed','locked') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'failed',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `failure_reason` varchar(255) DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `username` (`username`),
  KEY `status` (`status`),
  KEY `login_at` (`login_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- Table: activity_logs
-- Purpose: Track all user activities (create, read, update, delete)
-- ========================================
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL COMMENT 'create, read, update, delete, export, print, etc',
  `module` varchar(100) NOT NULL COMMENT 'kesmas, pembayaran, users, etc',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `record_id` int(11) DEFAULT NULL COMMENT 'ID of the affected record',
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'JSON format of old values before change',
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'JSON format of new values after change',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `action` (`action`),
  KEY `module` (`module`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- Table: data_pending
-- Purpose: Track data that requires approval before use
-- ========================================
CREATE TABLE IF NOT EXISTS `data_pending` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `data_type` varchar(50) NOT NULL COMMENT 'kesmas, pembayaran, alat_lab, etc',
  `data_id` int(11) NOT NULL COMMENT 'Reference to actual data record',
  `status` enum('pending','approved','rejected','revision_needed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `requested_by` int(11) NOT NULL COMMENT 'User who submitted the data',
  `approved_by` int(11) DEFAULT NULL COMMENT 'User who approved/rejected',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Reason for rejection or needed revision',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `approved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `data_type` (`data_type`),
  KEY `status` (`status`),
  KEY `requested_by` (`requested_by`),
  KEY `approved_by` (`approved_by`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add sample data (optional - for testing)
-- INSERT INTO `login_logs` (`username`, `status`, `ip_address`, `failure_reason`) VALUES
-- ('admin', 'success', '127.0.0.1', NULL),
-- ('user1', 'failed', '192.168.1.1', 'Invalid password'),
-- ('user2', 'locked', '10.0.0.5', 'Too many failed attempts');
