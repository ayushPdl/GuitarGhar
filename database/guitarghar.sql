-- GuitarGhar schema for shared hosting (ezyro / unaux / InfinityFree)
--
-- Do NOT run CREATE DATABASE here — the host already created your DB
-- (e.g. ezyro_42583908_guitarghar). In phpMyAdmin:
--   1. Click YOUR database name in the left sidebar
--   2. Import this file
--
-- In includes/config.php set db_name to THAT exact database name.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Users
CREATE TABLE IF NOT EXISTS users (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    full_name    VARCHAR(100)  NOT NULL,
    email        VARCHAR(100)  NOT NULL UNIQUE,
    password     VARCHAR(255)  NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Lessons content
CREATE TABLE IF NOT EXISTS lessons (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    lesson_id    VARCHAR(10)   NOT NULL UNIQUE,
    level        ENUM('beginner','intermediate','advanced') NOT NULL,
    sort_order   INT           NOT NULL DEFAULT 0,
    title        VARCHAR(150)  NOT NULL,
    description  TEXT          NOT NULL,
    video_url    VARCHAR(255)  DEFAULT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Lesson progress per user
CREATE TABLE IF NOT EXISTS lesson_progress (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT           NOT NULL,
    lesson_id    VARCHAR(10)   NOT NULL,
    completed    TINYINT(1)    DEFAULT 1,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_lesson (user_id, lesson_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Guitar builds
CREATE TABLE IF NOT EXISTS guitar_builds (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT           NOT NULL,
    shape        VARCHAR(50)   NOT NULL,
    color        VARCHAR(10)   NOT NULL,
    body_wood    VARCHAR(50)   NOT NULL,
    neck_wood    VARCHAR(50)   NOT NULL,
    fingerboard  VARCHAR(50)   NOT NULL,
    pickups      VARCHAR(50)   NOT NULL,
    bridge       VARCHAR(50)   NOT NULL,
    hardware     VARCHAR(50)   NOT NULL,
    saved_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- AI Recommendation history
CREATE TABLE IF NOT EXISTS recommendations (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT           NOT NULL,
    skill_level  VARCHAR(50)   NOT NULL,
    genre        VARCHAR(50)   NOT NULL,
    guitar_type  VARCHAR(50)   NOT NULL,
    budget       VARCHAR(50)   NOT NULL,
    extra_note   TEXT,
    result       TEXT          NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- Seed lessons (skip rows that already exist)
INSERT IGNORE INTO `lessons` (`id`, `lesson_id`, `level`, `sort_order`, `title`, `description`, `video_url`, `created_at`) VALUES
(1, 'beg-01', 'beginner', 1, 'Your First Guitar Chords', 'Learn the very first chords every beginner needs: A, D, and E. We will cover proper finger placement and how to switch between them smoothly.', NULL, '2026-08-05 07:52:28'),
(2, 'beg-02', 'beginner', 2, 'How to Hold the Guitar & Pick', 'Learn the correct posture, how to hold the guitar, and the proper way to grip the pick for clean, comfortable playing.', NULL, '2026-08-05 07:52:28'),
(3, 'beg-03', 'beginner', 3, 'Open Chords: G, C and D Major', 'Master the essential open chords G, C, and D. These are the building blocks for thousands of popular songs.', NULL, '2026-08-05 07:52:28'),
(4, 'beg-04', 'beginner', 4, 'Strumming Patterns for Beginners', 'Learn basic down and up strumming patterns and how to keep a steady rhythm so your playing sounds musical right away.', NULL, '2026-08-05 07:52:28'),
(5, 'beg-05', 'beginner', 5, 'Reading Chord Diagrams & Tabs', 'Understand how to read chord diagrams and guitar tablature so you can learn songs on your own.', NULL, '2026-08-05 07:52:28'),
(6, 'beg-06', 'beginner', 6, 'The E Minor & A Minor Chords', 'Add the emotional minor chords to your toolkit and practice smooth transitions between major and minor shapes.', NULL, '2026-08-05 07:52:28'),
(7, 'beg-07', 'beginner', 7, 'Changing Chords Quickly', 'Practical exercises to help you switch between chords faster and with less buzzing or hesitation.', NULL, '2026-08-05 07:52:28'),
(8, 'beg-08', 'beginner', 8, 'Introduction to Fingerpicking', 'Start fingerpicking with simple patterns using your thumb and fingers for a fuller, warmer sound.', NULL, '2026-08-05 07:52:28'),
(9, 'beg-09', 'beginner', 9, 'Your First Song: 4-Chord Progression', 'Put everything together and play a full song using the classic G, D, Em, C progression.', NULL, '2026-08-05 07:52:28'),
(10, 'beg-10', 'beginner', 10, 'Power Chords & Palm Muting', 'Learn moveable power chords and palm muting to get a heavier, rock-ready sound.', NULL, '2026-08-05 07:52:28'),
(11, 'int-01', 'intermediate', 1, 'Barre Chords Masterclass', 'Learn the essential barre chord shapes (E and A shapes) and build the finger strength to play them cleanly up the neck.', NULL, '2026-08-05 07:52:28'),
(12, 'int-02', 'intermediate', 2, 'Pentatonic Scale: The Foundation', 'Master the minor pentatonic scale in the first position and start improvising meaningful solos.', NULL, '2026-08-05 07:52:28'),
(13, 'int-03', 'intermediate', 3, 'Soloing with the Blues Scale', 'Add the blues note to the pentatonic scale and learn classic blues licks and phrasing.', NULL, '2026-08-05 07:52:28'),
(14, 'int-04', 'intermediate', 4, 'Major Scale & CAGED System', 'Learn the major scale and the CAGED system to visualize the entire fretboard.', NULL, '2026-08-05 07:52:28'),
(15, 'int-05', 'intermediate', 5, 'Bending, Vibrato & Hammer-Ons', 'Develop expressive techniques: string bends, vibrato, hammer-ons, and pull-offs for vocal-like phrasing.', NULL, '2026-08-05 07:52:28'),
(16, 'int-06', 'intermediate', 6, 'Intermediate Strumming & Rhythm', 'Take your rhythm playing further with syncopation, muted strums, and more complex patterns.', NULL, '2026-08-05 07:52:28'),
(17, 'int-07', 'intermediate', 7, 'Chord Voicings & Inversions', 'Sick of the same open chords? Learn new voicings and inversions to make your comping more interesting.', NULL, '2026-08-05 07:52:28'),
(18, 'int-08', 'intermediate', 8, 'Triads Across the Neck', 'Learn major and minor triads in all inversions and use them to build solos and chord melodies.', NULL, '2026-08-05 07:52:28'),
(19, 'int-09', 'intermediate', 9, 'Alternate Picking Technique', 'Develop speed and precision with alternate picking exercises and scale sequences.', NULL, '2026-08-05 07:52:28'),
(20, 'int-10', 'intermediate', 10, 'Playing with a Metronome', 'Build rock-solid timing and groove by practicing scales, chords, and riffs with a metronome.', NULL, '2026-08-05 07:52:28'),
(21, 'adv-01', 'advanced', 1, 'Sweep Picking Fundamentals', 'Learn the fundamentals of sweep picking for clean arpeggios and fast, fluid runs across the neck.', NULL, '2026-08-05 07:52:28'),
(22, 'adv-02', 'advanced', 2, 'Modes of the Major Scale', 'Understand and apply the seven modes (Ionian, Dorian, Phrygian, Lydian, Mixolydian, Aeolian, Locrian).', NULL, '2026-08-05 07:52:28'),
(23, 'adv-03', 'advanced', 3, 'Advanced Chord Theory', 'Build extended chords: 7ths, 9ths, 11ths, and 13ths, and understand how to use them in progressions.', NULL, '2026-08-05 07:52:28'),
(24, 'adv-04', 'advanced', 4, 'Approach to Jazz Guitar', 'Learn jazz voicings, walking bass lines, and the language of jazz improvisation.', NULL, '2026-08-05 07:52:28'),
(25, 'adv-05', 'advanced', 5, 'Fretboard Mastery & Transposition', 'Connect all scale positions and learn to transpose any idea into any key instantly.', NULL, '2026-08-05 07:52:28'),
(26, 'adv-06', 'advanced', 6, 'Chord-Melody Arranging', 'Learn to combine melody and chords to arrange full solo guitar versions of songs.', NULL, '2026-08-05 07:52:28'),
(27, 'adv-07', 'advanced', 7, 'Tapping Techniques', 'Master two-handed tapping for dramatic, modern soloing effects.', NULL, '2026-08-05 07:52:28'),
(28, 'adv-08', 'advanced', 8, 'Blues & Jazz Turnarounds', 'Learn classic 12-bar turnaround progressions and how to improvise over them.', NULL, '2026-08-05 07:52:28'),
(29, 'adv-09', 'advanced', 9, 'Improvisation Strategies', 'Develop a complete approach to improvising over any chord progression with confidence.', NULL, '2026-08-05 07:52:28'),
(30, 'adv-10', 'advanced', 10, 'Performance & Stage Presence', 'Polish your performance skills, from gear setup to stage presence and dealing with nerves.', NULL, '2026-08-05 07:52:28');
