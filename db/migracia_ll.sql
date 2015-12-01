SET NAMES 'utf8';
SET FOREIGN_KEY_CHECKS=0;

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
    validated BOOLEAN,
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
    context_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE assignments (
    context_id INT UNSIGNED UNIQUE NOT NULL,
    text_id_name INT UNSIGNED,
    text_id_description INT UNSIGNED,
    `begin` DATETIME,
    `end` DATETIME,
    `year` INTEGER,
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
    context_id INT UNSIGNED,
    link VARCHAR(11),
    FOREIGN KEY (context_id) REFERENCES CONTEXTS(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE programs (
    program_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    context_id INT UNSIGNED,
    original_name VARCHAR(100),
    FOREIGN KEY (context_id) REFERENCES CONTEXTS(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE images (
    image_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    context_id INT UNSIGNED,
    original_name VARCHAR(100),
    FOREIGN KEY (context_id) REFERENCES CONTEXTS(context_id) ON DELETE SET NULL
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

ALTER TABLE users AUTO_INCREMENT = 1;

SET @lastuserorg = (SELECT `AUTO_INCREMENT`-1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = (SELECT DATABASE() FROM DUAL) AND TABLE_NAME = 'users');

INSERT INTO organisators (user_id, `admin`, validated)
SELECT u.user_id, u.user_id = 1, TRUE
FROM users u
ORDER BY u.user_id;

INSERT INTO contexts (user_id)
SELECT 1
FROM old_missions;

ALTER TABLE contexts AUTO_INCREMENT = 1;

SET @lastmission = (SELECT `AUTO_INCREMENT`-1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = (SELECT DATABASE() FROM DUAL) AND TABLE_NAME = 'contexts');

INSERT INTO texts (sk, eng)
SELECT name, null
FROM old_missions
ORDER BY id ASC;

ALTER TABLE texts AUTO_INCREMENT = 1;

INSERT INTO texts (sk, eng)
SELECT content, null
FROM old_missions
ORDER BY id ASC;

SET @rownum = 0;

INSERT INTO assignments (context_id, text_id_name, text_id_description, `begin`, `end`, `year`)
SELECT @rownum := @rownum+1 AS rownum, @rownum AS rownum2, @rownum + @lastmission AS desc_id, `start`, `end`, YEAR(`start`)
FROM old_missions
ORDER BY id ASC; 

INSERT INTO users (mail, password)
SELECT SUBSTRING_INDEX(GROUP_CONCAT(CAST(u.mail AS CHAR) ORDER BY s.id DESC), ',', 1 ) as mail, SUBSTRING_INDEX(GROUP_CONCAT(CAST(u.passwd AS CHAR) ORDER BY s.id DESC), ',', 1 ) as passwd
FROM old_users u
LEFT OUTER JOIN old_solutions s ON (s.`uid` = u.id)
WHERE u.`type` = 0
GROUP BY u.name
ORDER BY MIN(u.id) ASC;

ALTER TABLE users AUTO_INCREMENT = 1;

SET @rownum = @lastuserorg;

INSERT INTO teams (user_id, name, description, sk_league)
SELECT @rownum := @rownum + 1 AS rownum, u.name, null, true
FROM (  SELECT u.name, MIN(u.id) id
        FROM old_users u
        WHERE u.`type` = 0
        GROUP BY u.name) u
ORDER BY u.id ASC;

INSERT INTO contexts (user_id)
SELECT t.user_id
FROM old_solutions o_s
LEFT OUTER JOIN old_users o_u ON (o_u.id = o_s.uid)
LEFT OUTER JOIN teams t ON (t.name COLLATE 'utf8_general_ci' = o_u.name)
GROUP BY o_s.id
ORDER BY o_s.id ASC;

SET @rownum = @lastmission;

INSERT INTO solutions (context_id, assignment_id, text, best)
SELECT @rownum := @rownum +1 AS rownum, s.assignment_id, s.content, s.win
FROM (  SELECT a.context_id AS assignment_id, o_s.content, o_s.win, o_s.id
        FROM old_solutions o_s
        LEFT OUTER JOIN old_users o_u ON (o_u.id = o_s.id)
        LEFT OUTER JOIN teams t ON (t.name COLLATE 'utf8_general_ci' = o_u.name)
        LEFT OUTER JOIN old_missions o_m ON (o_m.id = o_s.`mid`)
        LEFT OUTER JOIN texts txt ON (txt.sk COLLATE 'utf8_general_ci' = o_m.name)
        LEFT OUTER JOIN assignments a ON (a.text_id_name = txt.text_id)
        GROUP BY o_s.id) s
ORDER BY s.id ASC;

INSERT INTO comments (solution_id, user_id, text, points)
SELECT s.context_id, 1, SUBSTRING_INDEX(GROUP_CONCAT(CAST(o_r.text AS CHAR)  ORDER BY o_r.id SEPARATOR '#$#$#$##'), '#$#$#$##', 1 ) as text, ROUND(AVG(o_r.points), 2) AS points
FROM old_results o_r
LEFT OUTER JOIN old_solutions o_s ON (o_s.id = o_r.sid)
LEFT OUTER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
GROUP BY s.context_id
ORDER BY s.context_id ASC;

INSERT INTO videos (context_id, link)
SELECT s.context_id, CASE (REPLACE(o_v.link, ' ', '') LIKE 'https%') WHEN TRUE THEN MID(o_v.link, 31, 11) ELSE MID(o_v.link, 30, 11) END AS link
FROM old_videos o_v
LEFT OUTER JOIN old_solutions o_s ON (o_s.id = o_v.sid)
LEFT OUTER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
ORDER BY o_v.id ASC;

/****************************************************************************************/
INSERT INTO images (context_id, original_name) 
SELECT a.context_id, x.file_name 
FROM texts txt 
INNER JOIN assignments a ON (a.text_id_name = txt.text_id) 
INNER JOIN (
    SELECT '2013.11_.18_.20_.18_.16_.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/2013.11_.18_.20_.18_.16_.png%' UNION
    SELECT '8590.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/8590.jpg%' UNION
    SELECT 'AplusB.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/AplusB.png%' UNION
    SELECT 'bloodhound.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/bloodhound.jpg%' UNION
    SELECT 'cern.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/cern.jpg%' UNION
    SELECT 'dierny_stitok.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/dierny_stitok.png%' UNION
    SELECT 'dvere.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/dvere.jpg%' UNION
    SELECT 'fll-rotacia.zip' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/fll-rotacia.zip%' UNION
    SELECT 'hochschornerovci.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/hochschornerovci.jpg%' UNION
    SELECT 'hojdacka.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/hojdacka.jpg%' UNION
    SELECT 'hroch.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/hroch.jpg%' UNION
    SELECT 'IMAG1418.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/IMAG1418.jpg%' UNION
    SELECT 'karty.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/karty.png%' UNION
    SELECT 'lol.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/lol.jpg%' UNION
    SELECT 'prevodovkas.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/prevodovkas.png%' UNION
    SELECT 'priepast.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/priepast.jpg%' UNION
    SELECT 'projektove_vyucovanie_fll_2014.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/projektove_vyucovanie_fll_2014.png%' UNION
    SELECT 'robocupathome.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/robocupathome.jpg%' UNION
    SELECT 'robotchallenge_hand.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/robotchallenge_hand.jpg%' UNION
    SELECT 'rotacia.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/rotacia.png%' UNION
    SELECT 'skibot.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/skibot.jpg%' UNION
    SELECT 'slalom_trat1.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/slalom_trat1.png%' UNION
    SELECT 'slalom_trat2.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/slalom_trat2.png%' UNION
    SELECT 'spock.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/spock.jpg%' UNION
    SELECT 'stastny_hroch.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/stastny_hroch.jpg%' UNION
    SELECT 'stopar.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/stopar.png%' UNION
    SELECT 'tilebot.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/tilebot.png%' UNION
    SELECT 'turing.gif' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/turing.gif%' UNION
    SELECT 'vehicles.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/vehicles.png%' UNION
    SELECT 'vehicles1.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/vehicles1.png%' UNION
    SELECT 'vehicles2.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/vehicles2.png%' UNION
    SELECT 'vlasske-orechy.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/vlasske-orechy.jpg%' UNION
    SELECT 'watson.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/watson.jpg%') x 
ON (x.name = txt.sk COLLATE 'utf8_general_ci') 
ORDER BY x.file_name ASC, a.context_id ASC;

ALTER TABLE images AUTO_INCREMENT = 1;

INSERT INTO images (context_id, original_name) 
SELECT s.context_id, o_i.link
FROM old_img o_i
INNER JOIN old_solutions o_s ON (o_s.id = o_i.sid)
INNER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
ORDER BY o_i.link COLLATE 'utf8_slovak_ci' ASC ;

INSERT INTO programs (context_id, original_name) 
SELECT s.context_id, o_a.link
FROM old_attachments o_a
INNER JOIN old_solutions o_s ON (o_s.id = o_a.sid)
INNER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
ORDER BY o_a.link COLLATE 'utf8_slovak_ci' ASC;

ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE teams AUTO_INCREMENT = 1;
ALTER TABLE organisators AUTO_INCREMENT = 1;
ALTER TABLE contexts AUTO_INCREMENT = 1;
ALTER TABLE texts AUTO_INCREMENT = 1;
ALTER TABLE assignments AUTO_INCREMENT = 1;
ALTER TABLE solutions AUTO_INCREMENT = 1;
ALTER TABLE comments AUTO_INCREMENT = 1;
ALTER TABLE images AUTO_INCREMENT = 1;
ALTER TABLE programs AUTO_INCREMENT = 1;
ALTER TABLE videos AUTO_INCREMENT = 1;

UPDATE texts txt
INNER JOIN assignments a ON (a.text_id_description = txt.text_id)
INNER JOIN images i ON (i.context_id = a.context_id)
SET txt.sk = REPLACE (txt.sk, i.original_name, CONCAT_WS('/', 'assignments', i.context_id, 'images', i.image_id));

DROP TABLE old_attachments, old_img, old_missions, old_results, old_solutions, old_users, old_videos;
SET FOREIGN_KEY_CHECKS=1;
