SET NAMES 'utf8';

RENAME TABLE    attachments TO old_attachments,
                img TO old_img,
                missions TO old_missions,
                results TO old_results,
                solutions TO old_solutions,
                users TO old_users,
                videos TO old_videos;

CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mail VARCHAR(50) UNIQUE,
    password VARCHAR(50)
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE organisators (
    user_id INT UNSIGNED,
    `admin` BOOLEAN,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE teams (
    user_id INT UNSIGNED,
    name VARCHAR(30) UNIQUE,
    description TEXT,
    sk_league BOOLEAN,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE texts (
    text_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sk TEXT,
    eng TEXT
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE contexts (
    context_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE assignments (
    context_id INT UNSIGNED UNIQUE NOT NULL,
    text_id_name INT UNSIGNED,
    text_id_description INT UNSIGNED,
    `begin` DATETIME,
    `end` DATETIME,
    FOREIGN KEY (context_id) REFERENCES CONTEXTS(context_id) ON DELETE CASCADE,
    FOREIGN KEY (text_id_name) REFERENCES TEXTS(text_id) ON DELETE CASCADE,
    FOREIGN KEY (text_id_description) REFERENCES TEXTS(text_id) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE solutions (
    context_id INT UNSIGNED UNIQUE NOT NULL,
    assignment_id INT UNSIGNED,
    text TEXT,
    best BOOLEAN,
    FOREIGN KEY (context_id) REFERENCES CONTEXTS(context_id) ON DELETE CASCADE,
    FOREIGN KEY (assignment_id) REFERENCES ASSIGNMENTS(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE comments (
    comment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    solution_id INT UNSIGNED,
    user_id INT UNSIGNED,
    text TEXT,
    points FLOAT,
    FOREIGN KEY (solution_id) REFERENCES SOLUTIONS(context_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE videos (
    video_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    location_id INT UNSIGNED,
    link VARCHAR(11),
    FOREIGN KEY (location_id) REFERENCES CONTEXTS(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE programs (
    program_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    location_id INT UNSIGNED,
    link VARCHAR(30),
    FOREIGN KEY (location_id) REFERENCES CONTEXTS(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE images (
    image_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    location_id INT UNSIGNED,
    link VARCHAR(30),
    extension VARCHAR(5),
    FOREIGN KEY (location_id) REFERENCES CONTEXTS(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

/*********************************************************************************/

DELETE FROM old_users WHERE id = 97;

SET @rownum = 0;

UPDATE old_users 
SET mail = CONCAT(mail, @rownum := @rownum + 1)
WHERE mail = 'L.etnaLigaFLL@gmail.com'
ORDER BY id ASC;

INSERT INTO users (mail, password)
SELECT u.mail, u.passwd
FROM old_users u
WHERE u.`type` != 0
ORDER BY u.`type` = 2 ASC, u.id ASC;

SET @lastuserorg = (SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'letnaliga' AND TABLE_NAME = 'users')-1;

INSERT INTO organisators (user_id, `admin`)
SELECT u.user_id, u.user_id = 1
FROM users u;

INSERT INTO contexts (user_id)
SELECT 1
FROM old_missions;

SET @lastmission = (SELECT `AUTO_INCREMENT`-1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'letnaliga' AND TABLE_NAME = 'contexts')-1;

INSERT INTO texts (sk, eng)
SELECT name, null
FROM old_missions
ORDER BY id ASC;

INSERT INTO texts (sk, eng)
SELECT content, null
FROM old_missions
ORDER BY id ASC;

SET @rownum = 0;

INSERT INTO assignments (context_id, text_id_name, text_id_description, `begin`, `end`)
SELECT @rownum := @rownum+1 AS rownum, @rownum AS rownum2, @rownum + @lastmission AS desc_id, `start`, `end`
FROM old_missions
ORDER BY id ASC; 

INSERT INTO users (mail, password)
SELECT SUBSTRING_INDEX(GROUP_CONCAT(CAST(u.mail AS CHAR) ORDER BY s.id DESC), ',', 1 ) as mail, SUBSTRING_INDEX(GROUP_CONCAT(CAST(u.passwd AS CHAR) ORDER BY s.id DESC), ',', 1 ) as passwd
FROM old_users u
LEFT OUTER JOIN old_solutions s ON (s.`uid` = u.id)
WHERE u.`type` = 0
GROUP BY u.name
ORDER BY u.id ASC;

SET @rownum = @lastuserorg;

INSERT INTO teams (user_id, name, description, sk_league)
SELECT @rownum := @rownum + 1 AS rownum, u.name, null, true
FROM (  SELECT u.name FROM old_users u
        WHERE u.`type` = 0
        GROUP BY u.name
        ORDER BY u.id ASC) u;

INSERT INTO contexts (user_id)
SELECT t.user_id
FROM old_solutions s
LEFT OUTER JOIN old_users o_u ON (o_u.id = s.uid)
LEFT OUTER JOIN teams t ON (t.name COLLATE 'utf8_general_ci' = o_u.name)
GROUP BY s.id
ORDER BY s.id ASC;

SET @rownum = @lastmission;

INSERT INTO solutions (context_id, assignment_id, text, best)
SELECT @rownum := @rownum +1 AS rownum, s.id, s.content, s.win
FROM (  SELECT a.context_id AS id, s.content, s.win
        FROM old_solutions s
        LEFT OUTER JOIN old_users o_u ON (o_u.id = s.id)
        LEFT OUTER JOIN teams t ON (t.name COLLATE 'utf8_general_ci' = o_u.name)
        LEFT OUTER JOIN old_missions o_m ON (o_m.id = s.`mid`)
        LEFT OUTER JOIN texts txt ON (txt.sk COLLATE 'utf8_general_ci' = o_m.name)
        LEFT OUTER JOIN assignments a ON (a.text_id_name = txt.text_id)
        GROUP BY s.id
        ORDER BY s.id ASC) s;

INSERT INTO comments (solution_id, user_id, text, points)
SELECT s.context_id, 1, SUBSTRING_INDEX(GROUP_CONCAT(CAST(o_r.text AS CHAR)  ORDER BY o_r.id SEPARATOR '#$#$#$##'), '#$#$#$##', 1 ) as text, ROUND(AVG(o_r.points), 1) AS points
FROM old_results o_r
LEFT OUTER JOIN old_solutions o_s ON (o_s.id = o_r.sid)
LEFT OUTER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
GROUP BY s.context_id
ORDER BY s.context_id ASC;

INSERT INTO videos (location_id, link)
SELECT s.context_id, CASE (REPLACE(o_v.link, ' ', '') LIKE 'https%') WHEN TRUE THEN MID(o_v.link, 31, 11) ELSE MID(o_v.link, 30, 11) END AS link
FROM old_videos o_v
LEFT OUTER JOIN old_solutions o_s ON (o_s.id = o_v.sid)
LEFT OUTER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
ORDER BY o_v.id ASC;
